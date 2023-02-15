<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoPembelian extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'po_pembelian';
    protected $fillable = ['permintaan_pembelian_id', 'no_po', 'tgl_po', 'tgl_datang', 'ekspedisi_id', 'supplier_id', 'kurs_id', 'divisi_id', 'status_id'];

    function PermintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class, 'permintaan_pembelian_id');
    }
    public function DetailPoPembelian()
    {
        return $this->hasMany(DetailPoPembelian::class);
    }

    public function Ekspedisi()
    {
        return $this->belongsTo(Ekspedisi::class, 'ekspedisi_id');
    }
    public function Supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function Divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }
    public function Status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function DetailPoAset()
    {
        return $this->hasMany(DetailPoPembelianAset::class);
    }
    public function DetailPoPart()
    {
        return $this->hasMany(DetailPoPembelianPart::class);
    }
}
