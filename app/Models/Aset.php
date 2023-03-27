<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'aset';
    protected $fillable = ['daftar_perkiraan_id', 'nama', 'merk', 'deskripsi', 'kode'];


    public function DaftarPerkiraan()
    {
        return $this->belongsTo(DaftarPerkiraan::class, 'daftar_perkiraan_id');
    }
    public function DetailPermintaanPembelian()
    {
        return $this->hasMany(DetailPermintaanPembelian::class);
    }
    public function DetailPPAset()
    {
        return $this->hasMany(DetailPPAset::class);
    }
}
