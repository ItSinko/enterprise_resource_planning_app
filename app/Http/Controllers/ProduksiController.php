<?php

namespace App\Http\Controllers;

use App\Exports\NoseriRakitExport;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Divisi;
use App\Models\GudangBarangJadi;
use App\Models\GudangBarangJadiHis;
use App\Models\JadwalPerakitan;
use App\Models\JadwalRakitNoseri;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriTGbj;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\SystemLog;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProduksiController extends Controller
{
    function CreateTFItem(Request $request)
    {
        try {
            // dd($request->all());
            //code...
            foreach($request->data as $key => $value) {
                $header = TFProduksi::create([
                    'tgl_keluar' => Carbon::now(),
                    'ke' => $value['tujuan'],
                    'deskripsi' => $value['desk'],
                    'jenis' => 'keluar',
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                $detail = TFProduksiDetail::create([
                    't_gbj_id' => $header->id,
                    'gdg_brg_jadi_id' => $key,
                    'qty' => $value['qty'],
                    'jenis' => 'keluar',
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                foreach($value['noseri'] as $k => $v) {
                    NoseriTGbj::create([
                        't_gbj_detail_id' => $detail->id,
                        'noseri_id' => $v,
                        'layout_id' => 1,
                        'status_id' => 2,
                        'jenis' => 'keluar',
                        'created_at' => Carbon::now(),
                        'created_by' => $request->userid
                    ]);

                    NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $value['tujuan']]);
                    // NoseriLog::create([
                    //     'gbj_id' => $value['prd'],
                    //     'noseri_id' => $v,
                    //     'nonso' => $header->id,
                    //     'log_id' => $key,
                    //     'created_by' => $request->userid
                    // ]);
                }
            }

            $obj = [
                'data' => $request->data,
                'tgl_keluar' => Carbon::now()
            ];

            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Pengeluaran Tanpa SO',
                'response' => json_encode($obj),
                'user_id' => $request->userid
            ]);

            return response()->json(['msg' => 'Data Berhasil ditransfer', 'error' => false,]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function TfbySO(Request $request)
    {
        try {
            //code...
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function updateRancangSO(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function updateFinalSO(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }
    // get
    function getNoseri(Request $request)
    {
        try {
            $data = NoseriBarangJadi::where('gdg_barang_jadi_id', $request->gbj)->where('is_ready', 0)->where('is_aktif', 1)->get();
        $i = 0;
        return datatables()->of($data)
            ->addColumn('checkbox', function ($d) use ($i) {
                $i++;
                if ($d->is_change == 0) {
                    # code...
                    return '<input type="checkbox" class="cb-child" name="noseri_id[][' . $i . ']"  value="' . $d->id . '" disabled title="Noseri Tidak Bisa Digunakan">';
                } else {
                    # code...
                    return '<input type="checkbox" class="cb-child" name="noseri_id[][' . $i . ']"  value="' . $d->id . '">';
                }
                // return '<input type="checkbox" class="cb-child" name="noseri_id[][' . $i . ']" id="" value="' . $data->id . '">';
            })
            ->addColumn('noseri', function ($data) {
                return $data->noseri;
            })
            ->addColumn('ids', function ($d) {
                return $d->id;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getSOCek()
    {
        try {
            $datax = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select('p.id','p.so', 'p.no_po', 'p.log_id',
            DB::raw('count(dpp.gudang_barang_jadi_id)'),
            DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
            DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
            DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
            'ms.nama as log_nama',
            DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi"))
            ->leftJoin(DB::raw('detail_pesanan dp'),'dpp.detail_pesanan_id','=','dp.id')
            ->leftJoin(DB::raw('pesanan p'),'dp.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('ekatalog e'),'e.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('provinsi p2'),'p2.id','=','e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'),'ms.id','=','p.log_id')
            ->leftJoin(DB::raw('spa s'),'s.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('spb s2'),'s2.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('customer c_ekat'),'c_ekat.id','=','e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'),'c_spa.id','=','s.customer_id')
            ->leftJoin(DB::raw('customer c_spb'),'c_spb.id','=','s2.customer_id')
            ->whereNotIn('p.log_id',[7,10])
            ->groupBy('p.id')
            ->havingRaw('sum(case when dpp.status_cek is null then 1 else 0 end) = ?',[0])
            ->get();

            return datatables()->of($datax)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('po', function ($data) {
                    return $data->no_po;
                })
                ->addColumn('logs', function($d) {
                    if ($d->log_id == 9) {
                        $ax = "<span class='badge badge-pill badge-secondary'>".$d->log_nama."</span>";
                    } else if ($d->log_id == 6) {
                        $ax = "<span class='badge badge-pill badge-warning'>".$d->log_nama."</span>";
                    } elseif ($d->log_id == 8) {
                        $ax = "<span class='badge badge-pill badge-info'>".$d->log_nama."</span>";
                    } elseif ($d->log_id == 11) {
                        $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                    } else {
                        $ax = "<span class='badge badge-pill badge-danger'>".$d->log_nama."</span>";
                    }

                    return $ax;
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('batas_out', function ($d) {
                    if ($d->batas) {
                        return $d->batas;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($data) {
                    $x = explode('/', $data->so);
                    if ($data->total_cek == $data->total_uncek) {
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

                        if($data->log_id == 20) {
                            for ($i = 1; $i < count($x); $i++) {
                                if ($x[1] == 'EKAT') {
                                    return '';
                                } elseif ($x[1] == 'SPA') {
                                    return '';
                                } elseif ($x[1] == 'SPB') {
                                    return '';
                                }
                            }
                        }
                    } elseif ($data->total_cek != $data->total_uncek) {
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

                        if($data->log_id == 20) {
                            for ($i = 1; $i < count($x); $i++) {
                                if ($x[1] == 'EKAT') {
                                    return '';
                                } elseif ($x[1] == 'SPA') {
                                    return '';
                                } elseif ($x[1] == 'SPB') {
                                    return '';
                                }
                            }
                        }
                    } else {
                        if ($data->log_id == 9) {
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
                        }

                        if($data->log_id == 20) {
                            for ($i = 1; $i < count($x); $i++) {
                                if ($x[1] == 'EKAT') {
                                    return '';
                                } elseif ($x[1] == 'SPA') {
                                    return '';
                                } elseif ($x[1] == 'SPB') {
                                    return '';
                                }
                            }
                        }
                    }
                })
                ->rawColumns(['button', 'status', 'action', 'status1', 'status_prd', 'button_prd', 'logs'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getSOCekBelum()
    {
        try {
            $datax = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select('p.id','p.so', 'p.no_po', 'p.log_id',
            DB::raw('count(dpp.gudang_barang_jadi_id)'),
            DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
            DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
            DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
            'ms.nama as log_nama',
            DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi"))
            ->leftJoin(DB::raw('detail_pesanan dp'),'dpp.detail_pesanan_id','=','dp.id')
            ->leftJoin(DB::raw('pesanan p'),'dp.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('ekatalog e'),'e.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('provinsi p2'),'p2.id','=','e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'),'ms.id','=','p.log_id')
            ->leftJoin(DB::raw('spa s'),'s.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('spb s2'),'s2.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('customer c_ekat'),'c_ekat.id','=','e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'),'c_spa.id','=','s.customer_id')
            ->leftJoin(DB::raw('customer c_spb'),'c_spb.id','=','s2.customer_id')
            ->whereNotIn('p.log_id',[10,20])
            ->whereNotNull('p.no_po')
            ->groupBy('p.id')
            ->havingRaw('sum(case when dpp.status_cek is null then 1 else 0 end) != ?',[0])
            ->havingRaw('sum(case when dpp.status_cek is null then 1 else 0 end) != ?',['sum(case when dpp.status_cek = 4 then 1 else 0 end)'])
            ->get();
            return datatables()->of($datax)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('po', function ($data) {
                    return $data->no_po;
                })
                ->addColumn('logs', function($d) {
                    if ($d->log_id == 9) {
                        $ax = "<span class='badge badge-pill badge-secondary'>".$d->log_nama."</span>";
                    } else if ($d->log_id == 6) {
                        $ax = "<span class='badge badge-pill badge-warning'>".$d->log_nama."</span>";
                    } elseif ($d->log_id == 8) {
                        $ax = "<span class='badge badge-pill badge-info'>".$d->log_nama."</span>";
                    } elseif ($d->log_id == 11) {
                        $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                    } else {
                        $ax = "<span class='badge badge-pill badge-danger'>".$d->log_nama."</span>";
                    }

                    return $ax;
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('batas_out', function ($d) {
                    if ($d->batas) {
                        return Carbon::parse($d->batas)->isoFormat('D MMMM YYYY');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($data) {
                    $x = explode('/', $data->so);
                    if ($data->total_cek == $data->total_uncek) {
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

                        if($data->log_id == 20) {
                            for ($i = 1; $i < count($x); $i++) {
                                if ($x[1] == 'EKAT') {
                                    return '';
                                } elseif ($x[1] == 'SPA') {
                                    return '';
                                } elseif ($x[1] == 'SPB') {
                                    return '';
                                }
                            }
                        }
                    } elseif ($data->total_cek != $data->total_uncek) {
                        if($data->log_id == 20) {
                            for ($i = 1; $i < count($x); $i++) {
                                if ($x[1] == 'EKAT') {
                                    return '';
                                } elseif ($x[1] == 'SPA') {
                                    return '';
                                } elseif ($x[1] == 'SPB') {
                                    return '';
                                }
                            }
                        }

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
                ->rawColumns(['button', 'status', 'action', 'status1', 'status_prd', 'button_prd', 'logs'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }


    }

    function getOutSO()
    {
        try {
            $data = Pesanan::with(['Ekatalog.Customer', 'Spa.Customer', 'Spb.Customer'])
                ->whereIn('id', function($q){
                $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('t_gbj', 't_gbj.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.t_gbj_id', '=', 't_gbj.id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                ->groupBy('pesanan.id')
                ->havingRaw('count(t_gbj_noseri.id) < (select sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)
                from detail_pesanan
                join penjualan_produk on penjualan_produk.id = detail_pesanan.penjualan_produk_id
                join detail_penjualan_produk on detail_penjualan_produk.penjualan_produk_id = penjualan_produk.id
                where detail_pesanan.pesanan_id = pesanan.id)');
            })->addSelect(['tgl_kontrak' => function($q){
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 35 DAY))')
                ->from('ekatalog')
                ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                ->limit(1);
            }, 'count_qc' => function($q) {
                $q->selectRaw('count(t_gbj_noseri.id)')
                    ->from('t_gbj_noseri')
                    ->leftJoin('t_gbj_detail', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                    ->leftJoin('t_gbj', 't_gbj_detail.t_gbj_id', '=', 't_gbj.id')
                    ->whereColumn('t_gbj.pesanan_id', 'pesanan.id')
                    ->limit(1);
            }, 'count_pesanan' => function($q) {
                $q->selectRaw('sum(detail_pesanan.jumlah) * detail_penjualan_produk.jumlah')
                    ->from('detail_pesanan')
                    ->leftJoin('penjualan_produk', 'penjualan_produk.id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->leftJoin('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=' ,'penjualan_produk.id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->limit(1);
            }
            ])
            ->whereNotIn('pesanan.log_id', [7,10,20])
            // ->whereNotNull('pesanan.so')
            ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('progress', function($d) {
                    return '<span class="badge badge-info">QC: '.$d->count_qc.' ('.round(intval($d->count_qc / (intval($d->count_pesanan))) * 100,2).'%)</span> <br>
                            <span class="badge badge-warning">Gudang: '.intval(intval($d->count_pesanan) - $d->count_qc).' ('.round(intval(intval($d->count_pesanan) - $d->count_qc) / (intval($d->count_pesanan)) * 100,2).'%)</span>';
                })
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
                    if (isset($d->tgl_kontrak)) {
                        return Carbon::createFromFormat('Y-m-d', $d->tgl_kontrak)->isoFormat('D MMMM YYYY');
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
                                            </a>
                                            <a data-toggle="modal" data-target="#downloadtemplate" class="downloadtemplate" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '">
                                                <button class="btn btn-outline-dark btn-sm" type="button">
                                                    <i class="fas fa-download"></i>&nbsp;Template
                                                </button>
                                            </a>
                                            <a data-toggle="modal" data-target="#importtemplate" class="importtemplate" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '">
                                                <button class="btn btn-outline-info btn-sm" type="button">
                                                    <i class="fas fa-file-import"></i>&nbsp;Unggah
                                                </button>
                                            </a>';
                                } elseif ($x[1] == 'SPA') {
                                    return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-value="spa"  data-id="' . $data->id . '">
                                                <button class="btn btn-outline-primary btn-sm" type="button">
                                                    <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                                </button>
                                            </a>
                                            <a data-toggle="modal" data-target="#downloadtemplate" class="downloadtemplate" data-attr="" data-value="spa"  data-id="' . $data->id . '">
                                                <button class="btn btn-outline-dark btn-sm" type="button">
                                                    <i class="fas fa-download"></i>&nbsp;Template
                                                </button>
                                            </a>
                                            <a data-toggle="modal" data-target="#importtemplate" class="importtemplate" data-attr="" data-value="spa"  data-id="' . $data->id . '">
                                                <button class="btn btn-outline-info btn-sm" type="button">
                                                    <i class="fas fa-file-import"></i>&nbsp;Unggah
                                                </button>
                                            </a>';
                                } elseif ($x[1] == 'SPB') {
                                    return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-value="spb"  data-id="' . $data->id . '">
                                                <button class="btn btn-outline-primary btn-sm" type="button">
                                                    <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                                </button>
                                            </a>
                                            <a data-toggle="modal" data-target="#downloadtemplate" class="downloadtemplate" data-attr="" data-value="spb"  data-id="' . $data->id . '">
                                                <button class="btn btn-outline-dark btn-sm" type="button">
                                                    <i class="fas fa-download"></i>&nbsp;Template
                                                </button>
                                            </a>
                                            <a data-toggle="modal" data-target="#importtemplate" class="importtemplate" data-attr="" data-value="spb"  data-id="' . $data->id . '">
                                                <button class="btn btn-outline-info btn-sm" type="button">
                                                    <i class="fas fa-file-import"></i>&nbsp;Unggah
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
                                    </a>
                                    <a data-toggle="modal" data-target="#downloadtemplate" class="downloadtemplate" data-attr="" data-value="ekatalog"  data-id="' . $data->id . '">
                                                <button class="btn btn-outline-dark btn-sm" type="button">
                                                    <i class="fas fa-download"></i>&nbsp;Template
                                                </button>
                                            </a>
                                            ';
                            } elseif ($x[1] == 'SPA') {
                                return '<a data-toggle="modal" data-target="#editmodal" class="ubahmodal" data-attr="" data-value="spa"  data-id="' . $data->id . '">
                                            <button class="btn btn-outline-info btn-sm" type="button">
                                                <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                            </button>
                                        </a>
                                        <a data-toggle="modal" data-target="#downloadtemplate" class="downloadtemplate" data-attr="" data-value="spa"  data-id="' . $data->id . '">
                                                <button class="btn btn-outline-dark btn-sm" type="button">
                                                    <i class="fas fa-download"></i>&nbsp;Template
                                                </button>
                                            </a>
                                            ';
                            } elseif ($x[1] == 'SPB') {
                                return '<a data-toggle="modal" data-target="#editmodal" class="ubahmodal" data-attr="" data-value="spb"  data-id="' . $data->id . '">
                                            <button class="btn btn-outline-info btn-sm" type="button">
                                                <i class="fas fa-plus"></i>&nbsp;Siapkan Produk
                                            </button>
                                        </a>
                                        <a data-toggle="modal" data-target="#downloadtemplate" class="downloadtemplate" data-attr="" data-value="spb"  data-id="' . $data->id . '">
                                                <button class="btn btn-outline-dark btn-sm" type="button">
                                                    <i class="fas fa-download"></i>&nbsp;Template
                                                </button>
                                            </a>
                                            ';
                            }

            }
                    }
                })
                ->rawColumns(['button', 'status', 'progress'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }


    function getSOProduksi()
    {
        try {
            $data = Pesanan::
                    select('pesanan.so', 'pesanan.no_po', 'pesanan.log_id', 'ms.nama as log_nama',
                    DB::raw("case
                    when substring_index(substring_index(pesanan.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
                    when substring_index(substring_index(pesanan.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
                    when substring_index(substring_index(pesanan.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
                    when pesanan.so is null then c_ekat.nama
                end as divisi"), 'e.tgl_kontrak', 'pesanan.id', 'pesanan.tgl_po')
                    ->leftJoin('m_state as ms', 'ms.id', '=', 'pesanan.log_id')
                    ->leftJoin('ekatalog as e', 'e.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'e.customer_id')
                    ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
                    ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
                    ->leftJoin('provinsi as prov', 'prov.id', '=', 'e.provinsi_id')
                    ->orderBy('pesanan.id')
                    ->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('tgl_po', function ($data) {
                return $data->tgl_po;
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->divisi;
            })
            ->addColumn('batas_out', function ($d) {
                if (isset($d->tgl_kontrak)) {
                    return Carbon::createFromFormat('Y-m-d', $d->tgl_kontrak)->isoFormat('D MMMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('status_prd', function ($data) {
                if ($data->log_id) {
                    # code...
                    return '<span class="badge badge-warning">' . $data->log_nama . '</span>';
                } else {
                    return '-';
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getDetailSO(Request $request, $id, $value)
    {
        try {
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
                ->addColumn('paket', function ($data) {
                    $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($data) {
                        $q->where('id', $data->id);
                    })->get();
                    $xx = 0;
                    $xr = null;
                    foreach ($s as $i) {
                        foreach ($i->PenjualanProduk->Produk as $j) {
                            if ($j->id == $data->gudangbarangjadi->produk_id) {
                                $xx = $i->jumlah * $j->pivot->jumlah;
                            }
                            $xr = $i->id;
                        }
                    }
                    // $xy += $xx;
                    $s = Pesanan::whereHas('DetailPesanan.DetailPesananProduk', function($d) use($data) {
                        $d->where('id', $data->id);
                    })->first()->id;
                    $x = DB::table(DB::raw('pesanan p'))
                            ->select('p.id as pesid','p.so','pp.id as paketid','pp.nama','dp.jumlah','dpp.id as dppid',DB::raw('count(tgn.noseri_id) as jumlah_kirim'))
                            ->leftJoin(DB::raw('detail_pesanan dp'),'dp.pesanan_id','=','p.id')
                            ->leftJoin(DB::raw('detail_pesanan_produk dpp'),'dp.id','=','dpp.detail_pesanan_id')
                            ->leftJoin(DB::raw('penjualan_produk pp'),'pp.id','=','dp.penjualan_produk_id')
                            ->leftJoin(DB::raw('t_gbj tg'),'tg.pesanan_id','=','p.id')
                            ->leftJoin(DB::raw('t_gbj_detail tgd'),'tgd.detail_pesanan_produk_id','=','dpp.id')
                            ->leftJoin(DB::raw('t_gbj_noseri tgn'),'tgn.t_gbj_detail_id','=','tgd.id')
                            ->where('p.id','=',$s)
                            ->where('dp.id','=',$xr)
                            ->groupBy('pp.id')
                            ->groupBy('dpp.id')
                            ->get()->sum('jumlah');
                    $y = DB::table(DB::raw('pesanan p'))
                            ->select('p.id as pesid','p.so','pp.id as paketid','pp.nama','dp.jumlah','dpp.id as dppid',DB::raw('count(tgn.noseri_id) as jumlah_kirim'))
                            ->leftJoin(DB::raw('detail_pesanan dp'),'dp.pesanan_id','=','p.id')
                            ->leftJoin(DB::raw('detail_pesanan_produk dpp'),'dp.id','=','dpp.detail_pesanan_id')
                            ->leftJoin(DB::raw('penjualan_produk pp'),'pp.id','=','dp.penjualan_produk_id')
                            ->leftJoin(DB::raw('t_gbj tg'),'tg.pesanan_id','=','p.id')
                            ->leftJoin(DB::raw('t_gbj_detail tgd'),'tgd.detail_pesanan_produk_id','=','dpp.id')
                            ->leftJoin(DB::raw('t_gbj_noseri tgn'),'tgn.t_gbj_detail_id','=','tgd.id')
                            ->where('p.id','=',$s)
                            ->where('dp.id','=',$xr)
                            ->groupBy('pp.id')
                            ->groupBy('dpp.id')
                            ->get()->sum('jumlah_kirim');
                            return $data->detailpesanan->penjualanproduk->nama.'<br> '.'<span class="badge badge-light">QC: '.$y.' ('.round(($y / $x)*100,2).'%)</span>'.' <span class="badge badge-warning">Gudang: '.round($x - $y).' ('.round((round($x - $y) / $x)*100,2).'%)</span>';
                })
                ->addColumn('produk', function ($data) {
                    if ($data->status_cek == 4) {
                        if (empty($data->gudangbarangjadi->nama)) {
                            return $data->gudangbarangjadi->produk->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $data->gudang_barang_jadi_id . '"><input type="hidden" name="detail_pesanan_produk_id[]" id="detail_pesanan_produk_id" value="' . $data->id . '">';
                        } else {
                            return $data->gudangbarangjadi->produk->nama .' '.$data->gudangbarangjadi->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $data->gudang_barang_jadi_id . '"><input type="hidden" name="detail_pesanan_produk_id[]" id="detail_pesanan_produk_id" value="' . $data->id . '">';
                        }
                    } else {
                        $dt = GudangBarangJadi::whereIn('produk_id', [$data->gudangbarangjadi->produk->id])->get();
                        $opt = '';
                        foreach($dt as $dt) {
                            $opt .= '<option disabled selected hidden>Pilih Varian <b>' . $dt->produk->nama . '</b></option><option value="' . $dt->id . '" >' . $dt->produk->nama . ' <b>'. $dt->nama.'</b></option>';
                        }
                        $a = '<select name="variasiid" id="variasiid" class="form-control">

                                ' . $opt . '
                                </select>';

                        return $a;
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
                    if ($data->status_cek == 4) {
                        return $x;
                    } else {
                        return '<input type="text" class="form-control jumlah" name="qty[]" id="qty" value="' . $x . '">';
                    }
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
                ->addColumn('progress', function($data) {
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

                    $datacek = NoseriTGbj::whereHas('detail', function ($q) use ($data) {
                        $q->where('gdg_brg_jadi_id', $data->gudang_barang_jadi_id);
                        $q->where('detail_pesanan_produk_id', $data->id);
                    })->whereHas('detail.header', function ($q) use ($data) {
                        $q->where('pesanan_id', $data->detailpesanan->pesanan->id);
                    })->get()->count();
                    $val = $datacek/$x * 100;
                    $vall = round($x - $datacek) / $x * 100;

                    if ($val >= 75 && $val < 101) {
                        $atr = '<span class="badge badge-success">QC: '.$datacek.' ('.round($val, 2).'%)</span> <br>
                                <span class="badge badge-light">Gudang: '.round($x - $datacek).' ('.round($vall, 2).'%)</span>';
                    } elseif ($val >= 50 && $val < 75) {
                        $atr = '<span class="badge badge-info">QC: '.$datacek.' ('.round($val, 2).'%)</span> <br>
                                <span class="badge badge-light">Gudang: '.round($x - $datacek).' ('.round($vall, 2).'%)</span>';
                    } elseif ($val >= 25 && $val < 50) {
                        $atr = '<span class="badge badge-warning">QC: '.$datacek.' ('.round($val, 2).'%)</span> <br>
                                <span class="badge badge-light">Gudang: '.round($x - $datacek).' ('.round($vall, 2).'%)</span>';
                    }
                    else {
                        $atr = '<span class="badge badge-danger">QC: '.$datacek.' ('.round($val, 2).'%)</span> <br>
                                <span class="badge badge-light">Gudang: '.round($x - $datacek).' ('.round($vall, 2).'%)</span>';
                    }

                    return $atr;
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
                        return '<input type="checkbox" class="cb-child-so" value="' . $d->id . '" disabled>';
                    } else {
                        return '<input type="checkbox" class="cb-child-so" value="' . $d->id . '">';
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

                            if ($data->status_cek == null) {
                                return 'Siapkan Produk Dulu';
                            } else {
                                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="" data-jml="' . $jml_now . '" data-id="' . $data->gudang_barang_jadi_id . '" data-dpp="' . $data->id . '">
                                    <button class="btn btn-primary" data-toggle="modal" data-target=".modal-scan"><i
                                    class="fas fa-qrcode"></i> Scan Produk</button>
                                    </a>';
                            }

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

                        if ($data->status_cek == null) {
                            return 'Siapkan Produk Dulu';
                        } else {
                            return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="" data-jml="' . $x . '" data-id="' . $data->gudang_barang_jadi_id . '" data-dpp="' . $data->id . '">
                                    <button class="btn btn-primary" data-toggle="modal" data-target=".modal-scan"><i
                                    class="fas fa-qrcode"></i> Scan Produk</button>
                                    </a>';
                        }

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
                ->rawColumns(['action', 'status', 'produk', 'qty', 'checkbox', 'status_prd', 'ids', 'progress', 'paket'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getEditSO(Request $request, $id, $value)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function headerSo($id, $value)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getHistorybyProduk()
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getNoseriSO(Request $request)
    {
        try {
            $data = NoseriBarangJadi::where('gdg_barang_jadi_id', $request->gdg_barang_jadi_id)->where('is_ready', 0)->where('is_aktif', 1)->get();
        $i = 0;
        return datatables()->of($data)
            ->addColumn('ids', function ($d) {
                return $d->id;
            })
            ->addColumn('ischange', function ($d) {
                return $d->is_change;
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getNoseriSOEdit(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

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
        $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select('p.id','p.so', 'p.no_po', 'p.log_id',
            DB::raw('count(dpp.gudang_barang_jadi_id)'),
            DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
            DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
            'ms.nama as log_nama', 'e.tgl_kontrak',
            DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi"))
            ->leftJoin(DB::raw('detail_pesanan dp'),'dpp.detail_pesanan_id','=','dp.id')
            ->leftJoin(DB::raw('pesanan p'),'dp.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('ekatalog e'),'e.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('provinsi p2'),'p2.id','=','e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'),'ms.id','=','p.log_id')
            ->leftJoin(DB::raw('spa s'),'s.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('spb s2'),'s2.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('customer c_ekat'),'c_ekat.id','=','e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'),'c_spa.id','=','s.customer_id')
            ->leftJoin(DB::raw('customer c_spb'),'c_spb.id','=','s2.customer_id')
            ->whereRaw('e.tgl_kontrak = date_sub(now(), interval 10 day)')
            ->groupBy('p.id')
            ->get();

        return count($data);
    }

    function h_minus5()
    {
        $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select('p.id','p.so', 'p.no_po', 'p.log_id',
            DB::raw('count(dpp.gudang_barang_jadi_id)'),
            DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
            DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
            'ms.nama as log_nama', 'e.tgl_kontrak',
            DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi"))
            ->leftJoin(DB::raw('detail_pesanan dp'),'dpp.detail_pesanan_id','=','dp.id')
            ->leftJoin(DB::raw('pesanan p'),'dp.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('ekatalog e'),'e.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('provinsi p2'),'p2.id','=','e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'),'ms.id','=','p.log_id')
            ->leftJoin(DB::raw('spa s'),'s.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('spb s2'),'s2.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('customer c_ekat'),'c_ekat.id','=','e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'),'c_spa.id','=','s.customer_id')
            ->leftJoin(DB::raw('customer c_spb'),'c_spb.id','=','s2.customer_id')
            ->whereRaw('e.tgl_kontrak = date_sub(now(), interval 5 day)')
            ->groupBy('p.id')
            ->get();

        return count($data);
    }

    function h_exp()
    {
        $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select('p.id','p.so', 'p.no_po', 'p.log_id',
            DB::raw('count(dpp.gudang_barang_jadi_id)'),
            DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
            DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
            'ms.nama as log_nama', 'e.tgl_kontrak',
            DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi"))
            ->leftJoin(DB::raw('detail_pesanan dp'),'dpp.detail_pesanan_id','=','dp.id')
            ->leftJoin(DB::raw('pesanan p'),'dp.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('ekatalog e'),'e.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('provinsi p2'),'p2.id','=','e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'),'ms.id','=','p.log_id')
            ->leftJoin(DB::raw('spa s'),'s.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('spb s2'),'s2.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('customer c_ekat'),'c_ekat.id','=','e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'),'c_spa.id','=','s.customer_id')
            ->leftJoin(DB::raw('customer c_spb'),'c_spb.id','=','s2.customer_id')
            ->whereRaw('e.tgl_kontrak < now()')
            ->groupBy('p.id')
            ->get();

        return count($data);
    }

    function minus5()
    {
        try {
            $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select('p.id','p.so', 'p.no_po', 'p.log_id',
            DB::raw('count(dpp.gudang_barang_jadi_id)'),
            DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
            DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
            'ms.nama as log_nama', 'e.tgl_kontrak',
            DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi"))
            ->leftJoin(DB::raw('detail_pesanan dp'),'dpp.detail_pesanan_id','=','dp.id')
            ->leftJoin(DB::raw('pesanan p'),'dp.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('ekatalog e'),'e.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('provinsi p2'),'p2.id','=','e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'),'ms.id','=','p.log_id')
            ->leftJoin(DB::raw('spa s'),'s.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('spb s2'),'s2.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('customer c_ekat'),'c_ekat.id','=','e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'),'c_spa.id','=','s.customer_id')
            ->leftJoin(DB::raw('customer c_spb'),'c_spb.id','=','s2.customer_id')
            ->whereRaw('e.tgl_kontrak = date_sub(now(), interval 5 day)')
            ->groupBy('p.id')
            ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('batas_out', function ($d) {
                    if (isset($d->tgl_kontrak)) {
                        return Carbon::parse($d->tgl_kontrak)->isoFormat('D MMM YYYY');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_prd', function ($data) {
                    if ($data->log_id) {
                        # code...
                        return '<span class="badge badge-warning">' . $data->log_nama . '</span>';
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function minus10()
    {
        try {
            $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select('p.id','p.so', 'p.no_po', 'p.log_id',
            DB::raw('count(dpp.gudang_barang_jadi_id)'),
            DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
            DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
            'ms.nama as log_nama', 'e.tgl_kontrak',
            DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi"))
            ->leftJoin(DB::raw('detail_pesanan dp'),'dpp.detail_pesanan_id','=','dp.id')
            ->leftJoin(DB::raw('pesanan p'),'dp.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('ekatalog e'),'e.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('provinsi p2'),'p2.id','=','e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'),'ms.id','=','p.log_id')
            ->leftJoin(DB::raw('spa s'),'s.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('spb s2'),'s2.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('customer c_ekat'),'c_ekat.id','=','e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'),'c_spa.id','=','s.customer_id')
            ->leftJoin(DB::raw('customer c_spb'),'c_spb.id','=','s2.customer_id')
            ->whereRaw('e.tgl_kontrak = date_sub(now(), interval 10 day)')
            ->groupBy('p.id')
            ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('batas_out', function ($d) {
                    if (isset($d->tgl_kontrak)) {
                        return Carbon::parse($d->tgl_kontrak)->isoFormat('D MMM YYYY');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_prd', function ($data) {
                    if ($data->log_id) {
                        # code...
                        return '<span class="badge badge-warning">' . $data->log_nama . '</span>';
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function expired()
    {
        try {
            $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select('p.id','p.so', 'p.no_po', 'p.log_id',
            DB::raw('count(dpp.gudang_barang_jadi_id)'),
            DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
            DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
            'ms.nama as log_nama', 'e.tgl_kontrak',
            DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi"))
            ->leftJoin(DB::raw('detail_pesanan dp'),'dpp.detail_pesanan_id','=','dp.id')
            ->leftJoin(DB::raw('pesanan p'),'dp.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('ekatalog e'),'e.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('provinsi p2'),'p2.id','=','e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'),'ms.id','=','p.log_id')
            ->leftJoin(DB::raw('spa s'),'s.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('spb s2'),'s2.pesanan_id','=','p.id')
            ->leftJoin(DB::raw('customer c_ekat'),'c_ekat.id','=','e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'),'c_spa.id','=','s.customer_id')
            ->leftJoin(DB::raw('customer c_spb'),'c_spb.id','=','s2.customer_id')
            ->whereRaw('e.tgl_kontrak < now()')
            ->groupBy('p.id')
            ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('batas_out', function ($d) {
                    if (isset($d->tgl_kontrak)) {
                        $a = Carbon::now()->diffInDays($d->tgl_kontrak);
                        return Carbon::parse($d->tgl_kontrak)->isoFormat('D MMM YYYY') . '<br> <span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_prd', function ($data) {
                    if ($data->log_id) {
                        return '<span class="badge badge-warning">' . $data->log_nama . '</span>';
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

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }
    // rakit

    function exp_rakit()
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function exp_rakit_h()
    {
        $data = JadwalPerakitan::whereRaw('DATEDIFF(tanggal_selesai, now()) <= 10')
            ->whereRaw('DATEDIFF(tanggal_selesai, now()) > 0')->get();
        return count($data);
    }

    function exp_jadwal()
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function exp_jadwal_h()
    {
        $data = JadwalPerakitan::where('state', 'perubahan')->whereNotIn('status_tf', [14])->get();
        return count($data);
    }

    function change_jadwal()
    {
        try {
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
            ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    // perakitan
    function plan_rakit()
    {
        try {
            $data = DB::table('jadwal_perakitan')->
            select('jadwal_perakitan.id', 'jadwal_perakitan.produk_id', 'jadwal_perakitan.created_at','jadwal_perakitan.tanggal_mulai','jadwal_perakitan.tanggal_selesai',DB::raw('datediff(now(), jadwal_perakitan.tanggal_selesai) as selisih'),DB::raw('jadwal_perakitan.no_bppb'),DB::raw("concat(p.nama,' ',gbj.nama) as produkk"),DB::raw('jadwal_perakitan.jumlah'))
            ->leftJoin(DB::raw('gdg_barang_jadi as gbj'),'gbj.id','=','jadwal_perakitan.produk_id')
            ->leftJoin(DB::raw('produk as p'),'p.id','=','gbj.produk_id')
            ->whereMonth('jadwal_perakitan.tanggal_mulai', Carbon::now()->addMonth())
            ->groupBy('jadwal_perakitan.id')
            ->get();

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
                return $d->produkk;
            })
            ->addColumn('jml', function ($d) {
                return $d->jumlah . ' Unit';
            })
            ->make(true);
        return $res;
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

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
        try {
            $data = DB::select("select jp.id, jp.produk_id, jp.created_at, jp.tanggal_mulai, jp.tanggal_selesai,
        jp.no_bppb, jp.jumlah, jp.evaluasi, count(jrn.jadwal_id) as jml_rakit,
        concat(p.nama,' ',gbj.nama) as produkk,
        datediff(now(), jp.tanggal_selesai) as selisih
        from jadwal_perakitan jp
        left join jadwal_rakit_noseri jrn on jrn.jadwal_id = jp.id
        left join gdg_barang_jadi gbj on gbj.id = jp.produk_id
        left join produk p on p.id = gbj.produk_id
        where jp.status not in (6) and jp.status_tf not in(14)
        group by jp.id
        having jp.jumlah != count(jrn.jadwal_id)");

        $res = datatables()->of($data)
                ->addColumn('start', function ($d) {
                    if (isset($d->tanggal_mulai)) {
                        return Carbon::parse($d->tanggal_mulai)->isoFormat('D MMM YYYY');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('end', function ($d) {
                    $x = $d->selisih;

                    if (isset($d->tanggal_selesai)) {
                        if ($x >= -10 && $x < -5) {
                            return '<span class="tanggal">' . Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="badge badge-warning">Kurang ' . abs($x) . ' Hari</span>';
                        } elseif ($x >= -5 && $x <= -2) {
                            return '<span class="tanggal">' . Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="badge badge-warning">Kurang ' . abs($x) . ' Hari</span>';
                        } elseif ($x > -2 && $x <= 0) {
                            return '<span class="tanggal">' . Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="badge badge-danger">Kurang ' . $x . ' Hari</span>';
                        } elseif ($x > 0) {
                            return '<span class="tanggal">' . Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="badge badge-danger">Lebih ' . $x . ' Hari</span>';
                        } elseif ($x < -10) {
                            return '<span class="tanggal">' . Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span><br> <span class="badge badge-warning">Kurang ' . abs($x) . ' Hari</span>';
                        } else {
                            return '<span class="tanggal">' . Carbon::parse($d->tanggal_selesai)->isoFormat('D MMM YYYY') . '</span> ' . $x;
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('produk', function ($d) {
                    return $d->produkk;
                })
                ->addColumn('jml', function ($d) {
                    return $d->jumlah . ' Unit'. '<br><span class="badge badge-dark">Kurang ' . intval($d->jumlah - $d->jml_rakit) . ' Unit</span>';
                })
                ->addColumn('action', function ($d) {
                //     $a = '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '" data-jml="' . intval($d->jumlah - $d->jml_rakit) . '" data-produk="'.$d->produk_id.'">
                //                     <button class="btn btn-outline-info btn-sm"><i class="far fa-edit"></i> Rakit Produk</button>
                //             </a>&nbsp;<a data-toggle="modal" data-target="#detailtransfer" class="detailtransfer" data-attr=""  data-id="' . $d->id . '" data-jml="' . intval($d->jumlah - $d->jml_rakit) . '" data-prd="' . $d->produkk.'" data-produk="'.$d->produk_id.'">
                //                 <button class="btn btn-outline-danger btn-sm"><i class="far fa-edit"></i> Transfer Sisa Produk</button>
                //             </a>';
                    $a = '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '" data-jml="' . intval($d->jumlah - $d->jml_rakit) . '" data-produk="'.$d->produk_id.'">
                                    <button class="btn btn-outline-info btn-sm"><i class="far fa-edit"></i> Rakit Produk</button>
                            </a>&nbsp;<a data-toggle="modal" data-target="#detailtransfer" class="detailtransfer" data-attr=""  data-id="' . $d->id . '" data-jml="' . intval($d->jumlah - $d->jml_rakit) . '" data-prd="' . $d->produkk.'" data-produk="'.$d->produk_id.'">
                                <button class="btn btn-outline-danger btn-sm"><i class="far fa-edit"></i> Transfer Sisa Produk</button>
                            </a>
                            </a>&nbsp;<a data-toggle="modal" data-target="#evaluasirakit" class="evaluasirakit" data-attr=""  data-id="' . $d->id . '" data-jml="' . intval($d->jumlah - $d->jml_rakit) . '" data-prd="' . $d->produkk.'" data-produk="'.$d->produk_id.'" data-eval="'.$d->evaluasi.'">
                                <button class="btn btn-outline-secondary btn-sm"><i class="far fa-edit"></i> Evaluasi Perakitan</button>
                            </a>';
                    return $a;
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function storeTelatRakit(Request $request)
    {
        try {
            JadwalPerakitan::find($request->jadwal_id)->update(['evaluasi' => $request->evaluasi]);
            $obj = [
                'jadwal' => $request->jadwal_id,
                'evaluasi' => $request->evaluasi,
            ];

            SystemLog::create([
                'tipe' => 'Produksi',
                'subjek' => 'Evaluasi Perakitan',
                'response' => json_encode($obj),
                'user_id' => $request->created_by,
            ]);
            return response()->json(['msg' => 'Data Berhasil disimpan']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getSelesaiRakit()
    {
        try {
            $data = DB::select("select jp.id, jp.produk_id, jp.created_at, jp.tanggal_mulai, jp.tanggal_selesai,
        jp.no_bppb, jp.jumlah,
        concat(p.nama,' ',gbj.nama) as produkk,
        datediff(now(), jp.tanggal_selesai) as selisih,
        cast(sum(case when jrn.status = 14 then 1 else 0 end) as SIGNED) as jml_kirim,
        cast(sum(case when jrn.status = 11 then 1 else 0 end) as SIGNED) as jml_rakit,
        cast(sum(case when jrn.status in(11,14) then 1 else 0 end) as SIGNED) as jml_all,
        round((cast(sum(case when jrn.status = 14 then 1 else 0 end) as SIGNED) /
        cast(sum(case when jrn.status in(11,14) then 1 else 0 end) as SIGNED) * 100),2) as perc_kirim,
        round((cast(sum(case when jrn.status in(11,14) then 1 else 0 end) as SIGNED) / jp.jumlah) * 100,2)  as perc_isi,
        round((cast(sum(case when jrn.status = 11 then 1 else 0 end) as SIGNED) /
        cast(sum(case when jrn.status in(11,14) then 1 else 0 end) as SIGNED) * 100),2) as perc_rakit
        from jadwal_perakitan jp
        left join jadwal_rakit_noseri jrn on jrn.jadwal_id = jp.id
        left join gdg_barang_jadi gbj on gbj.id = jp.produk_id
        left join produk p on p.id = gbj.produk_id
        where jp.status not in (6) and jp.status_tf not in(14,11)
        group by jp.id
        having jp.jumlah != cast(sum(case when jrn.status = 14 then 1 else 0 end) as SIGNED)");

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
                  return $d->produkk;
              })
              ->addColumn('jml', function ($d) {
                  return  $d->jumlah . ' Unit<br><span class="badge badge-dark">Terisi: ' . $d->jml_all . ' Unit</span>';
              })
              ->addColumn('action', function ($d) {
                  if ($d->jml_rakit != 0) {
                      return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $d->id . '" data-jml="' . $d->jumlah . '" data-prd="' . $d->produk_id . '">
                                      <button class="btn btn-outline-success btn-sm"><i class="far fa-edit"></i> Transfer</button>
                                  </a>&nbsp;
                                  <a data-toggle="modal" data-target="#detailmodalTransfer" class="detailmodalTransfer" data-attr=""  data-id="' . $d->id . '" data-jml="' . $d->jml_rakit . '" data-prd="' . $d->produk_id . '">
                                      <button class="btn btn-outline-danger btn-sm"><i class="far fa-edit"></i> Transfer Sisa Produk</button>
                                  </a>';
                  }
              })
              ->addColumn('progress', function($d){
                  $a = $d->perc_kirim == null ? '0.00' : $d->perc_kirim;
                  $b = $d->perc_rakit == null ? '0.00' : $d->perc_rakit;
                  return '<span class="badge badge-success">Terkirim: '.$d->jml_kirim.' Unit ('.$a.'%)</span>
                          <br><span class="badge badge-dark">Rakit: ' . $d->jml_rakit . ' Unit ('.$b.'%)</span>';
              })
              ->addColumn('no_bppb', function ($d) {
                  return $d->no_bppb == null ? '-' : $d->no_bppb;
              })
              ->rawColumns(['action', 'status', 'jml', 'progress'])
              ->make(true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function detailRakitHeader($id)
    {
        try {
            $d = JadwalPerakitan::find($id);
            return response()->json([
                'no_bppb' => $d->no_bppb == null ? '-' : $d->no_bppb,
                'produk' => $d->produk->produk->nama . ' ' . $d->produk->nama,
                'kategori' => $d->produk->produk->KelompokProduk->nama,
                'jml' => $d->jumlah . ' ' . $d->produk->satuan->nama,
                'start' => Carbon::parse($d->tanggal_mulai)->isoFormat('D MMM YYYY'),
                'end' => Carbon::parse($d->tanggal_sel)->isoFormat('D MMM YYYY'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function cekDuplicateNoseri(Request $request)
    {
        try {
            $data = JadwalRakitNoseri::whereIn('noseri', $request->noseri)->get()->pluck('noseri');
            $data1 = NoseriBarangJadi::whereIn('noseri', $request->noseri)->get()->pluck('noseri');
            $datam = $data->merge($data1);
            $seri = [];
            $seri1 = [];
            if (count($data) > 0 || count($data1) > 0) {
                foreach ($data as $item) {
                    array_push($seri, $item);
                }

                foreach ($data1 as $item1) {
                    array_push($seri1, $item1);
                }

                if(count($data) > 0) {
                    return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri) . ' sudah terdaftar di perakitan', 'error' => true]);
                }

                if(count($data1) > 0) {
                    return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri1) . ' sudah terdaftar di gudang barang jadi', 'error' => true]);
                }
                // return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri) . ' sudah terdaftar', 'error' => true]);
            } else {
                return response()->json(['msg' => 'Success', 'error' => false]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
        // $noseri = JadwalRakitNoseri::whereIn('noseri', $request->noseri)->get();

    }

    function cekUbahNoseri(Request $request)
    {
        try {
            $data = JadwalRakitNoseri::where('noseri', $request->noseri)->get()->count();
            if ($data > 0) {
                return response()->json(['msg' => 'Noseri ' . $request->noseri . ' Sudah Terdaftar', 'error' => true]);
            } else {
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function storeRakitNoseri(Request $request)
    {
        try {
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
                            // return 'all';
                            $d->status_tf = 15;
                            $d->no_bppb = strtoupper($request->no_bppb);
                            $d->filled_by = $request->userid;
                            $d->save();
                        } else {
                            // return 'part1';
                            $d->status_tf = 12;
                            $d->no_bppb = strtoupper($request->no_bppb);
                            $d->filled_by = $request->userid;
                            $d->save();
                        }
                    }
                }

                $obj = [
                    'waktu_rakit' => $request->tgl_perakitan,
                    'jadwal' => $request->jadwal_id,
                    'no_bppb' => strtoupper($request->no_bppb),
                    'noseri' => $request->noseri,
                    'jumlah' => count($request->noseri)
                ];

                SystemLog::create([
                    'tipe' => 'Produksi',
                    'subjek' => 'Perakitan Produk',
                    'response' => json_encode($obj),
                    'user_id' => $request->userid,
                    'created_at' => Carbon::now()
                ]);

                return response()->json(['msg' => 'Successfully']);
            } else {
                return response()->json(['msg' => 'Noseri Sudah Ada, Silahkan Gunakan yang lain.']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
        // dd($request->all());

    }

    function closeRakit(Request $request)
    {
        try {
            JadwalPerakitan::find($request->jadwal_id)->update(['keterangan' => $request->keterangan, 'status_tf' => 14]);
            $obj = [
                'jadwal' => $request->jadwal_id,
                'keterangan' => $request->keterangan,
                'tgl_closing' => Carbon::now(),
            ];

            SystemLog::create([
                'tipe' => 'Produksi',
                'subjek' => 'Tutup Perakitan',
                'response' => json_encode($obj),
                'user_id' => $request->created_by,
            ]);
            return response()->json(['msg' => 'Data Berhasil disimpan']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function closeTransfer(Request $request)
    {
        try {
            JadwalPerakitan::find($request->jadwal_id)->update(['keterangan_transfer' => $request->keterangan_transfer, 'status_tf' => 14]);

            $obj = [
                'jadwal' => $request->jadwal_id,
                'keterangan_transfer' => $request->keterangan_transfer,
                'tgl_closing' => Carbon::now(),
            ];

            SystemLog::create([
                'tipe' => 'Produksi',
                'subjek' => 'Tutup Pengiriman',
                'response' => json_encode($obj),
                'user_id' => $request->created_by,
            ]);
            return response()->json(['msg' => 'Data Berhasil disimpan']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getHeaderSeri($id)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function historySeri($id, $dd)
    {
        try {
            $data = JadwalRakitNoseri::whereHas('header', function ($q) use ($id) {
                $q->where('produk_id', $id);
            })
                ->whereRaw("date_format(waktu_tf, '%Y-%m-%d %H:%i') = ?", [$dd])
                // ->whereRaw("date_format(date_in, '%Y-%m-%d %H:%i') = ?", [$rakit])
                ->get();
            return datatables()->of($data)
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" name="noseri[]" id="noseri" value="' . $d->id . '" class="cb-child">';
                })
                ->addColumn('no_seri', function ($d) {
                    return $d->noseri;
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function get_detail_noseri_rakit($id, $dd)
    {
        try {
            $data = JadwalRakitNoseri::whereHas('header', function ($q) use ($id) {
                $q->where('produk_id', $id);
            })
                ->whereRaw("date_format(date_in, '%Y-%m-%d %H:%i') = ?", [$dd])
                // ->whereRaw("date_format(date_in, '%Y-%m-%d %H:%i') = ?", [$rakit])
                ->get();
            return datatables()->of($data)
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" name="noseri[]" id="noseri" value="' . $d->id . '" class="cb-child">';
                })
                ->addColumn('no_seri', function ($d) {
                    return $d->noseri;
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function detailSeri1($id, $jadwal)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function kirimseri(Request $request)
    {
        try {
            $header = new TFProduksi();
            $header->tgl_masuk = $request->tgl_transfer;
            $header->dari = 17;
            // $header->ke = $request->tujuan;
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
                    $seri = NoseriBarangJadi::updateOrCreate(
                        ['noseri' => $request->noseri[$key]],
                        [
                        'dari' => 17,
                        'ke' => $request->tujuan,
                        'gdg_barang_jadi_id' => $request->gbj_id,
                        'noseri' => $request->noseri[$key],
                        'jenis' => 'MASUK',
                        'is_aktif' => 0,
                        'created_at' => Carbon::now(),
                        'created_by' => $request->userid,
                    ]);
                    $seriid = $seri->id;

                    $serit = new NoseriTGbj();
                    $serit->t_gbj_detail_id = $detail->id;
                    $serit->noseri_id = $seriid;
                    $serit->layout_id = 1;
                    $serit->jenis = 'MASUK';
                    $serit->created_at = Carbon::now();
                    $serit->created_by = $request->userid;
                    $serit->save();
                }
                JadwalRakitNoseri::where('jadwal_id', $request->jadwal_id)->whereIn('noseri', [$request->noseri[$key]])->update(['waktu_tf' => $request->tgl_transfer, 'status' => 14, 'transfer_by' => $request->userid]);
            }

            $obj = [
                'tgl_keluar' => $request->tgl_transfer,
                'tujuan' => Divisi::find(13)->nama,
                'produk' => Produk::find(GudangBarangJadi::find($request->gbj_id)->produk_id)->nama.' '.GudangBarangJadi::find($request->gbj_id)->nama,
                'jumlah' => count($request->noseri),
                'jadwal' => $request->jadwal_id,
                'noseri' => $request->noseri
            ];

            SystemLog::create([
                'tipe' => 'Produksi',
                'subjek' => 'Pengiriman Noseri Produksi',
                'response' => json_encode($obj),
                'user_id' => $request->userid
            ]);

            return response()->json(['msg' => 'Berhasil Transfer ke Gudang']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function deleteNoseri(Request $request)
    {
        try {
            $cek_data = JadwalRakitNoseri::where('id', $request->noseriid)->where('jadwal_id', $request->jadwal_id)->get()->count();
            if ($cek_data > 0) {
                $obj = [
                    'jadwal' => $request->jadwal_id,
                    'noseriid' => $request->noseriid,
                    // 'noseri' => JadwalRakitNoseri::find($request->noseriid)->noseri,
                    'tgl_hapus' => Carbon::now(),
                ];

                SystemLog::create([
                    'tipe' => 'Produksi',
                    'subjek' => 'Hapus Noseri Perakitan',
                    'response' => json_encode($obj),
                    'user_id' => $request->userby
                ]);
                JadwalRakitNoseri::where('id', $request->noseriid)->where('jadwal_id', $request->jadwal_id)->delete();
                JadwalPerakitan::find($request->jadwal_id)->update(['status_tf' => 12]);

                return response()->json(['msg' => 'Data Berhasil Dihapus']);
            } else {
                return response()->json(['error' => 'Data Tidak Ada']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function updateNoseri(Request $request)
    {
        try {
            $cek_data = JadwalRakitNoseri::where('id', $request->noseriid)->where('jadwal_id', $request->jadwal_id)->get()->count();
            if ($cek_data > 0) {
                JadwalRakitNoseri::where('id', $request->noseriid)->where('jadwal_id', $request->jadwal_id)->update(['noseri' => $request->noseri]);

                return response()->json(['msg' => 'Data Berhasil diubah']);
            } else {
                return response()->json(['error' => 'Data Tidak Ada']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function deleteAllSeri(Request $request)
    {
        try {
            $obj = [
                'jadwal' => $request->jadwal_id,
                // 'noseriid' => JadwalRakitNoseri::whereIn('noseri', $request->noseri)->where('jadwal_id', $request->jadwal_id)->pluck('id'),
                // 'noseri' => JadwalRakitNoseri::whereIn('noseri', $request->noseri)->where('jadwal_id', $request->jadwal_id)->pluck('noseri'),
                'tgl_hapus' => Carbon::now(),
            ];

            SystemLog::create([
                'tipe' => 'Produksi',
                'subjek' => 'Hapus Beberapa Noseri Perakitan',
                'response' => json_encode($obj),
                'user_id' => $request->userby
            ]);
            JadwalRakitNoseri::whereIn('noseri', $request->noseri)->where('jadwal_id', $request->jadwal_id)->delete();
            JadwalPerakitan::find($request->jadwal_id)->update(['status_tf' => 12]);
            return response()->json(['msg' => 'Sukses']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

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
        $rakit = JadwalRakitNoseri::distinct()->count('jadwal_id');
        $unit = JadwalRakitNoseri::count('jadwal_id');
        $data = JadwalPerakitan::where('status_tf', 14)->get();
        $detail = JadwalPerakitan::with('noseri', 'produk.produk')->where('status_tf', 14)->get();
        return view('page.produksi.riwayat_perakitan', compact('rakit', 'unit', 'data', 'detail'));
    }

    function header_his_rakit($id)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function product_his_rakit()
    {
        try {
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
                array_push($produk, $a->produk->nama . ' ' . $a->nama);
            }
            $data = array_unique($produk);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    // gbj
    function terimaseri(Request $request)
    {
        try {
            foreach($request->data as $key => $value) {
               $nid = NoseriTGbj::find($key)->noseri_id;
                NoseriBarangJadi::whereIn('id',[$nid])->update(['is_aktif' => 1]);
                NoseriTGbj::whereIn('id', [$key])->update(['status_id' => 3, 'state_id' => 16, 'layout_id' => $value]);
            }

            return response()->json(['msg' => 'Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    // versi 2
    function getCountProdukBySO()
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function detailCountProdukBySO($id)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

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

    function ajax_perproduk()
    {
        try {
            $data = JadwalPerakitan::with('produk')->groupBy('produk_id')->groupBy('no_bppb')->has('noseri')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_bppb', function ($d) {
                return $d->no_bppb == null ? '-' : $d->no_bppb;
            })
            ->addColumn('produk', function ($d) {
                if (isset($d->produk_id)) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                }
            })
            ->addColumn('aksi', function ($d) {
                return $d->produk_id;
            })
            ->rawColumns(['aksi'])
            ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }


    }

    function detail_perproduk($id)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function ajax_sisa_transfer()
    {
        try {
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
                ->addColumn('remark', function ($d) {
                    if (isset($d->keterangan)) {
                        return $d->keterangan;
                    } else {
                        return $d->keterangan_transfer;
                    }
                })
                ->addColumn('aksi', function ($d) {
                    $seri_belum = JadwalRakitNoseri::where('jadwal_id', $d->id)->where('status', 11)->get()->count();
                    if (isset($d->keterangan)) {
                        return '
                        <a data-toggle="modal" data-target="#rakitmodal" class="transferlain" data-attr=""  data-id="' . $d->id . '" data-jml="' . $seri_belum . '" data-prd="' . $d->produk_id . '" data-ket="' . $d->keterangan . '">
                            <button class="btn btn-outline-secondary"><i class="far fa-eye"></i> Detail</button>
                        </a>
                        ';
                    } else {
                        return '
                        <a data-toggle="modal" data-target="#rakitmodal" class="transferlain" data-attr=""  data-id="' . $d->id . '" data-jml="' . $seri_belum . '" data-prd="' . $d->produk_id . '" data-ket="' . $d->keterangan_transfer . '">
                            <button class="btn btn-outline-secondary"><i class="far fa-eye"></i> Detail</button>
                        </a>
                        ';
                    }

                })
                ->addColumn('start_filter', function ($d) {
                    if (isset($d->tanggal_mulai)) {
                        return Carbon::createFromFormat('Y-m-d', $d->tanggal_mulai)->isoFormat('D-MM-Y');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('end_filter', function ($d) {
                    if (isset($d->tanggal_selesai)) {
                        return Carbon::createFromFormat('Y-m-d', $d->tanggal_selesai)->isoFormat('D-MM-Y');
                    } else {
                        return '-';
                    }
                })
                ->rawColumns(['aksi', 'jml_rakit', 'jml_sisa'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function detail_sisa_kirim(Request $request)
    {
        try {
            $data = JadwalRakitNoseri::with('header')->where('jadwal_id', $request->id)->where('status', 11)->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('noseri', function ($d) {
                    return $d->noseri;
                })
                ->addColumn('tgl_masuk', function ($d) {
                    return Carbon::parse($d->date_in)->isoFormat('dddd, D MMM YYYY hh:ii:ss');
                })
                ->addColumn('waktu_masuk', function ($d) {
                    return Carbon::parse($d->date_in)->isoFormat('hh:ii:ss');
                })
                ->addColumn('remark', function ($d) {
                    if (isset($d->header->keterangan)) {
                        return $d->header->keterangan;
                    } else {
                        return $d->header->keterangan_transfer;
                    }
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function get_his_rakit()
    {
        try {
            $data =
            DB::table('jadwal_rakit_noseri')
                    ->join('jadwal_perakitan as jp', 'jp.id', '=', 'jadwal_rakit_noseri.jadwal_id')
                    ->join('gdg_barang_jadi as gdg', 'gdg.id', '=', 'jp.produk_id')
                    ->join('produk as p', 'p.id', '=', 'gdg.produk_id')
                    ->groupBy('jadwal_rakit_noseri.jadwal_id')
                    ->groupBy(DB::raw("date_format(jadwal_rakit_noseri.date_in, '%Y-%m-%d %H:%i')"))
                    ->select('jadwal_rakit_noseri.jadwal_id', 'jadwal_rakit_noseri.date_in', 'jadwal_rakit_noseri.created_at', DB::raw('concat(p.nama," ", gdg.nama) as produkk'), 'jp.produk_id', DB::raw('count(jadwal_rakit_noseri.jadwal_id) as jml'), 'jp.no_bppb')
                    ->orderByDesc('date_in')
                    ->get();

        return datatables()->of($data)
            ->addColumn('day_rakit', function ($d) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->isoFormat('dddd, D MMMM Y');
            })
            ->addColumn('time_rakit', function ($d) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->format('H:i');
            })
            ->addColumn('bppb', function ($d) {
                return $d->no_bppb == null ? '-' : $d->no_bppb;
            })
            ->addColumn('produk', function ($d) {
                return $d->produkk;
            })
            ->addColumn('jml', function ($d) {
                return $d->jml . ' Unit';
            })
            ->addColumn('action', function ($d) {
                return '<button class="btn btn-outline-secondary detail" data-rakit="' . Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->format('Y-m-d H:i') . '" data-jml="' . $d->jml . '" data-id="' . $d->produk_id . '"><i class="far fa-eye"></i> Detail</button>';
            })
            ->addColumn('day_rakit_filter', function ($d) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $d->date_in)->isoFormat('D-MM-Y');
            })
            ->rawColumns(['action'])
            ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function ajax_history_rakit()
    {
        try {
            $d = JadwalRakitNoseri::select('jadwal_rakit_noseri.jadwal_id', 'jadwal_rakit_noseri.date_in', 'jadwal_rakit_noseri.created_at', 'jadwal_rakit_noseri.waktu_tf', 'jadwal_perakitan.produk_id', DB::raw('count(jadwal_id) as jml'), 'jadwal_perakitan.no_bppb')
            ->join('jadwal_perakitan', 'jadwal_perakitan.id', '=', 'jadwal_rakit_noseri.jadwal_id')
            ->groupBy('jadwal_rakit_noseri.jadwal_id')
            // ->groupBy(DB::raw("date_format(jadwal_rakit_noseri.date_in, '%Y-%m-%d %H:%i')"))
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function h_pengiriman()
    {
        try {
            $d = JadwalRakitNoseri::select('jadwal_rakit_noseri.jadwal_id', 'jadwal_rakit_noseri.date_in', 'jadwal_rakit_noseri.created_at', 'jadwal_rakit_noseri.waktu_tf', 'jadwal_perakitan.produk_id', DB::raw('count(jadwal_id) as jml'), 'jadwal_perakitan.no_bppb')
        ->join('jadwal_perakitan', 'jadwal_perakitan.id', '=', 'jadwal_rakit_noseri.jadwal_id')
        ->groupBy('jadwal_rakit_noseri.jadwal_id')
        // ->groupBy(DB::raw("date_format(jadwal_rakit_noseri.date_in, '%Y-%m-%d %H:%i')"))
        ->groupBy(DB::raw("date_format(jadwal_rakit_noseri.waktu_tf, '%Y-%m-%d %H:%i')"))
        ->whereNotNull('jadwal_rakit_noseri.waktu_tf')
        ->get()->sortByDesc('waktu_tf');

        return datatables()->of($d)
            ->addColumn('day_kirim', function ($d) {
                if (isset($d->waktu_tf)) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $d->waktu_tf)->isoFormat('dddd, D MMMM Y');
                } else {
                    return '-';
                }
            })
            ->addColumn('day_kirim_filter', function ($d) {
                if (isset($d->waktu_tf)) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $d->waktu_tf)->format('Y-m-d');
                } else {
                    return '-';
                }
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
            ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function export_noseri_produksi(Request $request)
    {
        return Excel::download(new NoseriRakitExport(), 'NoseriPerakitan.xlsx');
    }
}
