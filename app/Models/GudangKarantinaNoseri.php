<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangKarantinaNoseri extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 't_gk_noseri';

    protected $fillable = ['gk_detail_id', 'noseri', 'remark', 'tk_kerusakan', 'is_draft', 'is_keluar', 'is_ready', 'out_noseri', 'created_by', 'updated_by', 'perbaikan', 'noseri_fix_id', 'hasil_jadi_id'];

    function detail() {
        return $this->belongsTo(GudangKarantinaDetail::class, 'gk_detail_id');
    }

    function layout() {
        return $this->belongsTo(Layout::class, 'layout_id');
    }

    function unit()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'hasil_jadi_id');
    }

    function noseri_new()
    {
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_fix_id');
    }


}
