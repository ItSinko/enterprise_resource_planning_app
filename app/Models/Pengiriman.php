<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\kesehatan\Karyawan;

class Pengiriman extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'pengiriman';
    protected $fillable = ['retur_penjualan_id', 'no_pengiriman', 'tanggal', 'customer_id', 'nama_penerima', 'alamat_penerima', 'telepon_penerima', 'ekspedisi_id', 'pengirim', 'biaya_kirim', 'resi', 'm_state_id', 'created_by'];

    public function Customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function Karyawan(){
        return $this->belongsTo(Karyawan::class, 'created_by');
    }

    public function State(){
        return $this->belongsTo(State::class, 'm_state_id');
    }

    public function Ekspedisi(){
        return $this->belongsTo(Ekspedisi::class, 'ekspedisi_id');
    }

    public function ReturPenjualan(){
        return $this->belongsTo(ReturPenjualan::class, 'retur_penjualan_id');
    }

    public function PengirimanNoseri(){
        return $this->hasMany(PengirimanNoseri::class, 'pengiriman_id');
    }

    public function PengirimanPart(){
        return $this->hasMany(PengirimanPart::class, 'pengiriman_id');
    }
}
