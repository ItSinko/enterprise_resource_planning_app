<?php

namespace App\Models\teknik;

<<<<<<< .merge_file_a07216
=======
use App\Models\Produk;
>>>>>>> .merge_file_a11828
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'bill_of_material';
<<<<<<< .merge_file_a07216
    protected $fillable = ['karyawan_id', 'tgl_cek', 'berat', 'lemak', 'kandungan_air', 'otot', 'tulang', 'kalori', 'suhu', 'spo2', 'pr', 'sistolik', 'diastolik', 'keterangan'];
=======

    protected $fillable = ['produk_id', 'kode', 'nama', 'keterangan'];

    function detail_bom()
    {
        return $this->hasMany(DetailBillOfMaterial::class);
    }
    function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
>>>>>>> .merge_file_a11828
}
