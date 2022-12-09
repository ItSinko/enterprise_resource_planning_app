<?php

namespace App\Models;

use App\Models\teknik\BillOfMaterial;
use App\Models\teknik\DetailBillOfMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPermintaanPembelian extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_permintaan_pembelian';

    public function PermintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class, 'permintaan_pembelian_id');
    }
    public function BillOfMaterial()
    {
        return $this->belongsTo(BillOfMaterial::class, 'bom_id');
    }
    public function Aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    public function DetailPermintaanPembelianProduk()
    {
        return $this->hasMany(DetailPermintaanPembelianProduk::class);
    }
}
