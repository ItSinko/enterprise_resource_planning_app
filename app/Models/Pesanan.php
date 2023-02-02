<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $connection = 'erp';
    protected $table = 'pesanan';
    protected $fillable = ['no_po', 'so', 'tgl_po', 'no_do', 'tgl_do', 'ket', 'log_id', 'checked_by', 'status_cek'];

    public function Ekatalog()
    {
        return $this->hasOne(Ekatalog::class);
    }
    public function Spa()
    {
        return $this->hasOne(Spa::class);
    }
    public function Spb()
    {
        return $this->hasOne(Spb::class);
    }
    public function DetailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
    public function DetailPesananDsb()
    {
        return $this->hasMany(DetailPesananDsb::class);
    }

    public function DetailPesananPart()
    {
        return $this->hasMany(DetailPesananPart::class);
    }
    public function DetailPesananPartJasa()
    {
        $id = $this->id;
        $s = DetailPesananPart::whereHas('Sparepart', function ($q) {
            $q->where('kode', 'like', '%Jasa%');
        })->where('pesanan_id', $id)->get();
        return $s;
    }

    public function DetailPesananPartNonJasa()
    {
        $id = $this->id;
        $s = DetailPesananPart::whereHas('Sparepart', function ($q) {
            $q->where('kode', 'not like', '%Jasa%');
        })->where('pesanan_id', $id)->get();
        return $s;
    }

    public function DetailLogistikPart()
    {
        return $this->hasMany(DetailLogistikPart::class);
    }

    function TFProduksi()
    {
        return $this->hasOne(TFProduksi::class);
    }

    public function getJumlahProgress()
    {
        $id = $this->id;
        $data = Pesanan::where('id', $id)->addSelect(['count_gudang' => function ($q) {
            $q->selectRaw('count(t_gbj_noseri.id)')
                ->from('t_gbj_noseri')
                ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 't_gbj_detail.detail_pesanan_produk_id')
                ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
        }, 'count_qc_prd' => function ($q) {
            $q->selectRaw('count(noseri_detail_pesanan.id)')
                ->from('noseri_detail_pesanan')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->where('noseri_detail_pesanan.status', 'ok')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
        }, 'count_qc_part' => function ($q) {
            $q->selectRaw('sum(outgoing_pesanan_part.jumlah_ok)')
                ->from('outgoing_pesanan_part')
                ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
        }, 'count_log_prd' => function ($q) {
            $q->selectRaw('count(noseri_logistik.id)')
                ->from('noseri_logistik')
                ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->whereColumn('detail_pesanan_produk.pesanan_id', 'pesanan.id');
        }, 'count_log_part' => function ($q) {
            $q->selectRaw('sum(detail_logistik_part.jumlah)')
                ->from('detail_logistik_part')
                ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                ->leftjoin('m_sparepart')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
        }, 'count_jumlah_prd' => function ($q) {
            $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.produk_id', '=', 'gdg_barang_jadi.produk_id')
                ->whereColumn('detail_penjualan_produk.penjualan_produk_id', 'detail_pesanan.')
                ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                ->limit(1);
        }])->with('PenjualanProduk')->first();

        return $data;
    }

    public function getJumlahPaket()
    {
        $id = $this->id;
        $jumlah = 0;
        $data = DetailPesanan::where('pesanan_id', $id)->get();
        foreach ($data as $i) {
            foreach ($i->PenjualanProduk->Produk as $j) {
                $jumlah = $jumlah + 1;
            }
        }
        return $jumlah;
    }

    public function getJumlahPaketPesanan()
    {
        $id = $this->id;
        $jumlah = 0;
        $data = DetailPesanan::where('pesanan_id', $id)->get();
        foreach ($data as $d) {
            $jumlah += $d->jumlah;
        }
        return $jumlah;
    }


    public function getJumlahProduk()
    {
        $id = $this->id;
        $s = DetailPesanan::where('pesanan_id', $id)->get();
        $jumlah = 0;
        foreach ($s as $i) {
            foreach ($i->PenjualanProduk->Produk as $j) {
                $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
            }
        }
        return $jumlah;
    }

    public function getJumlahPesanan()
    {
        $id = $this->id;
        $s = DetailPesanan::where('pesanan_id', $id)->get();
        $jumlah = 0;
        foreach ($s as $i) {
            foreach ($i->PenjualanProduk->Produk as $j) {
                $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
            }
        }
        return $jumlah;
    }

    public function getJumlahSeri()
    {
        $id = $this->id;
        $jumlah = NoseriTGbj::whereHas('detail.header', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->count();
        return $jumlah;
    }

    public function getJumlahCekSeri()
    {
        $id = $this->id;
        $jumlah = NoseriDetailPesanan::whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->count();
        return $jumlah;
    }

    public function getJumlahCek()
    {
        $id = $this->id;
        $jumlah = NoseriDetailPesanan::where('status', 'ok')->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->count();
        return $jumlah;
    }

    public function getJumlahKirim()
    {
        $id = $this->id;
        $jumlah = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->count();
        return $jumlah;
    }

    function cekJumlahkirim()
    {
        $id = $this->id;
        $jumlah = NoseriTGbj::whereHas('detail.header.pesanan', function ($q) use ($id) {
            $q->where('id', $id)->where('status_id', 2);
        })->count();
        return $jumlah;
    }

    public function getJumlahCoo()
    {
        $id = $this->id;
        $jumlah = NoseriCoo::whereHas('NoseriDetailLogistik.DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->count();
        return $jumlah;
    }

    function getJumlahKirim1()
    {
        $id = $this->id;
        $detail = TFProduksiDetail::where('t_gbj_id', $id)->get();
        $jumlah = 0;
        foreach ($detail as $d) {
            $jumlah += $d->qty;
        }
        return $jumlah;
    }

    public function getJumlahPesananPart()
    {
        $id = $this->id;
        $s = DetailPesananPart::where('pesanan_id', $id)->get();
        $jumlah = 0;
        foreach ($s as $i) {
            $jumlah = $jumlah + $i->jumlah;
        }
        return $jumlah;
    }


    public function getJumlahPesananPartNonJasa()
    {
        $id = $this->id;
        $s = DetailPesananPart::where('pesanan_id', $id)->whereHas('Sparepart', function ($q) {
            $q->where('kode', 'not like', '%JASA%');
        })->get();
        $jumlah = 0;
        foreach ($s as $i) {
            $jumlah = $jumlah + $i->jumlah;
        }
        return $jumlah;
    }

    public function getJumlahCekPart($status)
    {
        $id = $this->id;
        $s = OutgoingPesananPart::whereHas('DetailPesananPart', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->whereHas('DetailPesananPart.Sparepart', function ($q) use ($id) {
            $q->where('kode', 'not like', '%JASA%');
        })->get();
        $jumlah = 0;
        foreach ($s as $i) {
            if ($status == 'ok') {
                $jumlah = $jumlah + $i->jumlah_ok;
            } else if ($status == 'nok') {
                $jumlah = $jumlah + $i->jumlah_nok;
            }
        }
        return $jumlah;
    }


    public function getJumlahPesananJasa()
    {
        $id = $this->id;
        $s = DetailPesananPart::whereHas('Sparepart', function ($q) {
            $q->where('kode', 'like', '%Jasa%');
        })->where('pesanan_id', $id)->count();
        return $s;
    }
    public function getJumlahPesananNonJasa()
    {
        $id = $this->id;
        $s = DetailPesananPart::whereHas('Sparepart', function ($q) {
            $q->where('kode', 'not like', '%Jasa%');
        })->where('pesanan_id', $id)->count();
        return $s;
    }


    public function getSuratJalan()
    {
        $id = $this->id;

        $data = Logistik::select('logistik.nosurat', 'logistik.tgl_kirim')
            ->leftJoin('detail_logistik_part',  'detail_logistik_part.logistik_id',  '=',  'logistik.id')
            ->leftJoin('detail_logistik',  'detail_logistik.logistik_id',  '=',  'logistik.id')
            ->leftJoin('detail_pesanan_produk',  'detail_pesanan_produk.id',  '=',  'detail_logistik.detail_pesanan_produk_id')
            ->leftJoin('detail_pesanan',  'detail_pesanan.id',  '=',  'detail_pesanan_produk.detail_pesanan_id')
            ->leftJoin('pesanan',  'pesanan.id',  '=',  'detail_pesanan.pesanan_id')
            ->leftJoin('detail_pesanan_part',  'detail_pesanan_part.pesanan_id',  '=',  'pesanan.id')
            ->where('pesanan.id', $id)->groupby('logistik.nosurat')->get();

        $value = array();
        foreach ($data as $d) {
            $value[] = $d->nosurat . '(' . $d->tgl_kirim . ')';
        }

        return implode(',', $value);
    }

    public function getJumlahKirimPart()
    {
        $id = $this->id;
        $s = DetailLogistikPart::whereHas('DetailPesananPart', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->get();
        $jumlah = 0;
        foreach ($s as $i) {
            $jumlah = $jumlah + $i->DetailPesananPart->jumlah;
        }
        return $jumlah;
    }

    public function LogistikLaporan($jenis, $eks, $awal, $akhir)
    {
        $id = $this->id;

        $prd = "";
        $part = "";

        if ($jenis == "ekspedisi") {
            if ($eks != '0') {
                $prd = Logistik::where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();

                $part = Logistik::where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
            } else {
                $prd = Logistik::whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();

                $part = Logistik::whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
            }
        } else if ($jenis == "nonekspedisi") {
            $prd = Logistik::whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();

            $part = Logistik::whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
        } else {
            $prd = Logistik::whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();

            $part = Logistik::whereBetween('tgl_kirim', [$awal, $akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
        }
        $data = $prd->merge($part);
        return $data;
    }

    public function AllProdukKirim($jenis, $eks, $awal, $akhir)
    {
        $id = $this->id;

        $prd = "";
        $part = "";

        if ($jenis == "ekspedisi") {
            if ($eks != '0') {
                $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($eks, $awal, $akhir) {
                    $q->where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->count();

                $part = DetailLogistikPart::whereHas('Logistik', function ($q) use ($eks, $awal, $akhir) {
                    $q->where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->whereHas('DetailPesananPart', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->count();
            } else {
                $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                    $q->whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->count();

                $part = DetailLogistikPart::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                    $q->whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->whereHas('DetailPesananPart', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->count();
            }
        } else if ($jenis == "nonekspedisi") {
            $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->count();

            $part = DetailLogistikPart::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->whereHas('DetailPesananPart', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->count();
        } else {
            $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->count();

            $part = DetailLogistikPart::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->whereHas('DetailPesananPart', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->count();
        }
        return $prd + $part;
    }

    public function LaporanQcProduk($produk, $hasil, $tgl_awal, $tgl_akhir)
    {
        $id = $this->id;
        $res = "";
        if ($produk != "0") {
            if ($hasil != "semua") {
                $res = DetailPesanan::where([['penjualan_produk_id', '=', $produk], ['pesanan_id', '=', $id]])->whereHas('DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir, $hasil) {
                    $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
                })->get();
            } else {
                $res = DetailPesanan::where([['penjualan_produk_id', '=', $produk], ['pesanan_id', '=', $id]])->whereHas('DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir) {
                    $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
                })->get();
            }
        } else {
            if ($hasil != "semua") {
                $res = DetailPesanan::where('pesanan_id', $id)->whereHas('DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir, $hasil) {
                    $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil);
                })->get();
            } else {
                $res = DetailPesanan::where('pesanan_id', $id)->whereHas('DetailPesananProduk.NoseriDetailPesanan', function ($q) use ($tgl_awal, $tgl_akhir) {
                    $q->whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir]);
                })->get();
            }
        }
        return $res;
    }

    public function countLaporanQcProduk($produk, $hasil, $tgl_awal, $tgl_akhir)
    {
        $id = $this->id;
        $res = "";
        if ($produk != "0") {
            if ($hasil != "semua") {
                $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil)->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($produk, $id) {
                    $q->where([['penjualan_produk_id', '=', $produk], ['pesanan_id', '=', $id]]);
                })->count();
            } else {
                $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($produk, $id) {
                    $q->where([['penjualan_produk_id', '=', $produk], ['pesanan_id', '=', $id]]);
                })->count();
            }
        } else {
            if ($hasil != "semua") {
                $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('status', $hasil)->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->count();
            } else {
                $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->count();
            }
        }
        return $res;
    }

    public function LaporanQcPart($produk, $tgl_awal, $tgl_akhir)
    {
        $id = $this->id;
        $res = "";
        if ($produk != "0") {
            $res = DetailPesananPart::where([['m_sparepart_id', '=', $produk], ['pesanan_id', '=', $id]])->whereHas('OutgoingPesananPart', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir]);
            })->get();
        } else {
            $res = DetailPesananPart::where('pesanan_id', $id)->whereHas('OutgoingPesananPart', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir]);
            })->get();
        }
        return $res;
    }

    public function countLaporanQcPart($produk, $tgl_awal, $tgl_akhir)
    {
        $id = $this->id;
        $res = "";
        if ($produk != "0") {
            $res = OutgoingPesananPart::whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananPart', function ($q) use ($produk, $id) {
                $q->where([['m_sparepart_id', '=', $produk], ['pesanan_id', '=', $id]]);
            })->count();
        } else {
            $res = OutgoingPesananPart::whereBetween('tanggal_uji', [$tgl_awal, $tgl_akhir])->whereHas('DetailPesananPart', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->count();
        }
        return $res;
    }


    public function getJumlahPaketUnique()
    {
        $id = $this->id;
        $jumlah = 0;
        $data = DetailPesanan::groupby('penjualan_produk_id')->where('pesanan_id', $id)->get();
        foreach ($data as $d) {
            $jumlah++;
        }
        if ($jumlah == 0) {
            return $jumlah = 1;
        } else {
            return  $jumlah;
        }
    }

    public function DetailPesananUnique()
    {
        $id = $this->id;
        $produk = DetailPesanan::groupby('penjualan_produk_id')->where('pesanan_id', $id)->orderBy('pesanan_id', 'DESC')->get();
        $part = DetailPesananPart::groupby('m_sparepart_id')->where('pesanan_id', $id)->orderBy('pesanan_id', 'DESC')->get();
        $data = $produk->merge($part);
        return $data;
    }

    public function DetailPesananUniqueDsb()
    {
        $id = $this->id;
        $data = DetailPesananDsb::groupby('penjualan_produk_id')->where('pesanan_id', $id)->orderBy('pesanan_id', 'DESC')->get();

        return $data;
    }

    public function getJumlahPesananUnique($id_penjualan_produk)
    {
        $id = $this->id;
        $data = DetailPesanan::groupby('penjualan_produk_id')->where(['pesanan_id' => $id, 'penjualan_produk_id' => $id_penjualan_produk])->sum('jumlah');
        return $data;
    }
    public function getJumlahPesananUniqueDsb($id_penjualan_produk)
    {
        $id = $this->id;
        $data = DetailPesananDsb::groupby('penjualan_produk_id')->where(['pesanan_id' => $id, 'penjualan_produk_id' => $id_penjualan_produk])->sum('jumlah');
        return $data;
    }
    public function getJumlahPartUnique($id_penjualan_produk)
    {
        $id = $this->id;
        $data = DetailPesananPart::groupby('m_sparepart_id')->where(['pesanan_id' => $id, 'm_sparepart_id' => $id_penjualan_produk])->sum('jumlah');
        return $data;
    }
    public function getTotalPesananUnique($id_penjualan_produk)
    {
        $id = $this->id;
        $data = DetailPesanan::where(['pesanan_id' => $id, 'penjualan_produk_id' => $id_penjualan_produk])->get();
        $total = 0;
        foreach ($data as $d) {
            $total += $d->harga * $d->jumlah;
        }
        return $total;
    }
    public function getTotalPesananUniqueDsb($id_penjualan_produk)
    {
        $id = $this->id;
        $data = DetailPesananDsb::where(['pesanan_id' => $id, 'penjualan_produk_id' => $id_penjualan_produk])->get();
        $total = 0;
        foreach ($data as $d) {
            $total += $d->harga * $d->jumlah;
        }
        return $total;
    }
    public function getTotalPartUnique($m_sparepart_id)
    {
        $id = $this->id;
        $data = DetailPesananPart::where(['pesanan_id' => $id, 'm_sparepart_id' => $m_sparepart_id])->get();
        $total = 0;
        foreach ($data as $d) {
            $total += $d->harga * $d->jumlah;
        }
        return $total;
    }
    public function getOngkirPesananUnique($id_penjualan_produk)
    {
        $id = $this->id;
        $data = DetailPesanan::groupby('penjualan_produk_id')->where(['pesanan_id' => $id, 'penjualan_produk_id' => $id_penjualan_produk])->sum('ongkir');
        return $data;
    }
    public function getOngkirPesananUniqueDsb($id_penjualan_produk)
    {
        $id = $this->id;
        $data = DetailPesananDsb::groupby('penjualan_produk_id')->where(['pesanan_id' => $id, 'penjualan_produk_id' => $id_penjualan_produk])->sum('ongkir');
        return $data;
    }
    public function getSuratJalanProduk($id_penjualan_produk)
    {
        $id = $this->id;
        $detail_pesanan = DetailPesanan::where(['pesanan_id' => $id, 'penjualan_produk_id' => $id_penjualan_produk])->get();
        $detail_pesanan_id = [];
        $detail_pesanan_produk_id = [];
        foreach ($detail_pesanan as $d) {
            $detail_pesanan_id[] = $d->id;
        }

        $detail_pesanan_produk = DetailPesananProduk::whereIN('detail_pesanan_id', $detail_pesanan_id)->get();
        foreach ($detail_pesanan_produk as $e) {
            $detail_pesanan_produk_id[] = $e->id;
        }

        $logistik = DetailLogistik::groupby('logistik_id')->whereIN('detail_pesanan_produk_id', $detail_pesanan_produk_id)->get();
        return $logistik;
    }
    public function getNoseri($id_penjualan_produk)
    {
        $id = $this->id;
        $detail_pesanan = DetailPesanan::where(['pesanan_id' => $id, 'penjualan_produk_id' => $id_penjualan_produk])->get();
        $detail_pesanan_id = [];
        $detail_pesanan_produk_id = [];
        foreach ($detail_pesanan as $d) {
            $detail_pesanan_id[] = $d->id;
        }

        $detail_pesanan_produk = DetailPesananProduk::whereIN('detail_pesanan_id', $detail_pesanan_id)->get();
        foreach ($detail_pesanan_produk as $e) {
            $detail_pesanan_produk_id[] = $e->id;
        }

        $noseri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $detail_pesanan_produk_id)->get();

        return $noseri;
    }
    function log()
    {
        return $this->belongsTo(State::class, 'log_id');
    }

    public function State()
    {
        return $this->belongsTo(State::class, 'log_id');
    }
}
