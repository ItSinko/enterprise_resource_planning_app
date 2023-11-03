<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriDsb extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'noseri_dsb';
    protected $fillable = ['detail_pesanan_dsb', 'noseri'];


}
