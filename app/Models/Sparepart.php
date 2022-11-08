<?php

namespace App\Models;

use App\Models\teknik\DetailBillOfMaterial;
use App\Models\teknik\JenisPart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'm_sparepart';

    function kategori()
    {
        return $this->belongsTo(KelompokProduk::class, 'kelompok_produk_id');
    }
    function jenis_part()
    {
        return $this->belongsTo(JenisPart::class, 'jenis_part_id');
    }

    public function DetailPesananPart()
    {
        return $this->hasMany(DetailPesananPart::class, 'm_sparepart_id');
    }
    public function detail_bom()
    {
        return $this->hasMany(DetailBillOfMaterial::class);
    }
    function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
