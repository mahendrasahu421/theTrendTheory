<?php
// app/Http/Controllers/Admin/ImageKitController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageKitController extends Controller
{
    public function auth(Request $request)
    {
        $privateKey = config('services.imagekit.private_key');
        $token = $request->input('token', uniqid());
        $expire = time() + 2400; // 40 min valid
        $signature = hash_hmac('sha1', $token . $expire, $privateKey);

        return response()->json([
            'token' => $token,
            'expire' => $expire,
            'signature' => $signature,
        ]);
    }
}