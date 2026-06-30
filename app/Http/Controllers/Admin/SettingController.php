<?php
// app/Http/Controllers/Admin/SettingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\HeroSlide;
use App\Models\Media;
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
        $heroSlides = HeroSlide::orderBy('sort_order')->get();
        $lastBackup = Cache::get('last_backup', 'No backup yet');
        
        // Get media for gallery section
        $media = Media::where('collection', 'gallery')
            ->orWhere('collection', 'video_section')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.settings.index', compact('settings', 'heroSlides', 'lastBackup', 'media'));
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
                SiteSetting::set('logo', $upload['public_id']);
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
                SiteSetting::set('favicon', $upload['public_id']);
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
                SiteSetting::set('og_image', $upload['public_id']);
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
            'image_file' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
            'mobile_image_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $imageUrl = null;
            $mobileImageUrl = null;

// Upload desktop image to Cloudinary
            if ($request->hasFile('image_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('image_file'),
                    'hero-slides',
                    'slide_' . time()
                );
                $imageUrl = $upload['url'];
            }

// Upload mobile image if provided
            if ($request->hasFile('mobile_image_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('mobile_image_file'),
                    'hero-slides/mobile',
                    'slide_mobile_' . time()
                );
                $mobileImageUrl = $upload['url'];
            }

            HeroSlide::create([
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'image' => $imageUrl,
                'mobile_image' => $mobileImageUrl,
                'button_text' => $request->button_text ?? 'SHOP NOW',
                'button_link' => $request->button_link ?? '/shop',
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
                'image_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
                'mobile_image_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
                'button_text' => 'nullable|string|max:100',
                'button_link' => 'nullable|string|max:255',
                'sort_order' => 'integer|min:0',
                'is_active' => 'boolean',
            ]);

            $imageUrl = $slide->image;
            $mobileImageUrl = $slide->mobile_image;

// Upload new desktop image if provided
            if ($request->hasFile('image_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('image_file'),
                    'hero-slides',
                    'slide_' . time()
                );
                $imageUrl = $upload['url'];
            }

// Upload new mobile image if provided
            if ($request->hasFile('mobile_image_file')) {
                $upload = $this->cloudinary->upload(

                    $request->file('mobile_image_file'),
                    'hero-slides/mobile',
                    'slide_mobile_' . time()
                );
                $mobileImageUrl = $upload['url'];
            }

            $slide->update([
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'image' => $imageUrl,
                'mobile_image' => $mobileImageUrl,
                'button_text' => $request->button_text ?? 'SHOP NOW',
                'button_link' => $request->button_link ?? '/shop',
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
     * List all backups
     */
    public function listBackups()
    {
        $backupDir = storage_path('app/backups');
        $backups = [];

        if (File::exists($backupDir)) {
            $files = File::files($backupDir);
            foreach ($files as $file) {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatSize($file->getSize()),
                    'size_bytes' => $file->getSize(),
                    'created_at' => Carbon::createFromTimestamp($file->getCTime()),
                    'path' => $file->getPathname(),
                ];
            }
            usort($backups, fn($a, $b) => $b['created_at'] <=> $a['created_at']);
        }

        return view('admin.backups.index', compact('backups'));
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
    public function deleteBackup($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        if (File::exists($backupPath)) {
            File::delete($backupPath);
            return back()->with('success', 'Backup deleted successfully!');
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
}
