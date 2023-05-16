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
    protected $fillable = ['permintaan_pembelian_id', 'bom_id', 'part_id', 'aset_id', 'jumlah', 'harga', 'estimasi_harga', 'via', 'link', 'is_terdaftar'];


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
    public function Part()
    {
        return $this->belongsTo(Sparepart::class, 'part_id');
    }

    public function DetailPoPembelian()
    {
        return $this->hasMany(DetailPoPembelian::class);
    }
}
