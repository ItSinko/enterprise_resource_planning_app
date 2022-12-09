<?php

namespace App\Models;

use App\Models\teknik\DetailBillOfMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPermintaanPembelianProduk extends Model
{
    use HasFactory;
    protected $table = 'detail_permintaan_pembelian_produk';

    public function DetailPermintaanPembelian()
    {
        return $this->belongsTo(DetailPermintaanPembelian::class, 'detail_permintaan_pembelian_id');
    }
    public function DetailBillOfMaterial()
    {
        return $this->belongsTo(DetailBillOfMaterial::class, 'detail_bom_id');
    }
    public function Sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'part_id');
    }
}
