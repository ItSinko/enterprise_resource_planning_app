<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'perbaikan';
    protected $fillable = ['no_perbaikan', 'tanggal', 'retur_id', 'keterangan', 'm_state_id', 'gdg_barang_jadi_id'];

    public function ReturPenjualan(){
        return $this->belongsTo(ReturPenjualan::class, 'retur_id');
    }

    public function State(){
        return $this->belongsTo(State::class, 'm_state_id');
    }

    public function KaryawanPerbaikan(){
        return $this->hasMany(KaryawanPerbaikan::class, 'perbaikan_id');
    }

    public function NoseriPerbaikan(){
        return $this->hasMany(NoseriPerbaikan::class, 'perbaikan_id');
    }

    public function PartPenggantiPerbaikan(){
        return $this->hasMany(PartPenggantiPerbaikan::class, 'perbaikan_id');
    }

    public function GudangBarangJadi()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gdg_barang_jadi_id');
    }
}
