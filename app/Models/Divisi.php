<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\DivisiInventory;
use App\Models\kesehatan\Karyawan;

class Divisi extends Model
{
    protected $connection = 'erp';
    protected $table = "divisi";
    protected $fillable = ['nama', 'kode'];


    public function Karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
    public function StokDivisiPart()
    {
        return $this->hasMany(StokDivisiPart::class, 'divisi_id', 'id');
    }
}
