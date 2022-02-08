<?php

namespace App\Http\Controllers;

use App\Models\DetailEkatalog;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\GudangBarangJadiHis;
use App\Models\JadwalPerakitan;
use App\Models\JadwalPerakitanLog;
use App\Models\JadwalRakitNoseri;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriDetailLogistik;
use App\Models\NoseriTGbj;
use App\Models\PenjualanProduk;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use App\Models\TFProduksiHis;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProduksiController extends Controller
{
    function CreateTFItem(Request $request)
    {
        foreach ($request->gdg_brg_jadi_id as $key => $value) {
            $tf_prod = new TFProduksi();
            $tf_prod->tgl_keluar = Carbon::now();
            $tf_prod->ke = $request->ke[$key];
            $tf_prod->deskripsi = $request->deskripsi[$key];
            $tf_prod->jenis = 'keluar';
            $tf_prod->created_at = Carbon::now();
            $tf_prod->created_by = $request->userid;
            $tf_prod->save();

            $tf_prod_det = new TFProduksiDetail();
            $tf_prod_det->t_gbj_id = $tf_prod->id;
            $tf_prod_det->gdg_brg_jadi_id = $value;
            $tf_prod_det->qty = $request->qty[$key];
            $tf_prod_det->jenis = 'keluar';
            $tf_prod_det->created_at = Carbon::now();
            $tf_prod_det->created_by = $request->userid;
            $tf_prod_det->save();

            $did = $tf_prod_det->id;
            $checked = $request->noseri_id;

            foreach ($request->noseri_id[$value] as $k => $v) {
                if (in_array($request->noseri_id[$value], $checked)) {
                    $nn = new NoseriTGbj();
                    $nn->t_gbj_detail_id = $did;
                    $nn->noseri_id = $v;
                    $nn->layout_id = 1;
                    $nn->status_id = 2;
                    $nn->jenis = 'keluar';
                    $nn->created_at = Carbon::now();
                    $nn->created_by = $request->userid;
                    $nn->save();

                    NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->ke[$key]]);
                }
            }
            GudangBarangJadiHis::create([
                'gdg_brg_jadi_id' => $value,
                'stok' => $request->qty[$key],
                'tgl_masuk' => Carbon::now(),
                'jenis' => 'KELUAR',
                'created_by' => $request->userid,
                'created_at' => Carbon::now(),
                'ke' => $request->ke[$key],
                'tujuan' => $request->deskripsi[$key],
            ]);
        }

        $gdg = GudangBarangJadi::whereIn('id', $request->gdg_brg_jadi_id)->get()->toArray();
        $i = 0;
        foreach ($gdg as $vv) {
            $vv['stok'] = $vv['stok'] - $request->qty[$i];
            print_r($vv['stok']);

            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);

            $i++;
        }

        activity('GBJ')
            ->causedBy($request->userid)
            ->withProperties(['produk' => [$request->gdg_brg_jadi_id], 'jumlah' => [$request->qty[$i]] ])
            ->log('Transfer Tanpa SO');

        return response()->json(['msg' => 'Successfully']);
    }

    function TfbySO(Request $request)
    {
        $d = new TFProduksi();
        $d->pesanan_id = $request->pesanan_id;
        $d->tgl_keluar = Carbon::now();
        $d->ke = 23;
        $d->jenis = 'keluar';
        $d->status_id = 1;
        $d->state_id = 2;
        $d->created_at = Carbon::now();
        $d->created_by = $request->userid;
        $d->save();

        foreach ($request->data as $key => $value) {
            $dd = new TFProduksiDetail();
            $dd->t_gbj_id = $d->id;
            $dd->gdg_brg_jadi_id = $key;
            $dd->qty = $value['jumlah'];
            $dd->jenis = 'keluar';
            $dd->status_id = 1;
            $dd->state_id = 2;
            $dd->created_at = Carbon::now();
            $dd->created_by = $request->userid;
            $dd->save();

            $did = $dd->id;
            foreach ($value['noseri'] as $k => $v) {
                $nn = new NoseriTGbj();
                $nn->t_gbj_detail_id = $did;
                $nn->noseri_id = $v;
                $nn->status_id = 1;
                $nn->state_id = 2;
                $nn->jenis = 'keluar';
                $nn->created_at = Carbon::now();
                $nn->created_by = $request->userid;
                $nn->save();

                NoseriBarangJadi::find($v)->update(['is_ready' => 1]);
            }
        }

        return response()->json(['msg' => 'Data Tersimpan ke Rancangan']);
    }

    function TfbySOFinal(Request $request)
    {
        $a = TFProduksi::where('pesanan_id', $request->pesanan_id)->first();
        if (isset($a->id)) {

            foreach ($request->data as $key => $value) {
                $b = TFProduksiDetail::where('t_gbj_id', $a->id)->where('gdg_brg_jadi_id', $value['prd'])->where('detail_pesanan_produk_id', $key)->get()->count();
                $c = TFProduksiDetail::where('t_gbj_id', $a->id)->where('gdg_brg_jadi_id', $value['prd'])->where('detail_pesanan_produk_id', $key)->first();
                if ($b > 0) {
                    foreach ($value['noseri'] as $k => $v) {
                        $nn = new NoseriTGbj();
                        $nn->t_gbj_detail_id = $c->id;
                        $nn->noseri_id = $v;
                        $nn->status_id = 2;
                        $nn->state_id = 8;
                        $nn->jenis = 'keluar';
                        $nn->created_at = Carbon::now();
                        $nn->created_by = $request->userid;
                        $nn->save();

                        NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                    }

                    $gdg = GudangBarangJadi::whereIn('id', [$value['prd']])->get()->toArray();
                    $i = 0;
                    foreach ($gdg as $vv) {
                        $vv['stok'] = $vv['stok'] - $value['jumlah'];
                        // print_r($vv['stok']);
                        $i++;
                        GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                        GudangBarangJadiHis::create([
                            'gdg_brg_jadi_id' => $vv['id'],
                            'stok' => $value['jumlah'],
                            'tgl_masuk' => Carbon::now(),
                            'jenis' => 'KELUAR',
                            'created_by' => $request->userid,
                            'created_at' => Carbon::now(),
                            'ke' => 23,
                            'tujuan' => $request->deskripsi,
                        ]);
                    }
                } else {
                    foreach ($request->data as $key => $value) {
                        $dd = new TFProduksiDetail();
                        $dd->t_gbj_id = $a->id;
                        $dd->detail_pesanan_produk_id = $key;
                        $dd->gdg_brg_jadi_id = $value['prd'];
                        $dd->qty = $value['jumlah'];
                        $dd->jenis = 'keluar';
                        $dd->status_id = 2;
                        $dd->state_id = 8;
                        $dd->created_at = Carbon::now();
                        $dd->created_by = $request->userid;
                        $dd->save();

                        $did = $dd->id;
                        $checked = $request->noseri_id;
                        foreach ($value['noseri'] as $k => $v) {
                            $nn = new NoseriTGbj();
                            $nn->t_gbj_detail_id = $did;
                            $nn->noseri_id = $v;
                            $nn->status_id = 2;
                            $nn->state_id = 8;
                            $nn->jenis = 'keluar';
                            $nn->created_at = Carbon::now();
                            $nn->created_by = $request->userid;
                            $nn->save();

                            NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                        }

                        $gdg = GudangBarangJadi::whereIn('id', [$value['prd']])->get()->toArray();
                        $i = 0;
                        foreach ($gdg as $vv) {
                            $vv['stok'] = $vv['stok'] - $value['jumlah'];
                            // print_r($vv['stok']);
                            $i++;
                            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                            GudangBarangJadiHis::create([
                                'gdg_brg_jadi_id' => $vv['id'],
                                'stok' => $value['jumlah'],
                                'tgl_masuk' => Carbon::now(),
                                'jenis' => 'KELUAR',
                                'created_by' => $request->userid,
                                'created_at' => Carbon::now(),
                                'ke' => 23,
                                'tujuan' => $request->deskripsi,
                            ]);
                        }
                    }
                }
            }
        } else {
            $d = new TFProduksi();
            $d->pesanan_id = $request->pesanan_id;
            $d->tgl_keluar = Carbon::now();
            $d->ke = 23;
            $d->jenis = 'keluar';
            $d->status_id = 2;
            $d->state_id = 8;
            $d->created_at = Carbon::now();
            $d->created_by = $request->userid;
            $d->save();

            foreach ($request->data as $key => $value) {
                $dd = new TFProduksiDetail();
                $dd->t_gbj_id = $d->id;
                $dd->detail_pesanan_produk_id = $key;
                $dd->gdg_brg_jadi_id = $value['prd'];
                $dd->qty = $value['jumlah'];
                $dd->jenis = 'keluar';
                $dd->status_id = 2;
                $dd->state_id = 8;
                $dd->created_at = Carbon::now();
                $dd->created_by = $request->userid;
                $dd->save();

                $did = $dd->id;
                $checked = $request->noseri_id;
                foreach ($value['noseri'] as $k => $v) {
                    $nn = new NoseriTGbj();
                    $nn->t_gbj_detail_id = $did;
                    $nn->noseri_id = $v;
                    $nn->status_id = 2;
                    $nn->state_id = 8;
                    $nn->jenis = 'keluar';
                    $nn->created_at = Carbon::now();
                    $nn->created_by = $request->userid;
                    $nn->save();

                    NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                }

                $gdg = GudangBarangJadi::whereIn('id', [$key])->get()->toArray();
                $i = 0;
                foreach ($gdg as $vv) {
                    $vv['stok'] = $vv['stok'] - $value['jumlah'];
                    // print_r($vv['stok']);
                    $i++;
                    GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                    GudangBarangJadiHis::create([
                        'gdg_brg_jadi_id' => $vv['id'],
                        'stok' => $value['jumlah'],
                        'tgl_masuk' => Carbon::now(),
                        'jenis' => 'KELUAR',
                        'created_by' => $request->userid,
                        'created_at' => Carbon::now(),
                        'ke' => 23,
                        'tujuan' => $request->deskripsi,
                    ]);
                }
            }
        }

        $s = DetailPesanan::where('pesanan_id', $request->pesanan_id)->get();
        $jumlah = 0;
        $x = 0;
        foreach ($s as $i) {
            foreach ($i->PenjualanProduk->Produk as $j) {
                $x = $jumlah + ($i->jumlah * $j->pivot->jumlah);
            }
        }

        $jumlah_kirim = NoseriTGbj::whereHas('detail.header', function ($q) use ($request) {
            $q->where('pesanan_id', $request->pesanan_id);
        })->where('status_id', 2)->get()->count();

        if ($x == $jumlah_kirim) {
            Pesanan::find($request->pesanan_id)->update(['log_id' => 8]);
        } else {
            Pesanan::find($request->pesanan_id)->update(['log_id' => 6]);
        }

        return response()->json(['msg' => 'Data Terkirim ke QC']);
    }

    function updateRancangSO(Request $request)
    {
        $header = TFProduksi::select('id')->where('pesanan_id', $request->pesanan_id)->first();

        $ddid = TFProduksiDetail::where('t_gbj_id', $header->id)->get()->pluck('id');
        $noserid = NoseriTGbj::whereIn('t_gbj_detail_id', $ddid)->get()->pluck('id');

        NoseriTGbj::whereIn('id', $noserid)->delete();
        TFProduksiDetail::where('t_gbj_id', $header->id)->delete();
        foreach ($request->data as $key => $value) {
            $dd = new TFProduksiDetail();
            $dd->t_gbj_id = $header->id;
            $dd->gdg_brg_jadi_id = $key;
            $dd->qty = $value['jumlah'];
            $dd->jenis = 'keluar';
            $dd->status_id = 1;
            $dd->state_id = 2;
            $dd->created_at = Carbon::now();
            $dd->created_by = $request->userid;
            $dd->save();

            $did = $dd->id;
            $checked = $request->noseri_id;
            foreach ($value['noseri'] as $k => $v) {
                $nn = new NoseriTGbj();
                $nn->t_gbj_detail_id = $did;
                $nn->noseri_id = $v;
                $nn->status_id = 1;
                $nn->state_id = 2;
                $nn->jenis = 'keluar';
                $nn->created_at = Carbon::now();
                $nn->created_by = $request->userid;
                $nn->save();

                NoseriBarangJadi::find($v)->update(['is_ready' => 1]);
            }
        }

        return response()->json(['msg' => 'Rancangan berhasil diubah']);
    }

    function updateFinalSO(Request $request)
    {
        $header = TFProduksi::select('id')->where('pesanan_id', $request->pesanan_id)->first();
        $header->status_id = 2;
        $header->state_id = 8;
        $header->created_by = $request->userid;
        $header->save();

        $ddid = TFProduksiDetail::where('t_gbj_id', $header->id)->get()->pluck('id');
        $noserid = NoseriTGbj::whereIn('t_gbj_detail_id', $ddid)->get()->pluck('id');

        NoseriTGbj::whereIn('id', $noserid)->delete();
        TFProduksiDetail::where('t_gbj_id', $header->id)->delete();
        foreach ($request->data as $key => $value) {
            $dd = new TFProduksiDetail();
            $dd->t_gbj_id = $header->id;
            $dd->gdg_brg_jadi_id = $key;
            $dd->qty = $value['jumlah'];
            $dd->jenis = 'keluar';
            $dd->status_id = 2;
            $dd->state_id = 8;
            $dd->created_at = Carbon::now();
            $dd->created_by = $request->userid;
            $dd->save();

            $did = $dd->id;
            $checked = $request->noseri_id;
            foreach ($value['noseri'] as $k => $v) {
                $nn = new NoseriTGbj();
                $nn->t_gbj_detail_id = $did;
                $nn->noseri_id = $v;
                $nn->status_id = 2;
                $nn->state_id = 8;
                $nn->jenis = 'keluar';
                $nn->created_at = Carbon::now();
                $nn->created_by = $request->userid;
                $nn->save();

                NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
            }

            $gdg = GudangBarangJadi::whereIn('id', [$key])->get()->toArray();
            $i = 0;
            foreach ($gdg as $vv) {
                $vv['stok'] = $vv['stok'] - $value['jumlah'];
                print_r($vv['stok']);
                $i++;
                GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                GudangBarangJadiHis::create([
                    'gdg_brg_jadi_id' => $vv['id'],
                    'stok' => $value['jumlah'],
                    'tgl_masuk' => Carbon::now(),
                    'jenis' => 'KELUAR',
                    'created_by' => $request->userid,
                    'created_at' => Carbon::now(),
                    'ke' => 23,
                    'tujuan' => $request->deskripsi,
                ]);
            }
        }

        return response()->json(['msg' => 'Rancangan berhasil ditransfer']);
    }
    // get
    function getNoseri(Request $request, $id)
    {
        $data = NoseriBarangJadi::where('gdg_barang_jadi_id', $id)->where('is_ready', 0)->get();
        $i = 0;
        return datatables()->of($data)
            ->addColumn('checkbox', function ($data) use ($i) {
                $i++;
                return '<input type="checkbox" class="cb-child" name="noseri_id[][' . $i . ']" id="" value="' . $data->id . '">';
            })
            ->addColumn('noseri', function ($data) {
                return $data->noseri;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    function getSOCek()
    {
        $Ekatalog = collect(Pesanan::has('Ekatalog')->whereNotIn('log_id', [7, 10])->get());
        $Spa = collect(Pesanan::has('Spa')->whereNotIn('log_id', [7, 10])->Has('DetailPesanan')->get());
        $Spb = collect(Pesanan::has('Spb')->whereNotIn('log_id', [7, 10])->Has('DetailPesanan')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('po', function ($data) {
                return $data->no_po;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                for ($i = 1; $i < count($name); $i++) {
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->Spb->Customer->nama;
                    }
                }
            })
            ->addColumn('batas_out', function ($d) {
                if (isset($d->Ekatalog->tgl_kontrak)) {
                    if ($d->Ekatalog->Provinsi->status == 1) {
                        return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(5)->isoFormat('D MMMM YYYY');
                    }

                    if ($d->Ekatalog->Provinsi->status == 2) {
                        return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(4)->isoFormat('D MMMM YYYY');
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('status_prd', function ($data) {
                if ($data->log_id) {
                    return '<span class="badge badge-warning">' . $data->log->nama . '</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('status1', function ($data) {
                $sumcek = DB::table('view_cek_produkso')->select('*', DB::raw('count(status_cek) as jml'), DB::raw('count(gbjid) as jml_prd'))->groupBy('pesananid')->where('pesananid', $data->id)->get()->pluck('jml');
                $sumprd = DB::table('view_cek_produkso')->select('*', DB::raw('count(status_cek) as jml'), DB::raw('count(gbjid) as jml_prd'))->groupBy('pesananid')->where('pesananid', $data->id)->get()->pluck('jml_prd');
                if ($sumcek->sum() == $sumprd->sum()) {
                    return '<span class="badge badge-primary">Sudah Dicek</span>';
                } elseif ($sumcek->sum() == 0) {
                    return '<span class="badge badge-danger">Belum Dicek</span>';
                } elseif ($sumcek->sum() != $sumprd->sum()) {
                    return '<span class="badge badge-warning">Pengecekan Berlangsung</span>';
                }
            })
            ->addColumn('action', function ($data) {
                $x = explode('/', $data->so);
                $sumcek = DB::table('view_cek_produkso')->select('*', DB::raw('count(status_cek) as jml'), DB::raw('count(gbjid) as jml_prd'))->groupBy('pesananid')->where('pesananid', $data->id)->get()->pluck('jml');
                $sumprd = DB::table('view_cek_produkso')->select('*', DB::raw('count(status_cek) as jml'), DB::raw('count(gbjid) as jml_prd'))->groupBy('pesananid')->where('pesananid', $data->id)->get()->pluck('jml_prd');
                if ($sumcek->sum() == $sumprd->sum()) {
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    ';
                        } elseif ($x[1] == 'SPA') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="spa"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    ';
                        } elseif ($x[1] == 'SPB') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="spb"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    ';
                        }
                    }
                } elseif ($sumcek->sum() != $sumprd->sum()) {
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    <button type="button" data-toggle="modal" data-target="#editmodal" data-attr="" data-value="ekatalog" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm editmodal"><i class="fas fa-plus"></i> Siapkan Produk</button>
                                    ';
                        } elseif ($x[1] == 'SPA') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="spa"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    <button type="button" data-toggle="modal" data-target="#editmodal" data-attr="" data-value="spa" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm editmodal"><i class="fas fa-plus"></i> Siapkan Produk</button>
                                    ';
                        } elseif ($x[1] == 'SPB') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="spb"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    <button type="button" data-toggle="modal" data-target="#editmodal" data-attr="" data-value="spb" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm editmodal"><i class="fas fa-plus"></i> Siapkan Produk</button>
                                    ';
                        }
                    }
                } else {
                    if ($data->log_id == 9) {
                        for ($i = 1; $i < count($x); $i++) {
                            if ($x[1] == 'EKAT') {
                                return '
                                        <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                        <button type="button" data-toggle="modal" data-target="#editmodal" data-attr="" data-value="ekatalog" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm editmodal"><i class="fas fa-plus"></i> Siapkan Produk</button>
                                        ';
                            } elseif ($x[1] == 'SPA') {
                                return '
                                        <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="spa"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                        <button type="button" data-toggle="modal" data-target="#editmodal" data-attr="" data-value="spa" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm editmodal"><i class="fas fa-plus"></i> Siapkan Produk</button>
                                        ';
                            } elseif ($x[1] == 'SPB') {
                                return '
                                        <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="spb"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                        <button type="button" data-toggle="modal" data-target="#editmodal" data-attr="" data-value="spb" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm editmodal"><i class="fas fa-plus"></i> Siapkan Produk</button>
                                        ';
                            }
                        }
                    }
                }
            })
            ->addColumn('button_prd', function ($d) {
                $x = explode('/', $d->so);
                for ($i = 1; $i < count($x); $i++) {
                    if ($x[1] == 'EKAT') {
                        return '<a data-toggle="modal" data-target="#detailproduk" class="detailproduk" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                        </a>';
                    } elseif ($x[1] == 'SPA') {
                        return '<a data-toggle="modal" data-target="#detailproduk" class="detailproduk" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                        </a>';
                    } elseif ($x[1] == 'SPB') {
                        return '<a data-toggle="modal" data-target="#detailproduk" class="detailproduk" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                        </a>';
                    }
                }
            })
            ->rawColumns(['button', 'status', 'action', 'status1', 'status_prd', 'button_prd'])
            ->make(true);
    }

    function getOutSO()
    {
        $Ekatalog = collect(Pesanan::has('Ekatalog')->whereNotIn('log_id', [7, 10])->get());
        $Spa = collect(Pesanan::has('Spa')->whereNotIn('log_id', [7, 10])->get());
        $Spb = collect(Pesanan::has('Spb')->whereNotIn('log_id', [7, 10])->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);
        $x = [];
        foreach ($data as $k) {
            if ($k->getJumlahPesanan() != $k->cekJumlahkirim()) {
                $x[] = $k->id;
            }
        }

        $datax = Pesanan::whereIn('id', $x)->get();

        return datatables()->of($datax)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('no_po', function ($data) {
                return $data->no_po;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                for ($i = 1; $i < count($name); $i++) {
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->Spb->Customer->nama;
                    }
                }
            })
            ->addColumn('batas_out', function ($d) {
                if (isset($d->Ekatalog->tgl_kontrak)) {
                    if ($d->Ekatalog->Provinsi->status == 1) {
                        return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(5)->isoFormat('D MMMM YYYY');
                    }

                    if ($d->Ekatalog->Provinsi->status == 2) {
                        return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(4)->isoFormat('D MMMM YYYY');
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                $cek = TFProduksi::where('pesanan_id', $data->id)->where('status_id', 1)->get()->count();
                if ($cek == 0) {
                    if ($data->status_cek == 4) {
                        return '<span class="badge badge-success">Produk Sudah disiapkan</span>';
                    } else {
                        return '<span class="badge badge-danger">Produk belum disiapkan</span>';
                    }
                } else {
                    return '<span class="badge badge-info">Tersimpan ke rancangan</span>';
                }
            })
            ->addColumn('status_prd', function ($data) {
                if ($data->log_id) {
                    # code...
                    return '<span class="badge badge-warning">' . $data->log->nama . '</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('button', function ($data) {
                $x = explode('/', $data->so);
                $cek = TFProduksi::where('pesanan_id', $data->id)->where('status_id', 1)->get()->count();
                if ($cek == 0) {
                    if ($data->status_cek == 4) {
                        for ($i = 1; $i < count($x); $i++) {
                            if ($x[1] == 'EKAT') {
                                return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '">
                                        <button class="btn btn-outline-primary btn-sm" type="button">
                                            <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                        </button>
                                    </a>';
                            } elseif ($x[1] == 'SPA') {
                                return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-value="spa"  data-id="' . $data->id . '">
                                            <button class="btn btn-outline-primary btn-sm" type="button">
                                                <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                            </button>
                                        </a>';
                            } elseif ($x[1] == 'SPB') {
                                return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-value="spb"  data-id="' . $data->id . '">
                                            <button class="btn btn-outline-primary btn-sm" type="button">
                                                <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                            </button>
                                        </a>';
                            }
                        }
                    } else {
                        return 'Siapkan Produk Dahulu';
                    }
                } else {
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '<a data-toggle="modal" data-target="#editmodal" class="ubahmodal" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '">
                                    <button class="btn btn-outline-info btn-sm" type="button">
                                        <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                    </button>
                                </a>';
                        } elseif ($x[1] == 'SPA') {
                            return '<a data-toggle="modal" data-target="#editmodal" class="ubahmodal" data-attr="" data-value="spa"  data-id="' . $data->id . '">
                                        <button class="btn btn-outline-info btn-sm" type="button">
                                            <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPB') {
                            return '<a data-toggle="modal" data-target="#editmodal" class="ubahmodal" data-attr="" data-value="spb"  data-id="' . $data->id . '">
                                        <button class="btn btn-outline-info btn-sm" type="button">
                                            <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                        </button>
                                    </a>';
                        }
                    }
                }
            })
            ->addColumn('button_prd', function ($d) {
                $x = explode('/', $d->so);
                for ($i = 1; $i < count($x); $i++) {
                    if ($x[1] == 'EKAT') {
                        return '<a data-toggle="modal" data-target="#detailproduk" class="detailproduk" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                        </a>';
                    } elseif ($x[1] == 'SPA') {
                        return '<a data-toggle="modal" data-target="#detailproduk" class="detailproduk" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                        </a>';
                    } elseif ($x[1] == 'SPB') {
                        return '<a data-toggle="modal" data-target="#detailproduk" class="detailproduk" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                        </a>';
                    }
                }
            })
            ->rawColumns(['button', 'status', 'action', 'status1', 'status_prd', 'button_prd'])
            ->make(true);
    }

    function getSOProduksi()
    {
        $Ekatalog = collect(Pesanan::has('Ekatalog')->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                for ($i = 1; $i < count($name); $i++) {
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->Spb->Customer->nama;
                    } else {
                    }
                }

                if (empty($data->so)) {
                    return $data->Ekatalog->Customer->nama;
                }
            })
            ->addColumn('batas_out', function ($d) {
                if (isset($d->Ekatalog->tgl_kontrak)) {
                    return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->isoFormat('D MMMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('status_prd', function ($data) {
                if ($data->log_id) {
                    # code...
                    return '<span class="badge badge-warning">' . $data->log->nama . '</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('status1', function ($data) {
                $sumcek = DB::table('view_cek_produkso')->select('*', DB::raw('count(status_cek) as jml'), DB::raw('count(gbjid) as jml_prd'))->groupBy('pesananid')->where('pesananid', $data->id)->get()->pluck('jml');
                $sumprd = DB::table('view_cek_produkso')->select('*', DB::raw('count(status_cek) as jml'), DB::raw('count(gbjid) as jml_prd'))->groupBy('pesananid')->where('pesananid', $data->id)->get()->pluck('jml_prd');
                if ($sumcek == $sumprd) {
                    return '<span class="badge badge-primary">Sudah Dicek</span>';
                } elseif ($sumcek != $sumprd) {
                    return '<span class="badge badge-danger">Belum Dicek</span>';
                }
            })
            ->addColumn('action', function ($data) {
                $x = explode('/', $data->so);
                $sumcek = DB::table('view_cek_produkso')->select('*', DB::raw('count(status_cek) as jml'), DB::raw('count(gbjid) as jml_prd'))->groupBy('pesananid')->where('pesananid', $data->id)->get()->pluck('jml');
                $sumprd = DB::table('view_cek_produkso')->select('*', DB::raw('count(status_cek) as jml'), DB::raw('count(gbjid) as jml_prd'))->groupBy('pesananid')->where('pesananid', $data->id)->get()->pluck('jml_prd');
                if ($sumcek == $sumprd) {
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    ';
                        } elseif ($x[1] == 'SPA') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="spa"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    ';
                        } elseif ($x[1] == 'SPB') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="spb"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    ';
                        }
                    }
                } else {
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    <button type="button" data-toggle="modal" data-target="#editmodal" data-attr="" data-value="ekatalog" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm editmodal"><i class="fas fa-plus"></i> Siapkan Produk</button>
                                    ';
                        } elseif ($x[1] == 'SPA') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="spa"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    <button type="button" data-toggle="modal" data-target="#editmodal" data-attr="" data-value="spb" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm editmodal"><i class="fas fa-plus"></i> Siapkan Produk</button>
                                    ';
                        } elseif ($x[1] == 'SPB') {
                            return '
                                    <button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-value="spb"  data-id="' . $data->id . '" class="btn btn-outline-success btn-sm detailmodal"><i class="far fa-eye"></i> Detail</button>
                                    <button type="button" data-toggle="modal" data-target="#editmodal" data-attr="" data-value="spb" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm editmodal"><i class="fas fa-plus"></i> Siapkan Produk</button>
                                    ';
                        }
                    }
                }
            })
            ->addColumn('button_prd', function ($d) {
                $x = explode('/', $d->so);
                for ($i = 1; $i < count($x); $i++) {
                    if ($x[1] == 'EKAT') {
                        return '<a data-toggle="modal" data-target="#detailproduk" class="detailproduk" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                        </a>';
                    } elseif ($x[1] == 'SPA') {
                        return '<a data-toggle="modal" data-target="#detailproduk" class="detailproduk" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                        </a>';
                    } elseif ($x[1] == 'SPB') {
                        return '<a data-toggle="modal" data-target="#detailproduk" class="detailproduk" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                        </a>';
                    }
                }

                if (empty($d->so)) {
                    return '<a data-toggle="modal" data-target="#detailproduk" class="detailproduk" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                        <button class="btn btn-outline-info viewProduk"><i class="far fa-eye"></i>&nbsp;Detail</button>
                    </a>';
                }
            })
            ->addColumn('btn', function ($d) {
                $x = explode('/', $d->so);
                for ($i = 1; $i < count($x); $i++) {
                    if ($x[1] == 'EKAT') {
                        return '' . $d->id . '';
                    } elseif ($x[1] == 'SPA') {
                        return '' . $d->id . '';
                    } elseif ($x[1] == 'SPB') {
                        return '' . $d->id . '';
                    }
                }

                if (empty($d->so)) {
                    return '' . $d->id . '';
                }
            })
            ->addColumn('btnValue', function ($d) {
                $x = explode('/', $d->so);
                for ($i = 1; $i < count($x); $i++) {
                    if ($x[1] == 'EKAT') {
                        return 'ekatalog';
                    } elseif ($x[1] == 'SPA') {
                        return 'spa';
                    } elseif ($x[1] == 'SPB') {
                        return 'spb';
                    }
                }

                if (empty($d->so)) {
                    return 'ekatalog';
                }
            })
            ->rawColumns(['button', 'status', 'action', 'status1', 'status_prd', 'button_prd'])
            ->make(true);
    }

    function getDetailSO(Request $request, $id, $value)
    {
        if ($value == "ekatalog") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        } else if ($value == "spa") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        } else if ($value == "spb") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        }

        return datatables()->of($g)
            ->addIndexColumn()
            ->addColumn('paket', function ($d) {
                return $d->detailpesanan->penjualanproduk->nama;
            })
            ->addColumn('produk', function ($data) {
                if (empty($data->gudangbarangjadi->nama)) {
                    return $data->gudangbarangjadi->produk->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $data->gudang_barang_jadi_id . '"><input type="hidden" name="detail_pesanan_produk_id[]" id="detail_pesanan_produk_id" value="' . $data->id . '">';
                } else {
                    return $data->gudangbarangjadi->produk->nama . '-' . $data->gudangbarangjadi->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $data->gudang_barang_jadi_id . '"><input type="hidden" name="detail_pesanan_produk_id[]" id="detail_pesanan_produk_id" value="' . $data->id . '">';
                }
            })
            ->addColumn('qty', function ($data) {
                $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($data) {
                    $q->where('id', $data->id);
                })->get();
                $x = 0;
                foreach ($s as $i) {
                    foreach ($i->PenjualanProduk->Produk as $j) {
                        if ($j->id == $data->gudangbarangjadi->produk_id) {
                            $x = $i->jumlah * $j->pivot->jumlah;
                        }
                    }
                }
                return $x . '<input type="hidden" class="jumlah" name="qty[]" id="qty" value="' . $x . '">';
            })
            ->addColumn('jumlah', function ($data) {
                $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($data) {
                    $q->where('id', $data->id);
                })->get();
                $x = 0;
                foreach ($s as $i) {
                    foreach ($i->PenjualanProduk->Produk as $j) {
                        if ($j->id == $data->gudangbarangjadi->produk_id) {
                            $x = $i->jumlah * $j->pivot->jumlah;
                        }
                    }
                }
                return $x . ' ' . $data->gudangbarangjadi->satuan->nama;
            })
            ->addColumn('tipe', function ($data) {
                if (empty($data->gudangbarangjadi->nama)) {
                    return $data->gudangbarangjadi->produk->nama;
                } else {
                    return $data->gudangbarangjadi->produk->nama . ' ' . $data->gudangbarangjadi->nama;
                }
            })
            ->addColumn('merk', function ($data) {
                return $data->gudangbarangjadi->produk->merk;
            })
            ->addColumn('ids', function ($d) {
                if ($d->status_cek == 4) {
                    return '<input type="checkbox" class="cb-child-so" value="' . $d->gudang_barang_jadi_id . '" disabled>';
                } else {
                    return '<input type="checkbox" class="cb-child-so" value="' . $d->gudang_barang_jadi_id . '"><input type="hidden" name="detail_pesanan_produk_id[]" id="detail_pesanan_produk_id" value="' . $d->id . '">';
                }
            })
            ->addColumn('action', function ($data) {
                $cek = TFProduksiDetail::whereHas('header', function ($q) use ($data) {
                    $q->where('pesanan_id', $data->detailpesanan->pesanan->id);
                })->where('gdg_brg_jadi_id', $data->gudang_barang_jadi_id)->where('detail_pesanan_produk_id', $data->id)->get();
                if (count($cek) > 0) {
                    $datacek = NoseriTGbj::whereHas('detail', function ($q) use ($data) {
                        $q->where('gdg_brg_jadi_id', $data->gudang_barang_jadi_id);
                        $q->where('detail_pesanan_produk_id', $data->id);
                    })->whereHas('detail.header', function ($q) use ($data) {
                        $q->where('pesanan_id', $data->detailpesanan->pesanan->id);
                    })->get()->count();

                    $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($data) {
                        $q->where('id', $data->id);
                    })->get();
                    $x = 0;
                    foreach ($s as $i) {
                        foreach ($i->PenjualanProduk->Produk as $j) {
                            if ($j->id == $data->gudangbarangjadi->produk_id) {
                                $x = $i->jumlah * $j->pivot->jumlah;
                            }
                        }
                    }
                    if ($x == $datacek) {
                    } else {
                        $jml_now = $x - $datacek;
                        // return $datacek;
                        return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="" data-jml="' . $jml_now . '" data-id="' . $data->gudang_barang_jadi_id . '" data-dpp="' . $data->id . '">
                                <button class="btn btn-primary" data-toggle="modal" data-target=".modal-scan"><i
                                class="fas fa-qrcode"></i> Scan Produk</button>
                                </a>';
                    }
                } else {
                    $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($data) {
                        $q->where('id', $data->id);
                    })->get();
                    $x = 0;
                    foreach ($s as $i) {
                        foreach ($i->PenjualanProduk->Produk as $j) {
                            if ($j->id == $data->gudangbarangjadi->produk_id) {
                                $x = $i->jumlah * $j->pivot->jumlah;
                            }
                        }
                    }
                    return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="" data-jml="' . $x . '" data-id="' . $data->gudang_barang_jadi_id . '" data-dpp="' . $data->id . '">
                                <button class="btn btn-primary" data-toggle="modal" data-target=".modal-scan"><i
                                class="fas fa-qrcode"></i> Scan Produk</button>
                                </a>';
                }
            })
            ->addColumn('status', function ($data) {
                if (isset($data->status_cek)) {
                    return '<span class="badge badge-success">Sudah Diinput</span>';
                } else {
                    return '<span class="badge badge-danger">Belum Diinput</span>';
                }
            })
            ->addColumn('status_prd', function ($d) {
                if (isset($d->detailpesanan->pesanan->log_id)) {
                    return '<span class="badge badge-success">' . $d->detailpesanan->pesanan->log->nama . '</span>';
                } else {
                    return '<span class="badge badge-danger">Belum dicek</span>';
                }
            })
            ->addColumn('checkbox', function ($d) {
                $cek = TFProduksiDetail::whereHas('header', function ($q) use ($d) {
                    $q->where('pesanan_id', $d->detailpesanan->pesanan->id);
                })->where('gdg_brg_jadi_id', $d->gudang_barang_jadi_id)->get();
                if (count($cek) > 0) {
                    $datacek = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                        $q->where('gdg_brg_jadi_id', $d->gudang_barang_jadi_id);
                        $q->where('detail_pesanan_produk_id', $d->id);
                    })->whereHas('detail.header', function ($q) use ($d) {
                        $q->where('pesanan_id', $d->detailpesanan->pesanan->id);
                    })->get()->count();

                    $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($d) {
                        $q->where('id', $d->id);
                    })->get();
                    $x = 0;
                    foreach ($s as $i) {
                        foreach ($i->PenjualanProduk->Produk as $j) {
                            if ($j->id == $d->gudangbarangjadi->produk_id) {
                                $x = $i->jumlah * $j->pivot->jumlah;
                            }
                        }
                    }
                    if ($x == $datacek) {
                    } else {
                        return '<input type="checkbox" class="cb-child-prd" name="gbj_id" value="' . $d->gudang_barang_jadi_id . '"><input type="hidden" name="detail_pesanan_produk_id[]" id="detail_pesanan_produk_id" value="' . $d->id . '">';
                    }
                } else {
                    return '<input type="checkbox" class="cb-child-prd" name="gbj_id" value="' . $d->gudang_barang_jadi_id . '"><input type="hidden" name="detail_pesanan_produk_id[]" id="detail_pesanan_produk_id" value="' . $d->id . '">';
                }
            })
            ->rawColumns(['action', 'status', 'produk', 'qty', 'checkbox', 'status_prd', 'ids'])
            ->make(true);
        // return response()->json($g);
    }

    function getEditSO(Request $request, $id, $value)
    {
        if ($value == "ekatalog") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        } else if ($value == "spa") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        } else if ($value == "spb") {
            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $detail_id = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
        }

        return datatables()->of($g)
            ->addIndexColumn()
            ->addColumn('produk', function ($data) {
                if (empty($data->gudangbarangjadi->nama)) {
                    return $data->gudangbarangjadi->produk->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $data->gudang_barang_jadi_id . '">';
                } else {
                    return $data->gudangbarangjadi->produk->nama . '-' . $data->gudangbarangjadi->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $data->gudang_barang_jadi_id . '">';
                }
            })
            ->addColumn('qty', function ($data) {
                return $data->detailpesanan->jumlah . '<input type="hidden" class="jumlah" name="qty[]" id="qty" value="' . $data->detailpesanan->jumlah . '">';
            })
            ->addColumn('tipe', function ($data) {
                if (empty($data->gudangbarangjadi->nama)) {
                    return $data->gudangbarangjadi->produk->nama;
                } else {
                    return $data->gudangbarangjadi->produk->nama . ' ' . $data->gudangbarangjadi->nama;
                }
            })
            ->addColumn('merk', function ($data) {
                return $data->gudangbarangjadi->produk->merk;
            })
            ->addColumn('ids', function ($d) {
                if ($d->status_cek == 4) {
                    return '<input type="checkbox" class="cb-child-so" value="' . $d->gudang_barang_jadi_id . '" checked>';
                } else {
                    return '<input type="checkbox" class="cb-child-so" value="' . $d->gudang_barang_jadi_id . '">';
                }
            })
            ->addColumn('action', function ($data) {
                return '<a data-toggle="modal" data-target="#serimodal" class="serimodal" data-attr="" data-so="' . $data->detailpesanan->pesanan->id . '" data-jml="' . $data->detailpesanan->jumlah . '" data-id="' . $data->gudang_barang_jadi_id . '">
                                <button class="btn btn-primary" data-toggle="modal" data-target=".modal-scan-edit"><i
                                class="fas fa-qrcode"></i> Scan Produk</button>
                                </a>';
            })
            ->addColumn('status', function ($data) {
                if (isset($data->status_cek)) {
                    return '<span class="badge badge-success">Sudah Diinput</span>';
                } else {
                    return '<span class="badge badge-danger">Belum Diinput</span>';
                }
            })
            ->addColumn('status_prd', function ($d) {
                if (isset($d->detailpesanan->pesanan->log_id)) {
                    return '<span class="badge badge-success">' . $d->detailpesanan->pesanan->log->nama . '</span>';
                } else {
                    return '<span class="badge badge-danger">Belum dicek</span>';
                }
            })
            ->addColumn('checkbox', function ($d) {
                $cek = TFProduksiDetail::whereHas('header', function ($q) use ($d) {
                    $q->where('pesanan_id', $d->detailpesanan->pesanan->id);
                })->where('gdg_brg_jadi_id', $d->gudang_barang_jadi_id)->get();
                if (count($cek) > 0) {
                    return '<input type="checkbox" class="cb-prd-edit" name="gbj_id" value="' . $d->gudang_barang_jadi_id . '" checked="checked">';
                } else {
                    return '<input type="checkbox" class="cb-prd-edit" name="gbj_id" value="' . $d->gudang_barang_jadi_id . '">';
                }
            })
            ->rawColumns(['action', 'status', 'produk', 'qty', 'checkbox', 'status_prd', 'ids'])
            ->make(true);
    }

    function headerSo($id, $value)
    {
        if ($value == "ekatalog") {
            $data = Pesanan::with('Ekatalog')->find($id);
            return response()->json([
                'so' => $data->so == null ? '-' : $data->so,
                'po' => $data->no_po == null ? '-' : $data->no_po,
                'akn' => $data->Ekatalog->no_paket,
                'customer' => $data->Ekatalog->Customer->nama,
            ]);
        } else if ($value == "spa") {
            $data = Pesanan::with('spa')->find($id);
            return response()->json([
                'so' => $data->so == null ? '-' : $data->so,
                'po' => $data->no_po == null ? '-' : $data->no_po,
                'akn' => '-',
                'customer' => $data->spa->Customer->nama,
            ]);
        } else if ($value == "spb") {
            $data = Pesanan::with('spb')->find($id);
            return response()->json([
                'so' => $data->so == null ? '-' : $data->so,
                'po' => $data->no_po == null ? '-' : $data->no_po,
                'akn' => '-',
                'customer' => $data->spb->Customer->nama,
            ]);
        }
    }

    function getHistorybyProduk()
    {
        $data = GudangBarangJadi::with('produk', 'satuan')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('stock', function ($d) {
                return $d->stok . ' ' . $d->satuan->nama;
            })
            ->addColumn('kelompok', function ($d) {
                return $d->produk->kelompokproduk->nama;
            })
            ->addColumn('product', function ($d) {
                return $d->produk->nama . ' ' . $d->nama;
            })
            ->addColumn('kode_produk', function ($d) {
                return $d->produk->product->kode . '' . $d->produk->kode;
            })
            ->addColumn('action', function ($d) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '">
                            <button class="btn btn-info" data-toggle="modal" data-target=".modal-detail"><i
                            class="far fa-eye"></i> Detail</button>
                            </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getNoseriSO(Request $request)
    {
        $data = NoseriBarangJadi::where('gdg_barang_jadi_id', $request->gdg_barang_jadi_id)->where('is_ready', 0)->get();
        $i = 0;
        return datatables()->of($data)
            ->addColumn('ids', function ($d) {
                return $d->id;
            })
            ->addColumn('seri', function ($d) {
                return $d->noseri . '';
            })
            ->addColumn('checkbox', function ($d) use ($i) {
                $i++;
                return '<input type="checkbox" class="cb-child" name="noseri_id[][' . $i . ']"  value="' . $d->id . '">';
            })
            ->RawColumns(['seri', 'checkbox'])
            ->make(true);
    }

    function getNoseriSOEdit(Request $request)
    {
        $data = NoseriBarangJadi::where('gdg_barang_jadi_id', $request->gdg_barang_jadi_id)->where('is_aktif', 1)->whereNull('used_by')->get();
        $i = 0;
        return datatables()->of($data)
            ->addColumn('seri', function ($d) {
                return $d->noseri . '';
            })
            ->addColumn('checkbox', function ($d) use ($i) {
                $i++;
                if ($d->is_ready == 1) {
                    return '<input type="checkbox" class="cb-child-edit" name="noseri_id[][' . $i . ']"  value="' . $d->id . '" checked>';
                } else {
                    return '<input type="checkbox" class="cb-child-edit" name="noseri_id[][' . $i . ']"  value="' . $d->id . '">';
                }
            })
            ->RawColumns(['seri', 'checkbox'])
            ->make(true);
    }

    function UncheckedNoseri(Request $request)
    {
        NoseriBarangJadi::find($request->id)->update(['is_ready' => 0]);
    }

    function checkedNoseri(Request $request)
    {
        NoseriBarangJadi::find($request->id)->update(['is_ready' => 1]);
    }

    // check
    function checkStok(Request $request)
    {
        $gdg = GudangBarangJadi::where('id', $request->gdg_brg_jadi_id)->first();
        return $gdg;
    }

    // sale
    function h_minus10()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
            $q->whereDate('tgl_kontrak', '<=', Carbon::now()->subDays(10));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function h_minus5()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
            $q->whereDate('tgl_kontrak', '<=', Carbon::now()->subDays(5));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function h_exp()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
            $q->whereDate('tgl_kontrak', '<', Carbon::now());
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function minus5()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
            $q->whereDate('tgl_kontrak', '<=', Carbon::now()->subDays(5));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                for ($i = 1; $i < count($name); $i++) {
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->Spb->Customer->nama;
                    } else {
                    }
                }

                if (empty($data->so)) {
                    return $data->Ekatalog->Customer->nama;
                }
            })
            ->addColumn('batas_out', function ($d) {
                if (isset($d->Ekatalog->tgl_kontrak)) {
                    return Carbon::parse($d->Ekatalog->tgl_kontrak)->isoFormat('D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('status_prd', function ($data) {
                if ($data->log_id) {
                    # code...
                    return '<span class="badge badge-warning">' . $data->log->nama . '</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('button', function ($data) {
                $x = explode('/', $data->so);
                for ($i = 1; $i < count($x); $i++) {
                    if ($x[1] == 'EKAT') {
                        return '<a data-toggle="modal" data-target="#minus5" class="minus5" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPA') {
                        return '<a data-toggle="modal" data-target="#minus5" class="minus5" data-attr="" data-value="spa"  data-id="' . $data->id . '">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPB') {
                        return '<a data-toggle="modal" data-target="#minus5" class="minus5" data-attr="" data-value="spb"  data-id="' . $data->id . '">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    }
                }
            })
            ->rawColumns(['button', 'status_prd'])
            ->make(true);
    }

    function minus10()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
            $q->whereDate('tgl_kontrak', '<=', Carbon::now()->subDays(10));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                for ($i = 1; $i < count($name); $i++) {
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->Spb->Customer->nama;
                    } else {
                    }
                }

                if (empty($data->so)) {
                    return $data->Ekatalog->Customer->nama;
                }
            })
            ->addColumn('batas_out', function ($d) {
                if (isset($d->Ekatalog->tgl_kontrak)) {
                    return Carbon::parse($d->Ekatalog->tgl_kontrak)->isoFormat('D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('status_prd', function ($data) {
                if ($data->log_id) {
                    # code...
                    return '<span class="badge badge-warning">' . $data->log->nama . '</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('button', function ($data) {
                $x = explode('/', $data->so);
                for ($i = 1; $i < count($x); $i++) {
                    if ($x[1] == 'EKAT') {
                        return '<a data-toggle="modal" data-target="#minus10" class="minus10" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPA') {
                        return '<a data-toggle="modal" data-target="#minus10" class="minus10" data-attr="" data-value="spa"  data-id="' . $data->id . '">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPB') {
                        return '<a data-toggle="modal" data-target="#minus10" class="minus10" data-attr="" data-value="spb"  data-id="' . $data->id . '">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    }
                }
            })
            ->rawColumns(['button', 'status_prd'])
            ->make(true);
    }

    function expired()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
            $q->whereDate('tgl_kontrak', '<', Carbon::now());
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                for ($i = 1; $i < count($name); $i++) {
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->Spb->Customer->nama;
                    } else {
                    }
                }

                if (empty($data->so)) {
                    return $data->Ekatalog->Customer->nama;
                }
            })
            ->addColumn('batas_out', function ($d) {
                // $m = strtotime($d->Ekatalog->tgl_kontrak);
                // $a = strtotime(Carbon::now());
                // $s = $a - $m;
                // $x = abs(floor($s / (60 * 60 * 24)));
                if (isset($d->Ekatalog->tgl_kontrak)) {
                    $a = Carbon::now()->diffInDays($d->Ekatalog->tgl_kontrak);
                    return Carbon::parse($d->Ekatalog->tgl_kontrak)->isoFormat('D MMM YYYY') . '<br> <span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('status_prd', function ($data) {
                if ($data->log_id) {
                    # code...
                    return '<span class="badge badge-warning">' . $data->log->nama . '</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('button', function ($data) {
                $x = explode('/', $data->so);
                for ($i = 1; $i < count($x); $i++) {
                    if ($x[1] == 'EKAT') {
                        return '<a data-toggle="modal" data-target="#expired" class="expired" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPA') {
                        return '<a data-toggle="modal" data-target="#expired" class="expired" data-attr="" data-value="spa"  data-id="' . $data->id . '">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    } elseif ($x[1] == 'SPB') {
                        return '<a data-toggle="modal" data-target="#expired" class="expired" data-attr="" data-value="spb"  data-id="' . $data->id . '">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </a>';
                    }
                }
            })
            ->rawColumns(['button', 'batas_out', 'status_prd'])
            ->make(true);
    }
    // rakit

    function exp_rakit()
    {
        $data = JadwalPerakitan::whereRaw('DATEDIFF(tanggal_selesai, now()) <= 10')
            ->whereRaw('DATEDIFF(tanggal_selesai, now()) > 0')
            ->get();
        return datatables()->of($data)
            ->addColumn('start', function ($d) {
                return Carbon::parse($d->tanggal_mulai)->isoFormat('D MMM YYYY');
            })
            ->addColumn('end', function ($d) {
                $m = strtotime($d->tanggal_selesai);
                $a = strtotime(Carbon::now());
                $s = $a - $m;
                $x = abs(floor($s / (60 * 60 * 24)));

                if ($x <= 10 && $x > 5) {
                    return Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '<br> <span class="badge badge-info">Kurang ' . $x . ' Hari</span>';
                } elseif ($x <= 5 && $x >= 2) {
                    return Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '<br> <span class="badge badge-warning">Kurang ' . $x . ' Hari</span>';
                } elseif ($x < 2 && $x >= 0) {
                    return Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '<br> <span class="badge badge-danger">Kurang ' . $x . ' Hari</span>';
                }
            })
            ->addColumn('no_bppb', function ($d) {
                return '-';
            })
            ->addColumn('produk', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->jumlah . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('button', function ($d) {
                return '<a href="' . url('produksi/jadwal_perakitan') . '" class="btn btn-outline-primary"><i
                        class="fas fa-paper-plane">';
            })
            ->addColumn('button1', function ($d) {
                if ($d->status_tf == 14) {
                    return '<a href="' . url('produksi/riwayat_perakitan') . '" class="btn btn-outline-primary"><i
                        class="fas fa-paper-plane">';
                } elseif ($d->status_tf == 13) {
                    return '<a href="' . url('produksi/pengiriman') . '" class="btn btn-outline-primary"><i
                        class="fas fa-paper-plane">';
                } else {
                    return '<a href="' . url('produksi/jadwal_perakitan') . '" class="btn btn-outline-primary"><i
                        class="fas fa-paper-plane">';
                }
            })
            ->rawColumns(['button', 'end', 'button1'])
            ->make(true);
    }

    function exp_rakit_h()
    {
        $data = JadwalPerakitan::whereRaw('DATEDIFF(tanggal_selesai, now()) <= 10')
            ->whereRaw('DATEDIFF(tanggal_selesai, now()) > 0')->get();
        return count($data);
    }

    function exp_jadwal()
    {
        $data = JadwalPerakitan::where('state', 'perubahan')->whereNotIn('status_tf', [14])->get();
        return datatables()->of($data)
            ->addColumn('start', function ($d) {
                return Carbon::parse($d->tanggal_mulai)->isoFormat('D MMM YYYY');
            })
            ->addColumn('end', function ($d) {
                return Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY');
            })
            ->addColumn('no_bppb', function ($d) {
                return '-';
            })
            ->addColumn('produk', function ($d) {
                return $d->produk->produk->nama . '-' . $d->produk->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->jumlah . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('button', function ($d) {
                return '<a href="' . url('produksi/jadwal_perakitan') . '" class="btn btn-outline-primary"><i
                        class="fas fa-paper-plane">';
            })
            ->rawColumns(['button', 'end'])
            ->make(true);
    }

    function exp_jadwal_h()
    {
        $data = JadwalPerakitan::where('state', 'perubahan')->whereNotIn('status_tf', [14])->get();
        return count($data);
    }

    function change_jadwal()
    {
        $data = DB::table('prd_dashboard_perubahan_jadwal')->get();

        return datatables()->of($data)
            ->addColumn('start', function ($d) {
                return Carbon::parse($d->tanggal_mulai)->isoFormat('D MMM YYYY');
            })
            ->addColumn('end', function ($d) {
                return Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY');
            })
            ->addColumn('no_bppb', function ($d) {
                return '-';
            })
            ->addColumn('produk', function ($d) {
                return $d->produk->produk->nama . '-' . $d->produk->nama;
            })
            // ->addColumn('jml', function ($d) {
            //     return $d->jumlah . ' ' . $d->produk->satuan->nama;
            // })
            // ->addColumn('button', function ($d) {
            //     return '<a href="' . url('produksi/jadwal_perakitan') . '" class="btn btn-outline-primary"><i
            //             class="fas fa-paper-plane">';
            // })
            // ->rawColumns(['button', 'end'])
            ->make(true);
    }

    // perakitan
    function plan_rakit()
    {
        $data = JadwalPerakitan::whereMonth('tanggal_mulai', Carbon::now()->addMonth())->get();
        $res = datatables()->of($data)
            ->addColumn('start', function ($d) {
                if (isset($d->tanggal_mulai)) {
                    return Carbon::parse($d->tanggal_mulai)->isoFormat('D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('end', function ($d) {
                if (isset($d->tanggal_selesai)) {
                    return Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('produk', function ($d) {
                if (isset($d->produk_id)) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                }
            })
            ->addColumn('jml', function ($d) {
                return $d->jumlah . ' ' . $d->produk->satuan->nama;
            })
            ->make(true);
        return $res;
    }

    function calender_plan()
    {
        $data = JadwalPerakitan::with('Produk.Produk')->whereMonth('tanggal_mulai', Carbon::now()->addMonth())->get();
        return response()->json($data);
    }

    function calender_current()
    {
        $data = JadwalPerakitan::with('Produk.Produk')->whereNotIn('status', [6])->get();
        return response()->json($data);
    }
    function on_rakit()
    {
        $data = JadwalPerakitan::whereNotIn('status', [6])->whereIn('status_tf', [11, 12, 13])->orderByDesc('created_at')->get();
        $res = datatables()->of($data)
            ->addColumn('start', function ($d) {
                if (isset($d->tanggal_mulai)) {
                    return Carbon::parse($d->tanggal_mulai)->isoFormat('D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('end', function ($d) {
                $m = strtotime($d->tanggal_selesai);
                $a = strtotime(Carbon::now());
                $s = $a - $m;
                $x = floor($s / (60 * 60 * 24));

                if (isset($d->tanggal_selesai)) {
                    if ($x >= -10 && $x < -5) {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="badge badge-warning">Kurang ' . abs($x) . ' Hari</span>';
                    } elseif ($x >= -5 && $x <= -2) {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="badge badge-warning">Kurang ' . abs($x) . ' Hari</span>';
                    } elseif ($x > -2 && $x <= 0) {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="badge badge-danger">Kurang ' . $x . ' Hari</span>';
                    } elseif ($x > 0) {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="badge badge-danger">Lebih ' . $x . ' Hari</span>';
                    } elseif ($x < -10) {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="badge badge-warning">Kurang ' . abs($x) . ' Hari</span>';
                    } else {
                        return '<span class="tanggal">'.Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span> ' . $x;
                    }
                    // return date('d-m-Y', strtotime($d->tanggal_selesai)).' '.$x;
                } else {
                    return '-';
                }
            })
            ->addColumn('produk', function ($d) {
                if (isset($d->produk_id)) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                }
            })
            ->addColumn('jml', function ($d) {
                $seri = JadwalRakitNoseri::where('jadwal_id', $d->id)->get();
                $c = count($seri);
                if ($c != $d->jumlah) {
                    return $d->jumlah . ' ' . $d->produk->satuan->nama . '<br><span class="badge badge-dark">Kurang ' . intval($d->jumlah - $c) . ' Unit</span>';
                } else {
                    return $d->jumlah . ' ' . $d->produk->satuan->nama;
                }
            })
            ->addColumn('action', function ($d) {
                $seri = JadwalRakitNoseri::where('jadwal_id', $d->id)->get();
                $c = count($seri);
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '" data-jml="' . intval($d->jumlah - $c) . '">
                                <button class="btn btn-outline-info btn-sm"><i class="far fa-edit"></i> Rakit Produk</button>
                            </a>&nbsp;<a data-toggle="modal" data-target="#detailtransfer" class="detailtransfer" data-attr=""  data-id="' . $d->id . '" data-jml="' . intval($d->jumlah - $c) . '" data-prd="'.$d->produk->produk->nama . ' ' . $d->produk->nama.'">
                            <button class="btn btn-outline-danger btn-sm"><i class="far fa-edit"></i> Transfer Sisa Produk</button>
                        </a>';
            })
            ->addColumn('created_at', function ($d) {
                return $d->created_at;
            })
            ->addColumn('no_bppb', function ($d) {
                return $d->no_bppb == null ? '-' : $d->no_bppb;
            })
            ->addColumn('periode', function ($d) {
                if (isset($d->tanggal_mulai)) {
                    return Carbon::parse($d->tanggal_mulai)->isoFormat('MMMM');
                } else {
                    return '-';
                }
            })
            ->rawColumns(['action', 'jml', 'end'])
            ->make(true);
        return $res;
    }

    function getSelesaiRakit()
    {
        $data = JadwalPerakitan::whereIn('status_tf', [15, 13, 12])->whereNotIn('status', [6])->get();
        return datatables()->of($data)
            ->addColumn('periode', function ($d) {
                if (isset($d->tanggal_mulai)) {
                    return Carbon::parse($d->tanggal_mulai)->isoFormat('MMMM');
                } else {
                    return '-';
                }
            })
            ->addColumn('start', function ($d) {
                if (isset($d->tanggal_mulai)) {
                    return Carbon::parse($d->tanggal_mulai)->isoFormat('D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('end', function ($d) {
                if (isset($d->tanggal_selesai)) {
                    return Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('produk', function ($d) {
                if (isset($d->produk_id)) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                }
            })
            ->addColumn('jml', function ($d) {
                $seri = JadwalRakitNoseri::where('jadwal_id', $d->id)->get();
                $c = count($seri);
                if ($c != $d->jumlah) {
                    return  $d->jumlah . ' ' . $d->produk->satuan->nama . '<br><span class="badge badge-dark">Terisi ' . intval($c) . ' Unit</span>';
                } else {
                    return  $d->jumlah . ' ' . $d->produk->satuan->nama;
                }
            })
            ->addColumn('action', function ($d) {
                if ($d->status_tf == 12) {
                    $seri = JadwalRakitNoseri::where('jadwal_id', $d->id)->where('status', 14)->get();
                    $c = count($seri);
                    $seri_all = JadwalRakitNoseri::where('jadwal_id', $d->id)->get();
                    $c_all = count($seri_all);
                    $seri_belum = JadwalRakitNoseri::where('jadwal_id', $d->id)->where('status', 11)->get()->count();
                    if ($c == $c_all) {
                    } else {
                        return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '" data-jml="' . $d->jumlah . '" data-prd="' . $d->produk_id . '">
                            <button class="btn btn-outline-success btn-sm"><i class="far fa-edit"></i> Transfer</button>
                        </a>&nbsp;<a data-toggle="modal" data-target="#detailmodalTransfer" class="detailmodalTransfer" data-attr=""  data-id="' . $d->id . '" data-jml="' . $seri_belum . '" data-prd="' . $d->produk_id . '">
                        <button class="btn btn-outline-danger btn-sm"><i class="far fa-edit"></i> Transfer Sisa Produk</button>
                    </a>';
                    }
                    // return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '" data-jml="' . $d->jumlah . '" data-prd="' . $d->produk_id . '">
                    //     <button class="btn btn-outline-success"><i class="far fa-edit"></i> Transfer</button>
                    // </a>';
                } elseif ($d->status_tf == 13) {
                    $seri = JadwalRakitNoseri::where('jadwal_id', $d->id)->where('status', 14)->get();
                    $c = count($seri);
                    $seri_all = JadwalRakitNoseri::where('jadwal_id', $d->id)->get();
                    $c_all = count($seri_all);
                    $seri_belum = JadwalRakitNoseri::where('jadwal_id', $d->id)->where('status', 11)->get()->count();
                    if ($c == $c_all) {
                    } else {
                        return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '" data-jml="' . $d->jumlah . '" data-prd="' . $d->produk_id . '">
                            <button class="btn btn-outline-success btn-sm"><i class="far fa-edit"></i> Transfer</button>
                        </a>&nbsp;<a data-toggle="modal" data-target="#detailmodalTransfer" class="detailmodalTransfer" data-attr=""  data-id="' . $d->id . '" data-jml="' . $seri_belum . '" data-prd="' . $d->produk_id . '">
                        <button class="btn btn-outline-danger btn-sm"><i class="far fa-edit"></i> Transfer Sisa Produk</button>
                    </a>';
                    }
                } else {
                    $seri_belum = JadwalRakitNoseri::where('jadwal_id', $d->id)->where('status', 11)->get()->count();
                    return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '" data-jml="' . $d->jumlah . '" data-prd="' . $d->produk_id . '">
                        <button class="btn btn-outline-success btn-sm"><i class="far fa-edit"></i> Transfer</button>
                    </a>&nbsp;<a data-toggle="modal" data-target="#detailmodalTransfer" class="detailmodalTransfer" data-attr=""  data-id="' . $d->id . '" data-jml="' . $seri_belum . '" data-prd="' . $d->produk_id . '">
                    <button class="btn btn-outline-danger btn-sm"><i class="far fa-edit"></i> Transfer Sisa Produk</button>
                </a>';
                }
            })
            ->addColumn('status', function ($d) {
                $seri = JadwalRakitNoseri::where('jadwal_id', $d->id)->get();
                $c = count($seri);
                $jj = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                    $q->where('gdg_brg_jadi_id', $d->produk_id);
                })->get();
                $a = count($jj);
                if ($d->status_tf == 12) {
                    return '<span class="belum_diterima">Sudah Terisi Sebagian</span>';
                } elseif ($d->status_tf == 13) {
                    return '<span class="sebagian_diterima">Sudah Terkirim Sebagian</span>';
                } else {
                    return '<span class="sudah_diterima">Sudah Terisi Semua</span>';
                    // return 'xx';
                }
            })
            ->addColumn('no_bppb', function ($d) {
                return $d->no_bppb == null ? '-' : $d->no_bppb;
            })
            ->rawColumns(['action', 'status', 'jml'])
            ->make(true);
    }

    function detailRakitHeader($id)
    {
        $d = JadwalPerakitan::find($id);
        return response()->json([
            'no_bppb' => $d->no_bppb == null ? '-' : $d->no_bppb,
            'produk' => $d->produk->produk->nama . ' ' . $d->produk->nama,
            'kategori' => $d->produk->produk->KelompokProduk->nama,
            'jml' => $d->jumlah . ' ' . $d->produk->satuan->nama,
            'start' => Carbon::parse($d->tanggal_mulai)->isoFormat('D MMM YYYY'),
            'end' => Carbon::parse($d->tanggal_sel)->isoFormat('D MMM YYYY'),
        ]);
    }

    function cekDuplicateNoseri(Request $request)
    {
        $noseri = JadwalRakitNoseri::whereIn('noseri', $request->noseri)->get();
        $data = JadwalRakitNoseri::whereIn('noseri', $request->noseri)->get()->count();
        $seri = [];
        if ($data > 0) {
            foreach ($noseri as $item) {
                array_push($seri, $item->noseri);
            }
            return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri) . ' sudah terdaftar', 'error' => true]);
        } else {
            return response()->json(['msg' => 'Success', 'error' => false]);
        }
    }

    function cekUbahNoseri(Request $request)
    {
        $data = JadwalRakitNoseri::where('noseri', $request->noseri)->get()->count();
        if ($data > 0) {
            return response()->json(['msg' => 'Noseri ' . $request->noseri . ' Sudah Terdaftar', 'error' => true]);
        } else {
        }
    }

    function storeRakitNoseri(Request $request)
    {
        // dd($request->all());
        $cek_seri = JadwalRakitNoseri::where('noseri', $request->noseri)->get();
        if (count($cek_seri) == 0) {
            foreach ($request->noseri as $key => $value) {
                if (isset($value)) {
                    $seri = new JadwalRakitNoseri();
                    $seri->date_in = $request->tgl_perakitan;
                    $seri->jadwal_id = $request->jadwal_id;
                    $seri->noseri = strtoupper($value);
                    $seri->status = 11;
                    $seri->created_by = $request->userid;
                    $seri->save();

                    $d = JadwalPerakitan::find($request->jadwal_id);
                    $jj = JadwalRakitNoseri::where('jadwal_id', $request->jadwal_id)->get()->count();
                    if ($d->jumlah == $jj) {
                        $d->status_tf = 15;
                        $d->no_bppb = strtoupper($request->no_bppb);
                        $d->filled_by = $request->userid;
                        $d->save();
                    } else {
                        $d->status_tf = 12;
                        $d->no_bppb = strtoupper($request->no_bppb);
                        $d->filled_by = $request->userid;
                        $d->save();
                    }
                }
            }

            return response()->json(['msg' => 'Successfully']);
        } else {
            return response()->json(['msg' => 'Noseri Sudah Ada, Silahkan Gunakan yang lain.']);
        }

    }

    function closeRakit(Request $request)
    {
        JadwalPerakitan::find($request->jadwal_id)->update(['keterangan' => $request->keterangan, 'status_tf' => 14]);

        activity('Produksi')
            ->withProperties(['keterangan' => $request->keterangan])
            ->log('Finish Perakitan '.$request->jadwal_id.' ');
        return response()->json(['msg' => 'Data Berhasil disimpan']);
    }

    function closeTransfer(Request $request)
    {
        JadwalPerakitan::find($request->jadwal_id)->update(['keterangan_transfer' => $request->keterangan_transfer, 'status_tf' => 14]);

        activity('Produksi')
            ->withProperties(['keterangan_transfer' => $request->keterangan_transfer])
            ->log('Finish Transfer Perakitan '.$request->jadwal_id.' ');
        return response()->json(['msg' => 'Data Berhasil disimpan']);
    }

    function getHeaderSeri($id)
    {
        $jadwal = JadwalPerakitan::find($id);
        $gdg = GudangBarangJadi::find($jadwal->produk_id);

        return response()->json([
            'bppb' => $jadwal->no_bppb == null ? '-' : $jadwal->no_bppb,
            'produk' => $gdg->produk->nama . ' ' . $gdg->nama,
            'kategori' => $gdg->produk->KelompokProduk->nama,
            'jumlah' => $jadwal->jumlah . ' ' . $gdg->satuan->nama,
            'start' => Carbon::parse($jadwal->tanggal_mulai)->isoFormat('D MMM YYYY'),
            'end' => Carbon::parse($jadwal->tanggal_selesai)->isoFormat('D MMM YYYY'),
        ]);
    }

    function historySeri($id, $dd, $rakit)
    {
        $data = JadwalRakitNoseri::whereHas('header', function ($q) use ($id) {
            $q->where('produk_id', $id);
        })->whereRaw("date_format(waktu_tf, '%Y-%m-%d %H:%i') = ?", [$dd])->whereRaw("date_format(date_in, '%Y-%m-%d %H:%i') = ?", [$rakit])->get();
        return datatables()->of($data)
            ->addColumn('checkbox', function ($d) {
                return '<input type="checkbox" name="noseri[]" id="noseri" value="' . $d->id . '" class="cb-child">';
            })
            ->addColumn('no_seri', function ($d) {
                return $d->noseri;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    function detailSeri1($id, $jadwal)
    {
        $data = JadwalRakitNoseri::whereHas('header', function ($q) use ($id) {
            $q->where('produk_id', $id);
        })->whereNull('waktu_tf')->where('jadwal_id', $jadwal)->get();
        return datatables()->of($data)
            ->addColumn('no_seri', function ($d) {
                return $d->noseri;
            })
            ->addColumn('id', function ($d) {
                return $d->id;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    function kirimseri(Request $request)
    {
        // dd($request->all());
        $header = new TFProduksi();
        $header->tgl_masuk = $request->tgl_transfer;
        $header->dari = 17;
        $header->ke = 13;
        $header->jenis = 'masuk';
        $header->created_at = Carbon::now();
        $header->created_by = $request->userid;
        $header->save();

        $detail = new TFProduksiDetail();
        $detail->t_gbj_id = $header->id;
        $detail->gdg_brg_jadi_id = $request->gbj_id;
        $detail->qty = count($request->noseri);
        $detail->jenis = 'masuk';
        $detail->created_at = Carbon::now();
        $detail->created_by = $request->userid;
        $detail->save();

        $check_array = $request->noseri;
        foreach ($request->noseri as $key => $value) {
            if (in_array($request->noseri[$key], $check_array)) {
                $seri = new NoseriBarangJadi();
                $seri->dari = 17;
                $seri->ke = $request->tujuan;
                $seri->gdg_barang_jadi_id = $request->gbj_id;
                $seri->noseri = $request->noseri[$key];
                $seri->jenis = 'MASUK';
                $seri->is_aktif = 0;
                $seri->created_at = Carbon::now();
                $seri->created_by = $request->userid;
                $seri->save();

                $serit = new NoseriTGbj();
                $serit->t_gbj_detail_id = $detail->id;
                $serit->noseri_id = $seri->id;
                $serit->layout_id = 1;
                $serit->jenis = 'MASUK';
                $serit->created_at = Carbon::now();
                $serit->created_by = $request->userid;
                $serit->save();
            }
            JadwalRakitNoseri::where('jadwal_id', $request->jadwal_id)->whereIn('noseri', [$request->noseri[$key]])->update(['waktu_tf' => $request->tgl_transfer, 'status' => 14, 'transfer_by' => $request->userid]);
        }

        // rubah logic
        $sdh_terkirim = JadwalRakitNoseri::whereHas('header', function ($q) use ($request) {
            $q->where('produk_id', $request->gbj_id);
        })->where('status', 14)->get()->count();
        $blm_terkirim = JadwalRakitNoseri::whereHas('header', function ($q) use ($request) {
            $q->where('produk_id', $request->gbj_id);
        })->where('status', 11)->get()->count();
        $sdh_terisi = JadwalRakitNoseri::whereHas('header', function ($q) use ($request) {
            $q->where('produk_id', $request->gbj_id);
        })->get()->count();
        $total_rakit = JadwalPerakitan::find($request->jadwal_id);
        $now = intval($total_rakit->jumlah - $sdh_terkirim);
        if ($now == $total_rakit->jumlah) {
            // return 'a';
            $total_rakit->status_tf = 14;
            $total_rakit->filled_by = $request->userid;
            $total_rakit->save();
        } elseif ($total_rakit->jumlah == $sdh_terkirim) {
            // return 'b';
            $total_rakit->status_tf = 14;
            $total_rakit->filled_by = $request->userid;
            $total_rakit->save();
        } elseif ($sdh_terisi != $total_rakit->jumlah) {
            $total_rakit->status_tf = 12;
            $total_rakit->filled_by = $request->userid;
            $total_rakit->save();
        } else {
            // return 'c';
            $total_rakit->status_tf = 13;
            $total_rakit->filled_by = $request->userid;
            $total_rakit->save();
        }

        activity('Produksi')
            ->causedBy($request->userid)
            ->withProperties(['waktu_tf' => $request->tgl_transfer, 'transfer_by' => $request->userid, 'noseri' => [$request->noseri]])
            ->log('Transfer Noseri Ke Gudang sejumlah '.count($request->noseri).' ');
        return response()->json(['msg' => 'Berhasil Transfer ke Gudang']);
    }

    function deleteNoseri(Request $request)
    {
        $cek_data = JadwalRakitNoseri::where('id', $request->noseriid)->where('jadwal_id', $request->jadwal_id)->get()->count();
        if ($cek_data > 0) {
            $a = JadwalRakitNoseri::where('id', $request->noseriid)->where('jadwal_id', $request->jadwal_id)->first();
            activity('Produksi')
                ->withProperties(['noseri' => $a->noseri])
                ->log('Hapus Noseri');

            JadwalRakitNoseri::where('id', $request->noseriid)->where('jadwal_id', $request->jadwal_id)->delete();
            JadwalPerakitan::find($request->jadwal_id)->update(['status_tf' => 12]);
            return response()->json(['msg' => 'Data Berhasil Dihapus']);
        } else {
            return response()->json(['error' => 'Data Tidak Ada']);
        }
    }

    function updateNoseri(Request $request)
    {
        $cek_data = JadwalRakitNoseri::where('id', $request->noseriid)->where('jadwal_id', $request->jadwal_id)->get()->count();
        if ($cek_data > 0) {
            JadwalRakitNoseri::where('id', $request->noseriid)->where('jadwal_id', $request->jadwal_id)->update(['noseri' => $request->noseri]);

            activity('Produksi')
                ->withProperties(['noseri' => $request->noseri])
                ->log('Ubah Noseri');
            return response()->json(['msg' => 'Data Berhasil diubah']);
        } else {
            return response()->json(['error' => 'Data Tidak Ada']);
        }
    }

    function deleteAllSeri(Request $request)
    {
        $a = JadwalRakitNoseri::whereIn('noseri', $request->noseri)->where('jadwal_id', $request->jadwal_id)->get();
        $aa = [];
        foreach($a as $a) {
            $aa[] = $a->noseri;
        }
        // return $aa;
        activity('Produksi')
            ->withProperties(['noseri' => [$aa]])
            ->log('Hapus Noseri');

        // dd($request->all());
        JadwalRakitNoseri::whereIn('noseri', $request->noseri)->where('jadwal_id', $request->jadwal_id)->delete();
        JadwalPerakitan::find($request->jadwal_id)->update(['status_tf' => 12]);
        return response()->json(['msg' => 'Sukses']);
    }

    // riwayat rakit
    function h_rakit()
    {
        $data = JadwalPerakitan::where('status_tf', 14)->get()->count('produk_id');
        return $data;
    }

    function h_unit()
    {
        $data = JadwalPerakitan::with('noseri', 'produk.produk')->where('status_tf', 14)->get();
        return $data;
    }

    function his_rakit()
    {
        $rakit = JadwalPerakitan::where('status_tf', 14)->get()->count('produk_id');
        $unit = JadwalPerakitan::where('status_tf', 14)->get()->sum('jumlah');
        $data = JadwalPerakitan::where('status_tf', 14)->get();
        $detail = JadwalPerakitan::with('noseri', 'produk.produk')->where('status_tf', 14)->get();

        return view('page.produksi.riwayat_perakitan', compact('rakit', 'unit', 'data', 'detail'));
    }

    function header_his_rakit($id)
    {
        // $detail = JadwalPerakitan::with('noseri', 'produk.produk')->where('status_tf', 14)->where('produk_id', $id)->get();
        $detail = JadwalRakitNoseri::select('jadwal_rakit_noseri.jadwal_id', 'jadwal_rakit_noseri.date_in', 'jadwal_rakit_noseri.created_at', 'jadwal_rakit_noseri.waktu_tf', 'jadwal_perakitan.produk_id', DB::raw('count(jadwal_id) as jml'), 'jadwal_perakitan.no_bppb')
            ->join('jadwal_perakitan', 'jadwal_perakitan.id', '=', 'jadwal_rakit_noseri.jadwal_id')
            ->groupBy('jadwal_rakit_noseri.jadwal_id')
            ->groupBy('jadwal_rakit_noseri.date_in')
            ->groupBy('jadwal_rakit_noseri.waktu_tf')
            ->whereNotNull('jadwal_rakit_noseri.waktu_tf')
            ->where('jadwal_perakitan.status_tf', 14)
            ->where('jadwal_perakitan.produk_id', $id)
            ->get();
        $a = [];
        foreach ($detail as $d) {
            $a[] = [
                'day_rakit' => Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->isoFormat('dddd, D MMMM Y'),
                'time_rakit' => Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->format('H:i'),
                'day_kirim' => Carbon::createFromFormat('Y-m-d H:i:s', $d->waktu_tf)->isoFormat('dddd, D MMMM Y'),
                'time_kirim' => Carbon::createFromFormat('Y-m-d H:i:s', $d->waktu_tf)->format('H:i'),
                'bppb' => $d->no_bppb == null ? '-' : $d->no_bppb,
                // 'produk' => $d->produk->produk->nama . ' ' . $d->produk->nama,
                'produk' => $d->produk_id,
                'jml' => $d->jumlah . ' Unit',
            ];
        }
        return $a;
    }

    function ajax_history_rakit()
    {
        $d = JadwalRakitNoseri::select('jadwal_rakit_noseri.jadwal_id', 'jadwal_rakit_noseri.date_in', 'jadwal_rakit_noseri.created_at', 'jadwal_rakit_noseri.waktu_tf', 'jadwal_perakitan.produk_id', DB::raw('count(jadwal_id) as jml'), 'jadwal_perakitan.no_bppb')
            ->join('jadwal_perakitan', 'jadwal_perakitan.id', '=', 'jadwal_rakit_noseri.jadwal_id')
            ->groupBy('jadwal_rakit_noseri.jadwal_id')
            ->groupBy(DB::raw("date_format(jadwal_rakit_noseri.date_in, '%Y-%m-%d %H:%i')"))
            ->groupBy(DB::raw("date_format(jadwal_rakit_noseri.waktu_tf, '%Y-%m-%d %H:%i')"))
            ->whereNotNull('jadwal_rakit_noseri.waktu_tf')
            ->get()->sortByDesc('date_in');
        return datatables()->of($d)
            ->addColumn('day_rakit', function ($d) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->isoFormat('dddd, D MMMM Y');
            })
            ->addColumn('day_kirim', function ($d) {
                if (isset($d->waktu_tf)) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $d->waktu_tf)->isoFormat('dddd, D MMMM Y');
                } else {
                    return '-';
                }
            })
            ->addColumn('time_rakit', function ($d) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->format('H:i');
            })
            ->addColumn('time_kirim', function ($d) {
                if (isset($d->waktu_tf)) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $d->waktu_tf)->format('H:i');
                } else {
                    return '-';
                }
            })
            ->addColumn('bppb', function ($d) {
                return $d->no_bppb == null ? '-' : $d->no_bppb;
            })
            ->addColumn('produk', function ($d) {
                $a = GudangBarangJadi::find($d->produk_id);
                return $a->produk->nama . ' ' . $a->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->jml . ' Unit';
            })
            ->addColumn('action', function ($d) {
                return '<button class="btn btn-outline-secondary detail" data-rakit="' . Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->format('Y-m-d H:i') . '" data-tf="' . Carbon::createFromFormat('Y-m-d H:i:s', $d->waktu_tf)->format('Y-m-d H:i') . '" data-jml="' . $d->jml . '" data-id="' . $d->produk_id . '"><i class="far fa-eye"></i> Detail</button>';
            })->addColumn('day_rakit_filter', function ($d) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->isoFormat('D-MM-Y');
            })->addColumn('day_kirim_filter', function ($d) {
                if (isset($d->waktu_tf)) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $d->waktu_tf)->isoFormat('D-MM-Y');
                } else {
                    return '-';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function product_his_rakit()
    {
        $d = JadwalRakitNoseri::select('jadwal_rakit_noseri.jadwal_id', 'jadwal_rakit_noseri.date_in', 'jadwal_rakit_noseri.created_at', 'jadwal_rakit_noseri.waktu_tf', 'jadwal_perakitan.produk_id', DB::raw('count(jadwal_id) as jml'))
            ->join('jadwal_perakitan', 'jadwal_perakitan.id', '=', 'jadwal_rakit_noseri.jadwal_id')
            ->groupBy('jadwal_rakit_noseri.jadwal_id')
            ->groupBy('jadwal_rakit_noseri.date_in')
            ->groupBy('jadwal_rakit_noseri.waktu_tf')
            ->whereNotNull('jadwal_rakit_noseri.waktu_tf')
            ->get();

        $produk = [];
        foreach ($d as $item) {
            $a = GudangBarangJadi::find($item->produk_id);
            array_push($produk, $a->produk->nama);
        }
        $data = array_unique($produk);
        return response()->json($data);
    }

    // gbj
    function terimaseri(Request $request)
    {

        // dd($request->seri);
        $seri = NoseriTGbj::whereIn('id', $request->seri)->get()->toArray();

        $i = 0;
        foreach ($seri as $s) {
            // return $s;
            $i++;
            for ($k = 0; $k < count($request->layout); $k++) {
                // print_r(NoseriTGbj::where('id', $request->seri[$k])->get());
                NoseriTGbj::where('id', $request->seri[$k])->update(['status_id' => 3, 'state_id' => 16, 'layout_id' => json_decode($request->layout[$k], true)]);
            }

            NoseriBarangJadi::find($s['noseri_id'])->update(['is_aktif' => 1]);

            // $hid = NoseriTGbj::find($s['id']);
            // return TFProduksiDetail::find($hid->t_gbj_detail_id)->get()->pluck('gdg_brg_jadi_id');
            // $aa = TFProduksiDetail::where('gdg_brg_jadi_id', $hid-)
            // TFProduksiDetail::find($hid->t_gbj_detail_id)->update(['status_id' => 3, 'state_id' => 16]);

            $gid = NoseriBarangJadi::where('id', $s['noseri_id'])->get();
            foreach ($gid as $g) {
                $gdg = GudangBarangJadi::where('id', $g->gdg_barang_jadi_id)->get()->toArray();
                foreach ($gdg as $vv) {
                    $i++;
                    $vv['stok'] = $vv['stok'] + count($gid);
                    GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok'], 'updated_by' => $request->userid]);
                }
            }
        }

        activity('GBJ')
                ->causedBy($request->userid)
                ->withProperties(['produk' => $g->gdg_barang_jadi_id, 'jumlah' => count($request->seri)])
                ->log('Terima Seri');


        return response()->json(['msg' => 'Successfully']);
    }

    // versi 2
    function getCountProdukBySO()
    {
        $data = GudangBarangJadi::Has('DetailPesananProduk.DetailPesanan.Pesanan')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($data) {
                if (!empty($data->nama)) {
                    return $data->Produk->nama . " - <b>" . $data->nama . "</b>";
                } else {
                    return $data->Produk->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                // get jumlah produk semua so
                $dd = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($d) {
                    $q->whereIn('gudang_barang_jadi_id', [$d->id]);
                })->select(DB::raw('sum(jumlah) as jumlah'))->get();
                // get jumlah yang sudah dirakit
                $rakit = JadwalRakitNoseri::whereHas('header', function ($q) use ($d) {
                    $q->whereIn('produk_id', [$d->id]);
                })->select(DB::raw('count(jadwal_id) as jumlah'))->get();
                return 'Jumlah ' . $dd . ' Rakit ' . $rakit;
            })
            ->addColumn('aksi', function ($d) {
                return 'Aksi';
            })
            ->rawColumns(['aksi', 'jumlah'])
            ->make(true);
    }

    function detailCountProdukBySO($id)
    {
        $Ekatalog = collect(Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->has('Ekatalog')->whereNotNull('no_po')->get());
        $Spa = collect(Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->has('Spa')->whereNotNull('no_po')->get());
        $Spb = collect(Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
            $q->where('id', $id);
        })->has('Spb')->whereNotNull('no_po')->get());
        $data = $Ekatalog->merge($Spa)->merge($Spb);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($d) {
                if (empty($d->so)) {
                    return '-';
                } else {
                    return $d->so;
                }
            })
            ->addColumn('batas_max', function ($d) {
                if (isset($d->Ekatalog->tgl_kontrak)) {
                    return $d->Ekatalog->tgl_kontrak;
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                for ($i = 1; $i < count($name); $i++) {
                    if ($name[1] == 'EKAT') {
                        return $data->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->Spb->Customer->nama;
                    }
                }
            })

            ->make(true);
    }

    // dashboard produksi
    public function dashboard(Request $request)
    {
        $dateCurrent = Carbon::now()->subDays(5);
        $dateFuture = Carbon::now()->addDays(5);
        $period = CarbonPeriod::create($dateCurrent, $dateFuture);
        $data = [];
        foreach ($period as $date) {
            array_push($data, $date->format('d-m-Y'));
        }

        return response()->json($data);
    }

    public function getAllProduk()
    {
        $produk = JadwalPerakitan::with('Produk.produk')->get()->pluck('Produk.produk.nama', 'produk_id');
        return response()->json($produk);
    }

    function getGrafikProduk(Request $request)
    {
        $data = DB::table('prd_dashboard_view')
            ->where([
                ['filter', '=', $request->filter],
                ['filter', '<>', null],
            ])
            ->get();
        $arr = [];
        foreach ($data as $d) {
            $arr[] = [
                'tgl_rakit' => $d->tgl_rakit == null ? '-' : $d->tgl_rakit,
                'kode' => $d->produk_id == null ? '-' : $d->produk_id,
                'nama' => $d->prd_nm == null ? '-' : $d->prd_nm,
                'total' => $d->jml == 0 ? 0 : $d->jml,
                'filter' => $d->filter == null ? '-' : $d->filter,
            ];
        }

        return response()->json([
            'data' => $arr,
        ]);
    }

    function ajax_perproduk()
    {
    //    $data = JadwalRakitNoseri::whereHas('header', function($q) {
    //        $q->groupBy('produk_id');
    //    })->with('header')->groupBy('jadwal_id')->get();
        $data = JadwalPerakitan::groupBy('produk_id')->groupBy('no_bppb')->has('noseri')->get();
       return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_bppb', function($d) {
                return $d->no_bppb == null ? '-' : $d->no_bppb;
            })
            ->addColumn('produk', function($d) {
                if (isset($d->produk_id)) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                }
            })
            ->addColumn('aksi', function($d) {
                return $d->produk_id;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    function detail_perproduk($id)
    {
        $d = JadwalRakitNoseri::select('jadwal_rakit_noseri.jadwal_id', 'jadwal_rakit_noseri.date_in', 'jadwal_rakit_noseri.created_at', 'jadwal_rakit_noseri.waktu_tf', 'jadwal_perakitan.produk_id', DB::raw('count(jadwal_id) as jml'), 'jadwal_perakitan.no_bppb')
        ->join('jadwal_perakitan', 'jadwal_perakitan.id', '=', 'jadwal_rakit_noseri.jadwal_id')
        ->groupBy('jadwal_rakit_noseri.jadwal_id')
        ->groupBy(DB::raw("date_format(jadwal_rakit_noseri.date_in, '%Y-%m-%d %H:%i')"))
        ->groupBy(DB::raw("date_format(jadwal_rakit_noseri.waktu_tf, '%Y-%m-%d %H:%i')"))
        // ->whereNotNull('jadwal_rakit_noseri.waktu_tf')
        ->where('produk_id', $id)
        ->get()->sortByDesc('date_in');
        return datatables()->of($d)
        ->addColumn('day_rakit', function ($d) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->isoFormat('dddd, D MMMM Y');
        })
        ->addColumn('day_kirim', function ($d) {
            if (isset($d->waktu_tf)) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $d->waktu_tf)->isoFormat('dddd, D MMMM Y');
            } else {
                return '-';
            }
        })
        ->addColumn('time_rakit', function ($d) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->format('H:i');
        })
        ->addColumn('time_kirim', function ($d) {
            if (isset($d->waktu_tf)) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $d->waktu_tf)->format('H:i');
            } else {
                return '-';
            }
        })
        ->addColumn('bppb', function ($d) {
            return $d->no_bppb == null ? '-' : $d->no_bppb;
        })
        ->addColumn('jml', function ($d) {
            return $d->jml . ' Unit';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    function ajax_sisa_transfer()
    {
        $data = JadwalPerakitan::whereNotNull('keterangan_transfer')->orWhereNotNull('keterangan')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('periode', function ($d) {
                if (isset($d->tanggal_mulai)) {
                    return Carbon::parse($d->tanggal_mulai)->isoFormat('MMMM');
                } else {
                    return '-';
                }
            })
            ->addColumn('no_bppb', function ($d) {
                return $d->no_bppb == null ? '-' : $d->no_bppb;
            })
            ->addColumn('start', function ($d) {
                if (isset($d->tanggal_mulai)) {
                    return Carbon::parse($d->tanggal_mulai)->isoFormat('dddd, D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('end', function ($d) {
                if (isset($d->tanggal_selesai)) {
                    return Carbon::parse($d->tanggal_selesai)->isoFormat('dddd, D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('produk', function ($d) {
                if (isset($d->produk->nama)) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                } else {
                    return $d->produk->produk->nama;
                }
            })
            ->addColumn('jml_rakit', function ($d) {
                return  $d->jumlah . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('jml_sisa', function ($d) {
                $seri = JadwalRakitNoseri::where('jadwal_id', $d->id)->where('status', 14)->get();
                $c = count($seri);
                $seri_all = JadwalRakitNoseri::where('jadwal_id', $d->id)->get();
                $c_all = count($seri_all);
                $seri_belum = JadwalRakitNoseri::where('jadwal_id', $d->id)->where('status', 11)->get()->count();

                return '
                <span class="badge badge-success">Sisa Kirim : ' . intval($seri_belum) . ' Unit</span>
                <br><span class="badge badge-warning">Sisa Rakit : ' . intval($d->jumlah - $c_all) . ' Unit</span>
                ';
            })
            ->addColumn('remark', function($d){
                if (isset($d->keterangan)) {
                    return $d->keterangan;
                } else {
                    return $d->keterangan_transfer;
                }
            })
            ->addColumn('aksi', function($d){
                $seri_belum = JadwalRakitNoseri::where('jadwal_id', $d->id)->where('status', 11)->get()->count();
                if (isset($d->keterangan)) {
                    return '
                    <a data-toggle="modal" data-target="#rakitmodal" class="transferlain" data-attr=""  data-id="' . $d->id . '" data-jml="' . $seri_belum . '" data-prd="' . $d->produk_id . '" data-ket="'.$d->keterangan.'">
                        <button class="btn btn-outline-secondary"><i class="far fa-eye"></i> Detail</button>
                    </a>
                    ';
                } else {
                    return '
                    <a data-toggle="modal" data-target="#rakitmodal" class="transferlain" data-attr=""  data-id="' . $d->id . '" data-jml="' . $seri_belum . '" data-prd="' . $d->produk_id . '" data-ket="'.$d->keterangan_transfer.'">
                        <button class="btn btn-outline-secondary"><i class="far fa-eye"></i> Detail</button>
                    </a>
                    ';
                }
                // return '
                //     <a data-toggle="modal" data-target="#rakitmodal" class="transferlain" data-attr=""  data-id="' . $d->id . '" data-jml="' . $seri_belum . '" data-prd="' . $d->produk_id . '" data-ket="'.$d->keterangan.'">
                //         <button class="btn btn-outline-secondary"><i class="far fa-eye"></i> Detail</button>
                //     </a>
                //     ';
            })
            ->addColumn('start_filter', function ($d) {
                if (isset($d->tanggal_mulai)) {
                    return $d->tanggal_mulai;
                } else {
                    return '-';
                }
            })
            ->addColumn('end_filter', function ($d) {
                if (isset($d->tanggal_selesai)) {
                    return $d->tanggal_selesai;
                } else {
                    return '-';
                }
            })
            ->rawColumns(['aksi', 'jml_rakit', 'jml_sisa'])
            ->make(true);
    }

    function detail_sisa_kirim(Request $request)
    {
        $data = JadwalRakitNoseri::where('jadwal_id', $request->id)->where('status', 11)->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('noseri', function($d) {
                return $d->noseri;
            })
            ->addColumn('tgl_masuk', function($d){
                return Carbon::parse($d->date_in)->isoFormat('dddd, D MMM YYYY hh:ii:ss');
            })
            ->addColumn('waktu_masuk', function($d){
                return Carbon::parse($d->date_in)->isoFormat('hh:ii:ss');
            })
            ->addColumn('remark', function($d){
                if (isset($d->header->keterangan)) {
                    return $d->header->keterangan;
                } else {
                    return $d->header->keterangan_transfer;
                }
            })
            ->make(true);
    }
}
