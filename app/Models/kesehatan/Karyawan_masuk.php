<?php
namespace App\Models\kesehatan;

use Illuminate\Database\Eloquent\Model;

class Karyawan_masuk extends Model
{
    protected $connection = 'kesehatan';
    protected $tables = "karyawan_masuks";
    protected $fillable = ['karyawan_id', 'pemeriksa_id', 'karyawan_sakit_id', 'tgl_cek', 'alasan', 'keterangan'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
    public function karyawan_sakit()
    {
        return $this->belongsTo(Karyawan_sakit::class, 'karyawan_sakit_id');
    }
    public function pemeriksa()
    {
        return $this->belongsTo(Karyawan::class, 'pemeriksa_id');
    }
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
