<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $connection = 'erp';
    protected $table = 'provinsi';

    public function Customer()
    {
        return $this->hasMany(Customer::class);
    }
    public function Ekatalog()
    {
        return $this->hasMany(Ekatalog::class);
    }
    public function Ekspedisi()
    {
        return $this->belongsToMany(Ekspedisi::class, 'ekspedisi_provinsi');
    }
}
