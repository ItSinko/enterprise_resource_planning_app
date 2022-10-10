<?php

namespace App\Models\kesehatan;

use App\Models\kesehatan\Detail_obat;
use App\Models\kesehatan\Karyawan;
use App\Models\kesehatan\Obat;
use Illuminate\Database\Eloquent\Model;

class Karyawan_sakit extends Model
{
    //
    protected $table = 'karyawan_sakits';
    protected $connection = 'kesehatan';
    protected $fillable = ['tgl_cek', 'karyawan_id', 'pemeriksa_id', 'analisa', 'diagnosa', 'tindakan', 'terapi', 'obat_id', 'jumlah', 'aturan', 'konsumsi', 'keputusan'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function pemeriksa()
    {
        return $this->belongsTo(Karyawan::class, 'pemeriksa_id');
    }
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    public function detail_obat()
    {
        return $this->hasMany(Detail_obat::class);
    }

    public function karyawan_masuk()
    {
        return $this->hasOne(Karyawan_masuk::class, 'karyawan_sakit_id');
    }
}
