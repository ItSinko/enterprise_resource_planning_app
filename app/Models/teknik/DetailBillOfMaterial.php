<?php

namespace App\Models\teknik;

use App\Models\DetailPermintaanPembelian;
use App\Models\Satuan;
use App\Models\Sparepart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBillOfMaterial extends Model
{

    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_bill_of_material';

    protected $fillable = ['bill_of_material_id', 'part_id', 'jumlah', 'satuan_id'];

    function bom()
    {
        return $this->belongsTo(BillOfMaterial::class, 'bill_of_material_id');
    }
    function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'part_id');
    }
    function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
