<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// models
use App\Models\Produk;

class JadwalPerakitan extends Model
{
    protected $table = 'jadwal_perakitan';
    protected $fillable = ['produk_id', 'jumlah', 'tanggal_mulai', 'tanggal_selesai', 'status', 'state', 'konfirmasi', 'warna'];

    public function Produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
