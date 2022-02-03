<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPerakitan extends Model
{
    protected $table = 'jadwal_perakitan';
    protected $fillable = ['produk_id', 'jumlah', 'tanggal_mulai', 'tanggal_selesai', 'status', 'state', 'konfirmasi', 'warna', 'status_tf', 'created_at', 'keterangan', 'keterangan_transfer'];

    public function Produk()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'produk_id');
    }

    function noseri()
    {
        return $this->hasMany(JadwalRakitNoseri::class, 'jadwal_id');
    }

    function log()
    {
        return $this->hasMany(JadwalPerakitanLog::class, 'jadwal_perakitan_id');
    }
}
