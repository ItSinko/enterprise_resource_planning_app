<?php

namespace App\Models\teknik;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBahan extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'jenis_bahan';

    protected $fillable = ['nama'];

    function sparepart()
    {
        return $this->hasMany(Sparepart::class);
    }
    function produk()
    {
        return $this->hasMany(Produk::class);
    }
}
