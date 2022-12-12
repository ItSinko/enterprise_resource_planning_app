<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'penerimaan_gdg';
    protected $fillable = ['pembelian_id', 'lot_id', 'no_lpb', 'tgl_kedatangan', 'ket'];

    public function DetailPenerimaanBarang()
    {
        return $this->hasMany(DetailPenerimaanBarang::class);
    }

    public function LotNumber()
    {
        return $this->belongsTo(LotNumber::class, 'lot_id', 'id');
    }
}
