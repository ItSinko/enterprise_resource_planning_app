<?php

namespace App\Models\teknik;

use App\Models\Produk;
use App\Models\Sparepart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBahan extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'jenis_bahan';

    protected $fillable = ['nama'];


    function produk()
    {
        return $this->belongsToMany(Produk::class, 'produk_bahan');
    }

    function sparepart()
    {
        return $this->belongsToMany(Sparepart::class, 'm_sparepart_bahan');
    }
}
