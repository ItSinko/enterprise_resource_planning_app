<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenerimaanBarang extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_penerimaan_gdg';
    protected $fillable = ['penerimaan_barang_id', 'm_sparepart_id', 'jumlah', 'layout_id', 'jumlah_diterima', 'jumlah_set'];


    public function PenerimaanBarang()
    {
        return $this->belongsTo(PenerimaanBarang::class, 'penerimaan_barang_id');
    }

    public function Sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'm_sparepart_id');
    }
}
