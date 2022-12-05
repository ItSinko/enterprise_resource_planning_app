<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TFProduksiHistory extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'history_t_gbj';

    protected $fillable = ['t_gbj_id', 'divisi_id', 'status_id'];

    function header()
    {
        return $this->belongsTo(TFProduksi::class, 't_gbj_id');
    }
    function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
