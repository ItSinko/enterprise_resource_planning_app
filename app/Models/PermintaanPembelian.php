<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanPembelian extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'permintaan_pembelian';

    public function DetailPermintaanPembelian()
    {
        return $this->hasMany(DetailPermintaanPembelian::class);
    }
}
