<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileProduk extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'file_produk';
    protected $fillable = ['produk_id', 'path'];

    function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
