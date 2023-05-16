<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokDivisiPart extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'stok_divisi_part';
    protected $fillable = ['part_id', 'divisi_id'];
    public $timestamps = false;

    public function Divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id', 'id');
    }

    public function Sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'part_id', 'id');
    }

    public function DetailStokDivisiPart()
    {
        return $this->hasMany(DetailStokDivisiPart::class);
    }

    public function Status($divisi, $part)
    {
        $id = $this->id;
        $data = StokDivisiPart::where(['part_id' => $part, 'divisi_id' => $divisi])->count();

        return $data > 0 ? 'ada' : 'kosong';
    }
    public function getStokDivisiId($divisi, $part)
    {
        $data = StokDivisiPart::where(['part_id' => $part, 'divisi_id' => $divisi])->first();
        return $data ? $data->id : 0;
    }
    public function getId($divisi, $part, $lot)
    {
        $data = DetailStokDivisiPart::whereHas('StokDivisiPart', function ($q) use ($divisi, $part) {
            $q->where(['part_id' => $part, 'divisi_id' => $divisi]);
        })->where(['lot_id' => $lot])->first();

        return $data ? $data->id : 0;
    }
}
