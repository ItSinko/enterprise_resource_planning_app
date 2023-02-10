<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPPAset extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_pp_aset';
    protected $fillable = ['permintaan_pembelian_id', 'aset_id', 'jumlah', 'harga', 'estimasi_harga', 'via', 'link'];

    public function Aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }
    public function PermintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class, 'aset_id');
    }
}
