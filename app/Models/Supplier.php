<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'supplier';
    protected $fillable = ['kode', 'nama', 'kurs', 'email', 'telepon', 'jenis'];

    public function PermintaanPembelian()
    {
        return $this->hasMany(PermintaanPembelian::class);
    }
}
