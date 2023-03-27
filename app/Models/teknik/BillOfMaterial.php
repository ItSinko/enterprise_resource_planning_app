<?php

namespace App\Models\teknik;

use App\Models\DetailPPBom;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'bill_of_material';

    protected $fillable = ['produk_id', 'kode', 'nama', 'keterangan'];

    function detail_bom()
    {
        return $this->hasMany(DetailBillOfMaterial::class);
    }
    function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function DetailPermintaanPembelian()
    {
        return $this->hasMany(DetailPermintaanPembelian::class);
    }
    public function DetailPPBom()
    {
        return $this->hasMany(DetailPPBom::class);
    }
}
