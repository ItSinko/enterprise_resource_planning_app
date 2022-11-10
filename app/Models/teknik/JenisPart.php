<?php

namespace App\Models\teknik;

use App\Models\Sparepart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPart extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'jenis_part';

    protected $fillable = ['kode', 'nama'];

    function sparepart()
    {
        return $this->hasMany(Sparepart::class);
    }
}
