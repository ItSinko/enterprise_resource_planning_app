<?php

namespace App\Models;

use App\Models\teknik\BillOfMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPPBom extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_pp_bom';
    protected $fillable = ['permintaan_pembelian_id', 'bom_id', 'produk_id'];

    public function Bom()
    {
        return $this->belongsTo(BillOfMaterial::class, 'bom_id');
    }
    public function PermintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class, 'aset_id');
    }
    public function DetailPPBomPart()
    {
        return $this->hasMany(DetailPPBomPart::class, 'detail_pp_bom_id', 'id');
    }
}
