<?php

namespace App\Models\kesehatan;

use App\Models\Divisi;
use App\Models\HasilNotulen;
use App\Models\PesertaMeeting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{

    protected $table = 'karyawans';
    protected $connection = 'kesehatan';
    protected $fillable = ['nama', 'kode_karyawan', 'divisi_id', 'jabatan', 'foto', 'tgllahir', 'tgl_kerja', 'kelamin', 'is_aktif'];


    public function User()
    {
        return $this->hasOne(User::class, 'divisi_id');
    }
    public function Divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }
    public function Kesehatan_awal()
    {
        return $this->hasOne(Kesehatan_awal::class);
    }
    public function Berat_karyawan()
    {
        return $this->hasMany(Berat_karyawan::class);
    }
    public function Vaksin_karyawan()
    {
        return $this->hasMany(Vaksin_karyawan::class);
    }
    public function PesertaMeeting()
    {
        return $this->hasMany(PesertaMeeting::class,'karyawan_id');
    }
    public function Riwayat_penyakit()
    {
        return $this->hasMany(Riwayat_penyakit::class);
    }
    public function Kesehatan_mingguan_rapid()
    {
        return $this->hasMany(Kesehatan_mingguan_rapid::class);
    }
    public function gcu_karyawan()
    {
        return $this->hasMany(Gcu_karyawan::class);
    }
    public function kesehatan_tahunan()
    {
        return $this->hasMany(Kesehatan_tahunan::class);
    }
    public function karyawan_sakit()
    {
        return $this->hasMany(Karyawan_sakit::class);
    }
}
