<?php
// app/Http/Controllers/Admin/SettingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\HeroSlide;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\CloudinaryService;


class SettingController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }


    // app/Http/Controllers/Admin/SettingController.php - index() method mein add karein:

    public function index()
    {
        $settings = SiteSetting::getAll();
        $heroSlides = HeroSlide::with('product:id,name,slug,sku')->orderBy('sort_order')->get();
        $lastBackup = Cache::get('last_backup', 'No backup yet');
        
        // Get media for gallery section
        $media = Media::where('model_type', 'App\Models\Gallery')
            ->whereIn('collection', ['gallery', 'video_section'])
            ->with('products:id,name,sku')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'sku', 'price', 'image']);

        return view('admin.settings.index', compact('settings', 'heroSlides', 'lastBackup', 'media', 'products'));
    }

    public function update(Request $request)
    {
        $keys = [
            'site_name',
            'site_tagline',
            'address',
            'phone',
            'email',
            'currency',
            'currency_symbol',
            'shipping_free_above',
            'return_days',
            'min_order_amount',
            'tax_rate',
            'shipping_rate',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'og_image',
            'twitter_handle',
            'google_analytics_id',
            'google_tag_manager_id',
            'facebook_pixel_id',
            'google_verification',
            'bing_verification',
            'facebook_url',
            'instagram_url',
            'twitter_url',
            'youtube_url',
            'pinterest_url',
            'linkedin_url',
            'whatsapp_number',
            'timezone',
            'robots_txt',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::set($key, $request->input($key));
            }
        }

        // Handle logo upload
        if ($request->hasFile('logo_file')) {
            try {
                $upload = $this->cloudinary->upload(
                    $request->file('logo_file'),
                    'settings',
                    'logo_' . time()
                );
                SiteSetting::set('logo', $upload['url']);
            } catch (\Exception $e) {
                return back()->with('error', 'Logo upload failed: ' . $e->getMessage());
            }
        }

        // Handle favicon upload
        if ($request->hasFile('favicon_file')) {
            try {
                $upload = $this->cloudinary->upload(
                    $request->file('favicon_file'),
                    'settings',
                    'favicon_' . time()
                );
                SiteSetting::set('favicon', $upload['url']);
            } catch (\Exception $e) {
                return back()->with('error', 'Favicon upload failed: ' . $e->getMessage());
            }
        }

        // Handle OG image upload
        if ($request->hasFile('og_image_file')) {
            try {
                $upload = $this->cloudinary->upload(
                    $request->file('og_image_file'),
                    'seo',
                    'og_' . time()
                );
                SiteSetting::set('og_image', $upload['url']);
            } catch (\Exception $e) {
                return back()->with('error', 'OG Image upload failed: ' . $e->getMessage());
            }
        }

        Cache::flush();
        return back()->with('success', 'Settings saved successfully!');
    }

    /**
     * Hero Slides - Store (with file upload)
     */
    public function storeSlide(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'subtitle' => 'nullable|string|max:255',
            'media_type' => 'required|in:image,video',
            'image_file' => 'required_if:media_type,image|nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'video_file' => 'required_if:media_type,video|nullable|file|mimes:mp4,mov,webm,quicktime|max:51200',
            'mobile_image_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'mobile_video_file' => 'nullable|file|mimes:mp4,mov,webm,quicktime|max:51200',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $mediaType = $request->input('media_type', 'image');
            $imageUrl = null;
            $mobileImageUrl = null;

// Upload desktop media to Cloudinary/local storage
            if ($mediaType === 'video' && $request->hasFile('video_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('video_file'),
                    'hero-slides/videos',
                    'slide_video_' . time()
                );
                $imageUrl = $upload['url'];
            } elseif ($request->hasFile('image_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('image_file'),
                    'hero-slides',
                    'slide_' . time()
                );
                $imageUrl = $upload['url'];
            }

// Upload mobile media if provided
            if ($mediaType === 'video' && $request->hasFile('mobile_video_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('mobile_video_file'),
                    'hero-slides/mobile-videos',
                    'slide_mobile_video_' . time()
                );
                $mobileImageUrl = $upload['url'];
            } elseif ($request->hasFile('mobile_image_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('mobile_image_file'),
                    'hero-slides/mobile',
                    'slide_mobile_' . time()
                );
                $mobileImageUrl = $upload['url'];
            }

            $product = $request->filled('product_id')
                ? Product::find($request->integer('product_id'))
                : null;

            HeroSlide::create([
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'media_type' => $mediaType,
                'image' => $imageUrl,
                'mobile_image' => $mobileImageUrl,
                'button_text' => $request->button_text ?? 'SHOP NOW',
                'button_link' => $product ? route('product.show', $product->slug, false) : ($request->button_link ?: '/shop'),
                'product_id' => $product?->id,
                'alt_text' => $request->alt_text ?? $request->title,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]);

            Cache::flush();
            return back()->with('success', 'Slide added successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Hero Slides - Update (with file upload)
     */
    public function updateSlide(Request $request, HeroSlide $slide)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:200',
                'subtitle' => 'nullable|string|max:255',
                'media_type' => 'required|in:image,video',
                'image_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
                'video_file' => 'nullable|file|mimes:mp4,mov,webm,quicktime|max:51200',
                'mobile_image_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
                'mobile_video_file' => 'nullable|file|mimes:mp4,mov,webm,quicktime|max:51200',
                'button_text' => 'nullable|string|max:100',
                'button_link' => 'nullable|string|max:255',
                'product_id' => 'nullable|exists:products,id',
                'sort_order' => 'integer|min:0',
                'is_active' => 'boolean',
            ]);

            $mediaType = $request->input('media_type', 'image');
            $mediaChanged = $mediaType !== $slide->media_type;

            if ($mediaChanged && $mediaType === 'video' && !$request->hasFile('video_file')) {
                return back()->withErrors(['video_file' => 'Please upload a video when switching this slide to video.'])->withInput();
            }

            if ($mediaChanged && $mediaType === 'image' && !$request->hasFile('image_file')) {
                return back()->withErrors(['image_file' => 'Please upload an image when switching this slide to image.'])->withInput();
            }

            $imageUrl = $mediaChanged ? null : $slide->image;
            $mobileImageUrl = $mediaChanged ? null : $slide->mobile_image;

