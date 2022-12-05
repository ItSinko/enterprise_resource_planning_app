<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'm_status';

    function status()
    {
        return $this->hasMany(Status::class);
    }
    public function TFProduksiHistory()
    {
        return $this->hasMany(TFProduksiHistory::class, 'status_id', 'id');
    }
}
