<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class GudangKarantinaNoseri extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 't_gk_noseri';
    protected static $logName = 'Gudang Karantina';
    protected $fillable = ['gk_detail_id', 'noseri', 'remark', 'tk_kerusakan', 'is_draft', 'is_keluar', 'is_ready', 'out_noseri', 'created_by', 'updated_by', 'perbaikan'];
    protected static $logAttributes = ['noseri', 'remark', 'perbaikan'];
    function detail() {
        return $this->belongsTo(GudangKarantinaDetail::class, 'gk_detail_id');
    }

    function layout() {
        return $this->belongsTo(Layout::class, 'layout_id');
    }


}
