<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarPerkiraan extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'daftar_perkiraan';

    public function Aset()
    {
        return $this->hasMany(Aset::class);
    }
}
