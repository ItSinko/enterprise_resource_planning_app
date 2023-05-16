<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\kesehatan\Karyawan;

class KaryawanPerbaikan extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'karyawan_perbaikan';
    protected $fillable = ['karyawan_id', 'perbaikan_id'];

    public function Perbaikan(){
        return $this->belongsTo(Perbaikan::class, 'perbaikan_id');
    }

    public function Karyawan(){
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
