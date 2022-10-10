<?php

namespace App\Models\kesehatan;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $connection = 'kesehatan';
    protected $table = 'obats';
    protected $fillable = ['nama', 'stok', 'keterangan', 'aturan'];

    public function karyawan_sakit()
    {
        return $this->hasMany(Karyawan_sakit::class);
    }
    public function karyawan_masuk()
    {
        return $this->hasMany(Karyawan_masuk::class);
    }
    public function detail_obat()
    {
        return $this->hasMany(Detail_obat::class);
    }
}
