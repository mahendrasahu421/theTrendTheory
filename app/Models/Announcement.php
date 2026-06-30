<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'text', 'link', 'link_text',
        'bg_color', 'text_color', 'image_url', 'image_public_id', 'is_active', 'sort_order'
    ];
    protected $casts = ['is_active' => 'boolean'];
}
