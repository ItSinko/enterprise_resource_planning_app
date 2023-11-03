<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPerakitanRw extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'jadwal_perakitan_rw';
    protected $fillable = ['no_permintaan','produk_id','no_bppb', 'jumlah', 'tanggal_mulai', 'tanggal_selesai', 'status', 'state', 'konfirmasi', 'warna', 'status_tf', 'created_at', 'keterangan', 'keterangan_transfer', 'evaluasi','urutan','produk_reworks_id'];

    public function ProdukRw()
    {
        return $this->belongsTo(Produk::class, 'produk_reworks_id');
    }
    public function Produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
