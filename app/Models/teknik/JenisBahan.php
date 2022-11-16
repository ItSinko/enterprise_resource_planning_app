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
        return $this->hasMany(Produk::class);
    }

    function sparepart()
    {
        return $this->belongsToMany(Sparepart::class, 'm_sparepart_bahan', 'm_sparepart_id', 'jenis_bahan_id');
    }
}
