<?php

namespace App\Services;

use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class VisitorTrackerService
{
    /**
     * Track incoming web visitor request.
     */
    public function track(Request $request): ?VisitorLog
    {
        // Don't track console, background, or internal API/Asset requests
        if ($this->shouldSkip($request)) {
            return null;
        }

        $ip = $this->getClientIp($request);
        $sessionId = $request->session()->getId() ?: ('guest_' . md5($ip . $request->userAgent()));
        $userAgent = (string) $request->userAgent();

        // 1. Resolve Geo Location
        $geo = $this->resolveGeoLocation($ip, $request);

        // 2. Resolve Traffic Source & Referral
        $sourceData = $this->resolveTrafficSource($request);

        // 3. Resolve Device, Phone Model, OS & Browser
        $deviceData = $this->resolveDeviceData($userAgent);

        $userId = auth()->id();
        $currentUrl = $request->fullUrl();

        // Check if log already exists for this session today
        $log = VisitorLog::where('session_id', $sessionId)
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if ($log) {
            $log->update([
                'user_id'           => $userId ?: $log->user_id,
                'current_url'       => $currentUrl,
                'page_views_count'  => $log->page_views_count + 1,
                'last_activity_at'  => now(),
            ]);
            return $log;
        }

        // Create new log record
        return VisitorLog::create([
            'session_id'        => $sessionId,
            'user_id'           => $userId,
            'ip_address'        => $ip,
            'country'           => $geo['country'],
            'country_code'      => $geo['country_code'],
            'state'             => $geo['state'],
            'city'              => $geo['city'],
            'postal_code'       => $geo['postal_code'],
            'latitude'          => $geo['latitude'],
            'longitude'         => $geo['longitude'],
            'source'            => $sourceData['source'],
            'referrer_url'      => $sourceData['referrer_url'],
            'landing_page'      => $currentUrl,
            'current_url'       => $currentUrl,
            'utm_source'        => $sourceData['utm_source'],
            'utm_medium'        => $sourceData['utm_medium'],
            'utm_campaign'      => $sourceData['utm_campaign'],
            'utm_term'          => $sourceData['utm_term'],
            'utm_content'       => $sourceData['utm_content'],
            'device_type'       => $deviceData['device_type'],
            'device_brand'      => $deviceData['device_brand'],
            'device_model'      => $deviceData['device_model'],
            'os'                => $deviceData['os'],
            'os_version'        => $deviceData['os_version'],
            'browser'           => $deviceData['browser'],
            'browser_version'   => $deviceData['browser_version'],
            'user_agent'        => $userAgent,
            'page_views_count'  => 1,
            'last_activity_at'  => now(),
        ]);
    }

    /**
     * Check if request should be ignored.
     */
    protected function shouldSkip(Request $request): bool
    {
        $path = $request->path();
        
        // Skip admin panel, internal API, image and asset requests
        if ($request->is('admin*', 'api*', 'up', '_debugbar*', 'sanctum*') ||
            $request->ajax() ||
            $request->isMethod('POST') ||
            $request->isMethod('PUT') ||
            $request->isMethod('PATCH') ||
            $request->isMethod('DELETE') ||
            Str::endsWith($path, ['.js', '.css', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.webp', '.ico', '.woff', '.woff2', '.ttf', '.map'])) {
            return true;
        }

        // Skip search engine bots from polluting analytics
        $ua = strtolower((string) $request->userAgent());
        if (Str::contains($ua, ['googlebot', 'bingbot', 'yandexbot', 'duckduckbot', 'slurp', 'baiduspider', 'ahrefsbot', 'semrushbot'])) {
            return true;
        }

        return false;
    }

    /**
     * Extract real client IP taking proxies / Cloudflare into account.
     */
    protected function getClientIp(Request $request): string
    {
        return $request->header('CF-Connecting-IP')
            ?: $request->header('X-Forwarded-For')
            ?: $request->header('X-Real-IP')
            ?: $request->ip()
            ?: '127.0.0.1';
    }

    /**
     * Resolve Geo Location (City, State, Country).
     */
    public function resolveGeoLocation(string $ip, Request $request): array
    {
        // First check Cloudflare / CDN headers
        $cfCountry = $request->header('CF-IPCountry');
        $cfCity = $request->header('CF-IPCity');
        $cfRegion = $request->header('CF-Region') ?: $request->header('CF-Region-Code');

        if ($cfCountry && $cfCity) {
            return [
                'country'      => $cfCountry === 'IN' ? 'India' : $cfCountry,
                'country_code' => $cfCountry,
                'state'        => $cfRegion ?: 'Unknown',
                'city'         => $cfCity ?: 'Unknown',
                'postal_code'  => $request->header('CF-Postal-Code'),
                'latitude'     => null,
                'longitude'    => null,
            ];
        }

        // For local development / private IP addresses
        if ($this->isPrivateIp($ip)) {
            return [
                'country'      => 'India',
                'country_code' => 'IN',
                'state'        => 'Maharashtra',
                'city'         => 'Mumbai',
                'postal_code'  => '400001',
                'latitude'     => 19.0760,
                'longitude'    => 72.8777,
            ];
        }

        // Cache public IP lookups for 7 days
        return Cache::remember('geoip_' . md5($ip), 604800, function () use ($ip) {
            try {
                $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,regionName,city,zip,lat,lon");
                if ($response->successful() && $response->json('status') === 'success') {
                    $data = $response->json();
                    return [
                        'country'      => $data['country'] ?? 'Unknown',
                        'country_code' => $data['countryCode'] ?? null,
                        'state'        => $data['regionName'] ?? 'Unknown',
                        'city'         => $data['city'] ?? 'Unknown',
                        'postal_code'  => $data['zip'] ?? null,
                        'latitude'     => $data['lat'] ?? null,
                        'longitude'    => $data['lon'] ?? null,
                    ];
                }
            } catch (\Throwable $e) {
                // Fallback on timeout
            }

            return [
                'country'      => 'Unknown',
                'country_code' => null,
                'state'        => 'Unknown',
                'city'         => 'Unknown',
                'postal_code'  => null,
                'latitude'     => null,
                'longitude'    => null,
            ];
        });
    }

    /**
     * Determine if an IP is private/loopback.
     */
    protected function isPrivateIp(string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1', 'localhost']) ||
            !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    /**
     * Resolve traffic origin / source.
     */
    public function resolveTrafficSource(Request $request): array
    {
        $utmSource = $request->query('utm_source');
        $utmMedium = $request->query('utm_medium');
        $utmCampaign = $request->query('utm_campaign');
        $utmTerm = $request->query('utm_term');
        $utmContent = $request->query('utm_content');

        $referrer = $request->header('referer') ?: $request->header('referrer');
        $source = 'Direct';

        // 1. Explicit UTM source
        if ($utmSource) {
            $source = ucfirst(strtolower($utmSource));
        }
        // 2. Facebook Click ID
        elseif ($request->query('fbclid')) {
            $source = 'Facebook';
        }
        // 3. Google Click ID
        elseif ($request->query('gclid')) {
            $source = 'Google Ads';
        }
        // 4. In-App User-Agent detection (Instagram, Facebook)
        elseif (Str::contains((string)$request->userAgent(), 'Instagram')) {
            $source = 'Instagram';
        }
        elseif (Str::contains((string)$request->userAgent(), ['FBAV', 'FBAN', 'FB_IAB'])) {
            $source = 'Facebook';
        }
        // 5. Referrer URL parsing
        elseif ($referrer) {
            $host = strtolower(parse_url($referrer, PHP_URL_HOST) ?? '');

            if (Str::contains($host, ['instagram.com', 'l.instagram.com', 'ig.me'])) {
                $source = 'Instagram';
            } elseif (Str::contains($host, ['facebook.com', 'l.facebook.com', 'm.facebook.com', 'fb.me'])) {
                $source = 'Facebook';
            } elseif (Str::contains($host, ['google.com', 'google.co.in', 'google.co.uk', 'google.ca'])) {
                $source = 'Google Search';
            } elseif (Str::contains($host, ['youtube.com', 'youtu.be'])) {
                $source = 'YouTube';
            } elseif (Str::contains($host, ['whatsapp.com', 'api.whatsapp.com', 'wa.me'])) {
                $source = 'WhatsApp';
            } elseif (Str::contains($host, ['t.co', 'twitter.com', 'x.com'])) {
                $source = 'X (Twitter)';
            } elseif (Str::contains($host, ['pinterest.com', 'pin.it'])) {
                $source = 'Pinterest';
            } elseif (Str::contains($host, ['snapchat.com'])) {
                $source = 'Snapchat';
            } elseif (Str::contains($host, ['linkedin.com', 'lnkd.in'])) {
                $source = 'LinkedIn';
            } elseif ($host === strtolower($request->getHost())) {
                $source = 'Direct';
            } else {
                $source = 'Referral (' . $host . ')';
            }
        }

        return [
            'source'       => $source,
            'referrer_url' => $referrer,
            'utm_source'   => $utmSource,
            'utm_medium'   => $utmMedium,
            'utm_campaign' => $utmCampaign,
            'utm_term'     => $utmTerm,
            'utm_content'  => $utmContent,
        ];
    }

    /**
     * Parse User Agent for Phone Model, Brand, OS, and Browser.
     */
    public function resolveDeviceData(string $ua): array
    {
        $deviceType = 'desktop';
        $deviceBrand = 'Unknown';
        $deviceModel = 'Desktop PC';
        $os = 'Unknown OS';
        $osVersion = null;
        $browser = 'Unknown';
        $browserVersion = null;

        // Device Type Detection
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobile))/i', $ua)) {
            $deviceType = 'tablet';
        } elseif (preg_match('/(mobile|iphone|ipod|android|blackberry|iemobile|opera mini|phone)/i', $ua)) {
            $deviceType = 'mobile';
        } else {
            $deviceType = 'desktop';
        }

        // ── Operating System ─────────────────────────────────
        if (preg_match('/iPhone|iPad|iPod/i', $ua)) {
            $os = 'iOS';
            if (preg_match('/OS\s+([0-9_\.]+)/i', $ua, $m)) {
                $osVersion = str_replace('_', '.', $m[1]);
            }
        } elseif (preg_match('/Android\s+([0-9\.]+)/i', $ua, $m)) {
            $os = 'Android';
            $osVersion = $m[1] ?? null;
        } elseif (preg_match('/Windows NT\s+([0-9\.]+)/i', $ua, $m)) {
            $os = 'Windows';
            $verMap = ['10.0' => '10 / 11', '6.3' => '8.1', '6.2' => '8', '6.1' => '7'];
            $osVersion = $verMap[$m[1]] ?? $m[1];
        } elseif (preg_match('/Mac OS X\s+([0-9_\.]+)/i', $ua, $m)) {
            $os = 'macOS';
            $osVersion = str_replace('_', '.', $m[1]);
        } elseif (preg_match('/Linux/i', $ua)) {
            $os = 'Linux';
        }

        // ── Phone Brand & Model Detection ─────────────────────
        if (preg_match('/iPhone/i', $ua)) {
            $deviceBrand = 'Apple';
            $deviceModel = 'iPhone';
        } elseif (preg_match('/iPad/i', $ua)) {
            $deviceBrand = 'Apple';
            $deviceModel = 'iPad';
        } elseif (preg_match('/Macintosh/i', $ua)) {
            $deviceBrand = 'Apple';
            $deviceModel = 'MacBook / Mac';
        } elseif (preg_match('/SM-[A-Z0-9]+/i', $ua, $m)) {
            $deviceBrand = 'Samsung';
            $deviceModel = 'Galaxy (' . $m[0] . ')';
        } elseif (preg_match('/Samsung/i', $ua)) {
            $deviceBrand = 'Samsung';
            $deviceModel = 'Galaxy';
        } elseif (preg_match('/(CPH[0-9]+|OPPO\s+[A-Z0-9]+|Find\s+[X0-9]+|Reno\s*[0-9]+)/i', $ua, $m)) {
            $deviceBrand = 'Oppo';
            $deviceModel = 'Oppo ' . $m[0];
        } elseif (preg_match('/(OnePlus|GM19[0-9]+|NE22[0-9]+|IN20[0-9]+|CPH24[0-9]+)/i', $ua, $m)) {
            $deviceBrand = 'OnePlus';
            $deviceModel = 'OnePlus (' . $m[0] . ')';
        } elseif (preg_match('/(Redmi|POCO|Xiaomi|M2[0-9]+[A-Z]+|22[0-9]+[A-Z]+|23[0-9]+[A-Z]+)/i', $ua, $m)) {
            $deviceBrand = 'Xiaomi';
            $deviceModel = 'Xiaomi / ' . $m[0];
        } elseif (preg_match('/(vivo|iQOO|V2[0-9]+|I2[0-9]+)/i', $ua, $m)) {
            $deviceBrand = 'Vivo';
            $deviceModel = 'Vivo / ' . $m[0];
        } elseif (preg_match('/(realme|RMX[0-9]+)/i', $ua, $m)) {
            $deviceBrand = 'Realme';
            $deviceModel = 'Realme (' . $m[0] . ')';
        } elseif (preg_match('/Pixel\s+([0-9a-zA-Z\s]+)/i', $ua, $m)) {
            $deviceBrand = 'Google';
            $deviceModel = 'Pixel ' . $m[1];
        } elseif (preg_match('/(moto|motorola)/i', $ua)) {
            $deviceBrand = 'Motorola';
            $deviceModel = 'Moto Device';
        } elseif ($deviceType === 'desktop') {
            $deviceBrand = $os === 'macOS' ? 'Apple' : 'Desktop PC';
            $deviceModel = $os === 'macOS' ? 'Mac' : 'Windows / PC';
        } else {
            $deviceBrand = 'Android Device';
            $deviceModel = 'Generic Mobile';
        }

        // ── Browser Detection ─────────────────────────────────
        if (preg_match('/Instagram/i', $ua)) {
            $browser = 'Instagram In-App';
        } elseif (preg_match('/(FBAV|FBAN|FB_IAB)/i', $ua)) {
            $browser = 'Facebook In-App';
        } elseif (preg_match('/Snapchat/i', $ua)) {
            $browser = 'Snapchat In-App';
        } elseif (preg_match('/WhatsApp/i', $ua)) {
            $browser = 'WhatsApp In-App';
        } elseif (preg_match('/Edg\/([0-9\.]+)/i', $ua, $m)) {
            $browser = 'Microsoft Edge';
            $browserVersion = $m[1];
        } elseif (preg_match('/SamsungBrowser\/([0-9\.]+)/i', $ua, $m)) {
            $browser = 'Samsung Internet';
            $browserVersion = $m[1];
        } elseif (preg_match('/Chrome\/([0-9\.]+)/i', $ua, $m)) {
            $browser = 'Chrome';
            $browserVersion = $m[1];
        } elseif (preg_match('/Safari\/([0-9\.]+)/i', $ua, $m) && !preg_match('/Chrome/i', $ua)) {
            $browser = 'Safari';
            if (preg_match('/Version\/([0-9\.]+)/i', $ua, $vm)) {
                $browserVersion = $vm[1];
            }
        } elseif (preg_match('/Firefox\/([0-9\.]+)/i', $ua, $m)) {
            $browser = 'Firefox';
            $browserVersion = $m[1];
        } elseif (preg_match('/OPR\/([0-9\.]+)/i', $ua, $m) || preg_match('/Opera/i', $ua)) {
            $browser = 'Opera';
        }

        return [
            'device_type'     => $deviceType,
            'device_brand'    => $deviceBrand,
            'device_model'    => $deviceModel,
            'os'              => $os,
            'os_version'      => $osVersion,
            'browser'         => $browser,
            'browser_version' => $browserVersion,
        ];
    }
}
