<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriLog extends Model
{
    use HasFactory;

    protected $table = 'tbl_noseri_log';
    protected $fillable = ['gbj_id', 'noseri_id', 'noseri', 'dpp_id', 'nonso', 'remark', 'perbaikan', 'log_id', 'created_by'];

    function gudang()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gbj_id');
    }

    function noserii()
    {
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_id');
    }

    function dpp()
    {
        return $this->belongsTo(DetailPesananProduk::class, 'dpp_id');
    }

    function non_so()
    {
        return $this->belongsTo(TFProduksi::class, 'nonso');
    }

    function logs()
    {
        return $this->belongsTo(Divisi::class, 'log_id');
    }

    function createdd()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
