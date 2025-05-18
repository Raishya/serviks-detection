<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Diagnosa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nama', 'usia', 'tanggal_pemeriksaan', 'jenis_pemeriksaan', 'image_path',
        'canvas_output_path', 'mask_canvas_path', 'prediction', 'confidence', 'diagnosa'
    ];
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($diagnosa) {
            // Delete the file from the storage
            if ($diagnosa->image_path) {
                Storage::delete('public/' . $diagnosa->image_path);
            }
        });
    }
}
