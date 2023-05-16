<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriPerbaikan extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'noseri_perbaikan';
    protected $fillable = ['noseri_barang_jadi_id', 'perbaikan_id', 'tindak_lanjut', 'm_status_id', 'noseri_pengganti_id'];

    public function NoseriBarangJadi(){
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_barang_jadi_id');
    }

    public function Perbaikan(){
        return $this->belongsTo(Perbaikan::class, 'perbaikan_id');
    }

    public function Status(){
        return $this->belongsTo(Status::class, 'm_status_id');
    }

    public function NoseriPengganti(){
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_pengganti_id');
    }
}
