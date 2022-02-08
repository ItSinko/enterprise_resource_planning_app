<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class JadwalPerakitan extends Model
{
    use LogsActivity;

    protected $table = 'jadwal_perakitan';
    protected $fillable = ['produk_id', 'jumlah', 'tanggal_mulai', 'tanggal_selesai', 'status', 'state', 'konfirmasi', 'warna', 'status_tf', 'created_at', 'keterangan', 'keterangan_transfer', 'no_bppb'];
    protected static $logName = 'Produksi';
    protected static $logAttributes = ['Produk.produk.nama', 'keterangan', 'keterangan_transfer', 'status_tf', 'no_bppb'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Data Perakitan {$eventName}";
    }

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
