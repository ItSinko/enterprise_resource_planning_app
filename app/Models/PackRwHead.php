<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackRwHead extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'pack_rw_head';
    protected $fillable = ['jadwal_perakitan_rw_id','jumlah','prov','kota'];
}
