<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'aset';

    public function DaftarPerkiraan()
    {
        return $this->belongsTo(DaftarPerkiraan::class, 'daftar_perkiraan_id');
    }
    public function DetailPermintaanPembelian()
    {
        return $this->hasMany(DetailPermintaanPembelian::class);
    }
}
