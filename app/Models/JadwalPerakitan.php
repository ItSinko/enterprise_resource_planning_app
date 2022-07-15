<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPerakitan extends Model
{
    protected $table = 'jadwal_perakitan';
    protected $fillable = ['produk_id','no_bppb', 'jumlah', 'tanggal_mulai', 'tanggal_selesai', 'status', 'state', 'konfirmasi', 'warna', 'status_tf', 'created_at', 'keterangan', 'keterangan_transfer'];

    public function Produk()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'produk_id');
    }

    function noseri()
    {
        return $this->hasMany(NoseriBarangJadi::class, 'jadwal_id');
    }

    function log()
    {
        return $this->hasMany(JadwalPerakitanLog::class, 'jadwal_perakitan_id');
    }

    function getTotalRakit()
    {
        $id = $this->id;
        $s = JadwalPerakitan::where('id', $id)->get();
        $jumlah = 0;
        foreach ($s as $i) {
            $jumlah++;
        }
        return $jumlah;
    }

    function cekTotalRakit()
    {
        $id = $this->id;
        $jumlah = NoseriRakit::whereHas('serii', function($q) use($id) {
            $q->where('jadwal_id', $id);
        })->count();
        return $jumlah;
    }

    function cekTotalKirim()
    {
        $id = $this->id;
        $jumlah = NoseriRakit::whereHas('serii', function($q) use($id) {
            $q->where('jadwal_id', $id);
        })->where('status', 14)->count();
        return $jumlah;
    }
}
