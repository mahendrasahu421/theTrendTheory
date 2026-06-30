<?php
// app/Http/Controllers/WishlistController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // ── GET /wishlist ──────────────────────────────────────
    public function index()
    {
        $wishlist = session()->get('wishlist', []);
        $products = collect();

        if (!empty($wishlist)) {
            $products = Product::where('is_active', true)
                ->whereIn('id', $wishlist)
                ->get();
        }

        return view('froentend.wishlist.index', compact('products'));
    }

    // ── POST /wishlist/add/{id} ────────────────────────────
    public function add(Request $request, $id)
    {
        $product = Product::where('is_active', true)->find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.']);
        }

        $wishlist = session()->get('wishlist', []);

        if (!in_array($id, $wishlist)) {
            $wishlist[] = $id;
            session()->put('wishlist', $wishlist);
            $message = $product->name . ' added to wishlist!';
        } else {
            $message = $product->name . ' already in wishlist.';
        }

        return response()->json([
            'success'        => true,
            'message'        => $message,
            'wishlist_count' => count($wishlist),
        ]);
    }

    // ── DELETE /wishlist/{id} ──────────────────────────────
    public function remove(Request $request, $id)
    {
        $wishlist = session()->get('wishlist', []);
        $wishlist = array_values(array_filter($wishlist, function($item) use ($id) {
            return $item != $id;
        }));

        session()->put('wishlist', $wishlist);

        return response()->json([
            'success'        => true,
            'wishlist_count' => count($wishlist),
        ]);
    }
}