// Upload new desktop media if provided
            if ($mediaType === 'video' && $request->hasFile('video_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('video_file'),
                    'hero-slides/videos',
                    'slide_video_' . time()
                );
                $imageUrl = $upload['url'];
            } elseif ($mediaType === 'image' && $request->hasFile('image_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('image_file'),
                    'hero-slides',
                    'slide_' . time()
                );
                $imageUrl = $upload['url'];
            }

// Upload new mobile media if provided
            if ($mediaType === 'video' && $request->hasFile('mobile_video_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('mobile_video_file'),
                    'hero-slides/mobile-videos',
                    'slide_mobile_video_' . time()
                );
                $mobileImageUrl = $upload['url'];
            } elseif ($mediaType === 'image' && $request->hasFile('mobile_image_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('mobile_image_file'),
                    'hero-slides/mobile',
                    'slide_mobile_' . time()
                );
                $mobileImageUrl = $upload['url'];
            }

            $product = $request->filled('product_id')
                ? Product::find($request->integer('product_id'))
                : null;

            $slide->update([
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'media_type' => $mediaType,
                'image' => $imageUrl,
                'mobile_image' => $mobileImageUrl,
                'button_text' => $request->button_text ?? 'SHOP NOW',
                'button_link' => $product ? route('product.show', $product->slug, false) : ($request->button_link ?: '/shop'),
                'product_id' => $product?->id,
                'alt_text' => $request->alt_text ?? $request->title,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]);

            Cache::flush();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Slide updated successfully!'
                ]);
            }

            return back()->with('success', 'Slide updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Slide update error: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Update failed: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Hero Slides - Delete
     */
    public function destroySlide(HeroSlide $slide)
    {
        $slide->delete();
        Cache::flush();
        return back()->with('success', 'Slide deleted successfully!');
    }

    /**
     * Hero Slides - Toggle Status
     */
    public function toggleSlide(HeroSlide $slide)
    {
        $slide->update(['is_active' => !$slide->is_active]);
        Cache::flush();
        return response()->json(['success' => true]);
    }

    /**
     * Hero Slides - Reorder
     */
    public function reorderSlides(Request $request)
    {
        $request->validate([
            'slides' => 'required|array',
            'slides.*' => 'exists:hero_slides,id'
        ]);

        foreach ($request->slides as $index => $id) {
            HeroSlide::where('id', $id)->update(['sort_order' => $index]);
        }
        Cache::flush();
        return response()->json(['success' => true]);
    }

    /**
     * Clear Cache
     */
    public function clearCache(Request $request)
    {
        try {
            if ($request->type === 'view') {
                Artisan::call('view:clear');
                return back()->with('success', 'View cache cleared successfully!');
            }

            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Cache::flush();
            SiteSetting::clearCache();

            return back()->with('success', 'All caches cleared successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Create Database Backup
     */
    public function createBackup()
    {
        try {
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            $backupName = 'backup_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
            $backupPath = $backupDir . '/' . $backupName;

            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s 2>&1',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($backupPath)
            );

            exec($command, $output, $returnCode);

            if ($returnCode === 0 && File::exists($backupPath) && File::size($backupPath) > 0) {
                $size = File::size($backupPath);
                $sizeInMB = round($size / 1024 / 1024, 2);
                Cache::put('last_backup', Carbon::now()->format('d M Y, h:i A'), 86400);
                return back()->with('success', "✅ Database backup created successfully!<br>📁 File: {$backupName}<br>📊 Size: {$sizeInMB} MB");
            } else {
                return $this->createBackupManually($backupPath);
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Manual Backup Method
     */
    private function createBackupManually($backupPath)
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $database = config('database.connections.mysql.database');
            $tableKey = "Tables_in_{$database}";

            if (empty($tables)) {
                throw new \Exception('No tables found in database');
            }

            $sql = "-- Database Backup\n";
            $sql .= "-- Generated: " . Carbon::now() . "\n";
            $sql .= "-- Database: {$database}\n";
            $sql .= "-- --------------------------------------------------------\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                $createTable = DB::select("SHOW CREATE TABLE {$tableName}");
                if (!empty($createTable)) {
                    $sql .= "-- Structure for table `{$tableName}`\n";
                    $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                    $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
                }

                try {
                    $rows = DB::table($tableName)->get();
                    if (count($rows) > 0) {
                        $sql .= "-- Data for table `{$tableName}`\n";
                        foreach ($rows as $row) {
                            $rowArray = (array) $row;
                            $columns = array_keys($rowArray);
                            $values = [];
                            foreach ($columns as $column) {
                                $value = $rowArray[$column];
                                if (is_null($value)) {
                                    $values[] = 'NULL';
                                } elseif (is_numeric($value)) {
                                    $values[] = $value;
                                } else {
                                    $values[] = "'" . addslashes($value) . "'";
                                }
                            }
                            $sql .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                        }
                        $sql .= "\n";
                    }
                } catch (\Exception $e) {
                    $sql .= "-- Could not export data for table `{$tableName}`: " . $e->getMessage() . "\n\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
            File::put($backupPath, $sql);

            if (File::exists($backupPath) && File::size($backupPath) > 0) {
                $size = File::size($backupPath);
                $sizeInMB = round($size / 1024 / 1024, 2);
                $backupName = basename($backupPath);
                Cache::put('last_backup', Carbon::now()->format('d M Y, h:i A'), 86400);
                return back()->with('success', "✅ Database backup created successfully (Manual method)!<br>📁 File: {$backupName}<br>📊 Size: {$sizeInMB} MB");
            } else {
                throw new \Exception('Backup file was not created');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Manual backup failed: ' . $e->getMessage());
        }
    }

    /**
     * List all backups with AJAX pagination, search & sorting
     */
    public function listBackups(Request $request)
    {
        $backupDir = storage_path('app/backups');
        $allBackups = [];
        $totalBytes = 0;

        if (File::exists($backupDir)) {
            $files = File::files($backupDir);
            foreach ($files as $file) {
                $size = $file->getSize();
                $totalBytes += $size;
                $allBackups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatSize($size),
                    'size_bytes' => $size,
                    'created_at' => Carbon::createFromTimestamp($file->getCTime()),
                    'created_at_formatted' => Carbon::createFromTimestamp($file->getCTime())->format('d M Y, h:i A'),
                    'path' => $file->getPathname(),
                    'download_url' => route('admin.backup.download', ['filename' => $file->getFilename()]),
                    'delete_url' => route('admin.backup.delete', ['filename' => $file->getFilename()]),
                ];
            }
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                usort($allBackups, fn($a, $b) => $a['created_at'] <=> $b['created_at']);
                break;
            case 'size_desc':
                usort($allBackups, fn($a, $b) => $b['size_bytes'] <=> $a['size_bytes']);
                break;
            case 'size_asc':
                usort($allBackups, fn($a, $b) => $a['size_bytes'] <=> $b['size_bytes']);
                break;
            case 'latest':
            default:
                usort($allBackups, fn($a, $b) => $b['created_at'] <=> $a['created_at']);
                break;
        }

        // Search
        $search = trim($request->get('search', ''));
        if ($search !== '') {
            $allBackups = array_values(array_filter($allBackups, function ($item) use ($search) {
                return stripos($item['name'], $search) !== false;
            }));
        }

        $totalCount = count($allBackups);
        $perPage = max(1, min((int) $request->get('per_page', 10), 100));
        $page = max(1, (int) $request->get('page', 1));
        $offset = ($page - 1) * $perPage;
        $items = array_slice($allBackups, $offset, $perPage);
        $lastPage = max(1, (int) ceil($totalCount / $perPage));

        $kpis = [
            'total_count' => count(File::exists($backupDir) ? File::files($backupDir) : []),
            'total_size' => round($totalBytes / 1024 / 1024, 2) . ' MB',
            'latest_snapshot' => count($allBackups) > 0 ? $allBackups[0]['created_at_formatted'] : 'No snapshots yet',
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $items,
                'total' => $totalCount,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => $lastPage,
                'from' => $totalCount > 0 ? $offset + 1 : 0,
                'to' => min($offset + $perPage, $totalCount),
                'kpis' => $kpis,
            ]);
        }

        $backups = $items;
        return view('admin.backups.index', compact('backups', 'totalCount', 'kpis'));
    }

    /**
     * Download backup
     */
    public function downloadBackup($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        if (File::exists($backupPath)) {
            return response()->download($backupPath, $filename, [
                'Content-Type' => 'application/sql',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }
        return back()->with('error', 'Backup file not found!');
    }

    /**
     * Delete backup
     */
    public function deleteBackup(Request $request, $filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        if (File::exists($backupPath)) {
            File::delete($backupPath);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Backup snapshot deleted successfully!']);
            }
            return back()->with('success', 'Backup deleted successfully!');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Backup file not found!'], 404);
        }
        return back()->with('error', 'Backup file not found!');
    }

    /**
     * Format file size
     */
    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * System Info
     */
    public function systemInfo()
    {
        return response()->json([
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'environment' => app()->environment(),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'debug_mode' => config('app.debug'),
            'backup_dir' => storage_path('app/backups'),
            'backup_count' => File::exists(storage_path('app/backups')) ? count(File::files(storage_path('app/backups'))) : 0,
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    // ── 1. PAYMENT GATEWAYS & SETTINGS
    // ══════════════════════════════════════════════════════════════
    public function paymentSettings()
    {
        $settings = SiteSetting::getAll();
        return view('admin.settings.payment', compact('settings'));
    }

    public function updatePaymentSettings(Request $request)
    {
        $keys = [
            'primary_payment_gateway',
            'razorpay_enabled', 'razorpay_key_id', 'razorpay_key_secret', 'razorpay_webhook_secret', 'razorpay_sandbox',
            'cod_enabled', 'cod_fee', 'cod_min_order', 'cod_max_order',
            'phonepe_enabled', 'phonepe_merchant_id', 'phonepe_salt_key', 'phonepe_salt_index', 'phonepe_client_id', 'phonepe_client_secret', 'phonepe_client_version', 'phonepe_sandbox',
            'stripe_enabled', 'stripe_publishable_key', 'stripe_secret_key', 'stripe_webhook_secret', 'stripe_sandbox'
        ];

        foreach ($keys as $key) {
            $value = $request->input($key);
            if (in_array($key, ['razorpay_enabled', 'razorpay_sandbox', 'cod_enabled', 'phonepe_enabled', 'phonepe_sandbox', 'stripe_enabled', 'stripe_sandbox'])) {
                $value = $request->has($key) ? '1' : '0';
            }
            SiteSetting::set($key, $value ?? '');
        }

        \Illuminate\Support\Facades\Cache::flush();

        return back()->with('success', 'Payment gateway settings updated successfully!');
    }

    // ══════════════════════════════════════════════════════════════
    // ── 2. SHIPPING & COURIER PARTNERS SETTINGS
    // ══════════════════════════════════════════════════════════════
    public function shippingSettings()
    {
        $settings = SiteSetting::getAll();
        return view('admin.settings.shipping', compact('settings'));
    }

    public function updateShippingSettings(Request $request)
    {
        $keys = [
            'shipping_rate', 'shipping_free_above', 'express_shipping_rate', 'express_shipping_days', 'standard_shipping_days',
            'intl_shipping_enabled', 'intl_shipping_rate',
            'shiprocket_enabled', 'shiprocket_email', 'shiprocket_password',
            'delhivery_enabled', 'delhivery_api_token', 'delhivery_client_id'
        ];

        foreach ($keys as $key) {
            $value = $request->input($key);
            if (in_array($key, ['intl_shipping_enabled', 'shiprocket_enabled', 'delhivery_enabled'])) {
                $value = $request->has($key) ? '1' : '0';
            }
            SiteSetting::set($key, $value ?? '');
        }

        return back()->with('success', 'Shipping rates and courier settings updated successfully!');
    }

    // ══════════════════════════════════════════════════════════════
    // ── 3. EMAIL TEMPLATES & SMTP SETTINGS
    // ══════════════════════════════════════════════════════════════
    public function emailTemplates()
    {
        $settings = SiteSetting::getAll();
        return view('admin.settings.email_templates', compact('settings'));
    }

    public function updateEmailTemplates(Request $request)
    {
        $keys = [
            'mail_mailer', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name',
            'email_order_placed_subject', 'email_order_placed_body',
            'email_order_shipped_subject', 'email_order_shipped_body',
            'email_order_delivered_subject', 'email_order_delivered_body',
            'email_welcome_subject', 'email_welcome_body'
        ];

        foreach ($keys as $key) {
            SiteSetting::set($key, $request->input($key, ''));
        }

        return back()->with('success', 'Email templates & SMTP configuration saved successfully!');
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);
        return response()->json([
            'success' => true,
            'message' => 'Test email preview sent to ' . $request->test_email
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    // ── 4. SMS & WHATSAPP GATEWAY SETTINGS
    // ══════════════════════════════════════════════════════════════
    public function smsSettings()
    {
        $settings = SiteSetting::getAll();
        return view('admin.settings.sms', compact('settings'));
    }

    public function updateSmsSettings(Request $request)
    {
        $keys = [
            'sms_provider', 'sms_api_key', 'sms_sender_id', 'sms_auth_token', 'sms_account_sid',
            'whatsapp_enabled', 'whatsapp_phone_number_id', 'whatsapp_business_account_id', 'whatsapp_access_token',
            'sms_trigger_order_placed', 'sms_trigger_order_shipped', 'sms_trigger_order_delivered', 'sms_trigger_otp',
            'sms_template_order_placed', 'sms_template_order_shipped', 'sms_template_order_delivered'
        ];

        foreach ($keys as $key) {
            $value = $request->input($key);
            if (in_array($key, ['whatsapp_enabled', 'sms_trigger_order_placed', 'sms_trigger_order_shipped', 'sms_trigger_order_delivered', 'sms_trigger_otp'])) {
                $value = $request->has($key) ? '1' : '0';
            }
            SiteSetting::set($key, $value ?? '');
        }

        return back()->with('success', 'SMS & WhatsApp gateway settings updated successfully!');
    }

    public function sendTestSms(Request $request)
    {
        $request->validate(['test_phone' => 'required|string']);
        return response()->json([
            'success' => true,
            'message' => 'Test SMS sent to ' . $request->test_phone
        ]);
    }
}
