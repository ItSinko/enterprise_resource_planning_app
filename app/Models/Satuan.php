<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'm_satuan';
    protected $fillable = ['nama'];
}
