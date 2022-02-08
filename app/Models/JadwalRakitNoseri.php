<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class JadwalRakitNoseri extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "jadwal_rakit_noseri";
    protected static $logName = 'Produksi';
    protected $fillable = ['jadwal_id', 'noseri', 'status', 'date_in', 'waktu_tf', 'created_at', 'updated_at', 'no_bppb'];
    protected static $logAttributes = ['noseri', 'header.produk.produk.nama', 'status', 'date_in', 'waktu_tf'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Noseri Perakitan {$eventName}";
    }

    function header()
    {
        return $this->belongsTo(JadwalPerakitan::class, 'jadwal_id');
    }
}
