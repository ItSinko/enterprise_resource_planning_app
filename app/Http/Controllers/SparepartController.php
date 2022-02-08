<?php

namespace App\Http\Controllers;

use App\Exports\ProdukGKExport;
use App\Exports\TransaksiGKExport;
use App\Exports\UnitGKExport;
use App\Models\GudangBarangJadi;
use App\Models\GudangKarantina;
use App\Models\GudangKarantinaDetail;
use App\Models\GudangKarantinaNoseri;
use App\Models\Layout;
use App\Models\NoseriKeluarGK;
use App\Models\NoseriTGbj;
use App\Models\Sparepart;
use App\Models\SparepartGudang;
use App\Models\SparepartHis;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class SparepartController extends Controller
{
    // get
    function get()
    {
        $data = GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'))
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get();
        return datatables()->of($data)
            ->addColumn('kode', function ($d) {
                return $d->sparepart->spare->kode;
            })
            ->addColumn('produk', function ($d) {
                return $d->sparepart->nama;
            })
            ->addColumn('unit', function ($d) {
                return '-';
            })
            ->addColumn('jml', function ($d) {
                $cek1 = GudangKarantinaNoseri::whereHas('detail', function ($q) use ($d) {
                    $q->where('sparepart_id', $d->sparepart_id)->where('is_draft', 0)->where('is_ready', 1);
                })->get()->count();
                return $d->jml . ' pcs'.'<br><span class="badge badge-dark">Keluar '.$cek1.' Noseri</span>';
            })
            ->addColumn('button', function ($d) {
                return '<a class="btn btn-outline-info" href="' . url('gk/gudang/sparepart/' . $d->sparepart_id . '') . '"><i
                class="far fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['button', 'jml'])
            ->make(true);
    }

    function get_unit()
    {
        $data = GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'))
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->units->produk->nama . ' ' . $data->units->nama;
            })
            ->addColumn('kode_produk', function ($data) {
                return $data->units->produk->product->kode . '' . $data->units->produk->kode;
            })
            ->addColumn('jumlah', function ($data) {
                $cek1 = GudangKarantinaNoseri::whereHas('detail', function ($q) use ($data) {
                    $q->where('gbj_id', $data->gbj_id)->where('is_draft', 0)->where('is_ready', 1);
                })->get()->count();
                return $data->jml . ' ' . $data->units->satuan->nama.'<br><span class="badge badge-dark">Keluar '.$cek1.' Noseri</span>';
            })
            ->addColumn('kelompok', function ($data) {
                return $data->units->produk->KelompokProduk->nama;
            })
            ->addColumn('button', function ($d) {
                return '<a class="btn btn-outline-info" href="' . url('gk/gudang/unit/' . $d->gbj_id . '') . '"><i
                class="far fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['button', 'jumlah'])
            ->make(true);
    }
    // detail
    function detail_spr($id)
    {
        $header = GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'))
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->where('m_gs.id', $id)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get();
        $layout = Layout::where('jenis_id', 2)->get();
        return view('page.gk.gudang.sparepartEdit', compact('header', 'layout'));
    }

    function detail_unit($id)
    {
        $header1 = GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi', 'produk.nama as nama', 'm_satuan.nama as satuan')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->where('gdg_barang_jadi.id', $id)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->join('m_satuan', 'm_satuan.id', 'gdg_barang_jadi.satuan_id')
            ->get();

        $layout = Layout::where('jenis_id', 1)->get();
        $seri = GudangKarantinaNoseri::find($id);
        return view('page.gk.gudang.unitEdit', compact('header1', 'layout', 'seri'));
    }

    function history_spr($id)
    {
        $cek1 = collect(GudangKarantinaNoseri::whereHas('detail', function ($q) use ($id) {
            $q->where('sparepart_id', $id)->where('is_draft', 0);
        })->get());
        $cek = collect(NoseriKeluarGK::whereHas('detail', function ($qq) use ($id) {
            $qq->where('sparepart_id', $id)->where('is_draft', 0);
        })->get());
        $data = $cek1->merge($cek);
        // return $data;
        return datatables()->of($data)
            ->addColumn('inn', function ($d) {
                if (empty($d->detail->header->date_in)) {
                    return '-';
                } else {
                    return Carbon::parse($d->detail->header->date_in)->isoFormat('D MMM YYYY');
                }
            })
            ->addColumn('out', function ($d) {
                if (empty($d->detail->header->date_out)) {
                    return '-';
                } else {
                    return Carbon::parse($d->detail->header->date_out)->isoFormat('D MMM YYYY');
                }
            })
            ->addColumn('from', function ($d) {
                if (empty($d->detail->header->dari)) {
                    return '-';
                } else {
                    return '<span class="badge badge-success">' . $d->detail->header->from->nama . '</span>';
                }
            })
            ->addColumn('to', function ($d) {
                if (empty($d->detail->header->ke)) {
                    return '-';
                } else {
                    return '<span class="badge badge-info">' . $d->detail->header->to->nama . '</span>';
                }
            })
            ->addColumn('noser', function ($d) {
                if($d->seri) {
                    return $d->seri->noseri;
                } else {
                    return $d->noseri;
                }
            })
            ->addColumn('layout', function ($d) {
                if($d->seri) {
                    return $d->seri->layout->ruang;
                } else {
                    if (empty($d->layout->ruang)) {
                        return '-';
                    } else {
                        return $d->layout->ruang;
                    }
                }

            })
            ->addColumn('remarks', function ($d) {
                if($d->seri) {
                    return $d->seri->remark;
                } else {
                    if (empty($d->remark)) {
                        return '-';
                    } else {
                        return $d->remark;
                    }
                }

            })
            ->addColumn('perbaikan', function ($d) {
                if($d->seri) {
                    return $d->seri->perbaikan;
                } else {
                    if (empty($d->perbaikan)) {
                        return '-';
                    } else {
                        return $d->perbaikan;
                    }
                }

            })
            ->addColumn('tingkat', function ($d) {
                if($d->seri) {
                    return 'Level ' . $d->seri->tk_kerusakan;
                } else {
                    return 'Level ' . $d->tk_kerusakan;
                }
            })
            ->addColumn('status', function ($d) {
                if($d->seri) {
                    return '<span class="sudah_ditransfer">Sudah Ditransfer</span>';
                } else {
                    if ($d->status == 0) {
                        return '<span class="belum_diterima">Belum Diperbaiki</span>';
                    } else {
                        return '<span class="sudah_diterima">Sudah Diperbaiki</span>';
                    }
                }

            })
            ->addColumn('action', function ($d) {
                if($d->seri) {
                    return ' ';
                } else {
                    return '<a data-toggle="modal" data-target="#detailModal" class="detailModal" data-attr=""  data-id="' . $d->id . '">
                        <button class="btn btn-outline-info"><i class="far fa-edit"></i></button>
                        </a>';
                }

            })
            ->rawColumns(['status', 'action', 'from', 'to'])
            ->make(true);
    }

    function headerSeri($id)
    {
        $d = GudangKarantinaNoseri::find($id);
        return response()->json([
            'noser' => $d->noseri,
            'in' => $d->detail->header->date_in ? Carbon::parse($d->detail->header->date_in)->isoFormat('D MMM YYYY') : '-',
            'out' => $d->detail->header->date_out ? Carbon::parse($d->detail->header->date_out)->isoFormat('D MMM YYYY') : '-'
        ]);
    }

    function history_unit($id)
    {
        $cek1 = collect(GudangKarantinaNoseri::whereHas('detail', function ($q) use ($id) {
            $q->where('gbj_id', $id)->where('is_draft', 0);
        })->get());
        $cek = collect(NoseriKeluarGK::whereHas('detail', function ($q) use ($id) {
            $q->where('gbj_id', $id)->where('is_draft', 0);
        })->get());
        $data = $cek->merge($cek1);
        return datatables()->of($data)
            ->addColumn('inn', function ($d) {
                if (empty($d->detail->header->date_in)) {
                    return '-';
                } else {

                    return Carbon::parse($d->detail->header->date_in)->isoFormat('D MMM YYYY');
                }
            })
            ->addColumn('out', function ($d) {
                if (empty($d->detail->header->date_out)) {
                    return '-';
                } else {
                    return Carbon::parse($d->detail->header->date_out)->isoFormat('D MMM YYYY');
                }
            })
            ->addColumn('from', function ($d) {
                if (empty($d->detail->header->dari)) {
                    return '-';
                } else {
                    return '<span class="badge badge-success">' . $d->detail->header->from->nama . '</span>';
                }
            })
            ->addColumn('to', function ($d) {
                if (empty($d->detail->header->ke)) {
                    return '-';
                } else {
                    return '<span class="badge badge-info">' . $d->detail->header->to->nama . '</span>';
                }
            })
            ->addColumn('noser', function ($d) {
                if($d->seri) {
                    return $d->seri->noseri;
                } else {
                    return $d->noseri;
                }
            })
            ->addColumn('layout', function ($d) {
                if($d->seri) {
                    return $d->seri->layout->ruang;
                } else {
                    if (empty($d->layout->ruang)) {
                        return '-';
                    } else {
                        return $d->layout->ruang;
                    }
                }

            })
            ->addColumn('remarks', function ($d) {
                if($d->seri) {
                    return $d->seri->remark;
                } else {
                    if (empty($d->remark)) {
                        return '-';
                    } else {
                        return $d->remark;
                    }
                }

            })
            ->addColumn('perbaikan', function ($d) {
                if($d->seri) {
                    return $d->seri->perbaikan;
                } else {
                    if (empty($d->perbaikan)) {
                        return '-';
                    } else {
                        return $d->perbaikan;
                    }
                }

            })
            ->addColumn('tingkat', function ($d) {
                if($d->seri) {
                    return 'Level ' . $d->seri->tk_kerusakan;
                } else {
                    return 'Level ' . $d->tk_kerusakan;
                }

            })
            ->addColumn('status', function ($d) {
                if($d->seri) {
                    return '<span class="sudah_ditransfer">Sudah Ditransfer</span>';
                } else {
                    if ($d->status == 0) {
                        return '<span class="belum_diterima">Belum Diperbaiki</span>';
                    } else {
                        return '<span class="sudah_diterima">Sudah Diperbaiki</span>';
                    }
                }

            })
            ->addColumn('action', function ($d) {
                if($d->seri) {
                    return ' ';
                } else {
                    return '<a data-toggle="modal" data-target="#unitmodal" class="unitmodal" data-attr=""  data-id="' . $d->id . '">
                        <button class="btn btn-outline-info"><i class="far fa-edit"></i></button>
                        </a>';
                }

            })
            ->rawColumns(['status', 'action', 'from', 'to'])
            ->make(true);
    }

    // draft
    function get_draft_tf()
    {
        $data = GudangKarantina::with('from', 'to')->where('is_draft', 1)->where('is_keluar', 1)->get();
        return datatables()->of($data)
            ->addColumn('out', function ($d) {
                if (isset($d->date_out)) {
                    return Carbon::parse($d->date_out)->isoFormat('D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('in', function ($d) {
                if (isset($d->date_in)) {
                    return Carbon::parse($d->date_in)->isoFormat('D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('too', function ($d) {
                if (isset($d->ke)) {
                    return $d->to->nama;
                } else {
                    return '-';
                }
            })
            ->addColumn('from', function ($d) {
                if (isset($d->dari)) {
                    return $d->from->nama;
                } else {
                    return '-';
                }
            })

            ->addColumn('aksi', function ($d) {
                return '<a href="' . url('gk/transfer/' . $d->id . '') . '" class="btn btn-outline-info"><i class="far fa-edit"></i>Edit Produk</a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    function edit_draft_transfer(Request $request) {
        $cekid = GudangKarantina::find($request->id);

        $sprid = GudangKarantinaDetail::where('gk_id', $cekid->id)->whereNotNull('sparepart_id')->get();
        $arr_spr = [];
        $arr_serispr = [];
        foreach ($sprid as $s) {
            $serisprid = NoseriKeluarGK::with('seri')->where('gk_detail_id', $s->id)->get();
            $arr_spr[] = [
                'sparepart_id' => $s->sparepart_id,
                'qty'           => $s->qty_spr,
                'nama'          => $s->sparepart->nama,
                'kode'          => $s->id,
                'seri'          => $serisprid,

            ];
        }

        $unitid = GudangKarantinaDetail::where('gk_id', $cekid->id)->whereNotNull('gbj_id')->get();
        $arr_unit = [];
        $arr_seriunit = [];
        foreach ($unitid as $u) {
            $seriunitid = NoseriKeluarGK::with('seri')->where('gk_detail_id', $u->id)->get();
            $arr_unit[] = [
                'gbj_id'        => $u->gbj_id,
                'qty'           => $u->qty_unit,
                'nama'          => $u->units->produk->nama . ' ' . $u->units->nama,
                'kode'          => $u->id,
                'seri'          => $seriunitid,

            ];
        }

        return response()->json([
            'header' => $cekid,
            'unit' => $arr_unit,
            'spr'   => $arr_spr,
        ]);
    }

    function edit_tf($id)
    {
        $data = GudangKarantina::find($id);
        return view('page.gk.transfer.edit', compact('data'));
    }

    function get_draft_terima()
    {
        $data = GudangKarantina::with('from', 'to')->where('is_draft', 1)->where('is_keluar', 0)->get();
        return datatables()->of($data)
            ->addColumn('out', function ($d) {
                if (isset($d->date_out)) {
                    return Carbon::parse($d->date_out)->isoFormat('D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('in', function ($d) {
                if (isset($d->date_in)) {
                    return Carbon::parse($d->date_in)->isoFormat('D MMM YYYY');
                } else {
                    return '-';
                }
            })
            ->addColumn('too', function ($d) {
                if (isset($d->ke)) {
                    return $d->to->nama;
                } else {
                    return '-';
                }
            })
            ->addColumn('from', function ($d) {
                if (isset($d->dari)) {
                    return $d->from->nama;
                } else {
                    return '-';
                }
            })

            ->addColumn('aksi', function ($d) {
                return '<a href="' . url('gk/terimaProduk/' . $d->id . '') . '" class="btn btn-outline-info"><i class="far fa-edit"></i>Edit Produk</a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    function edit_terima($id)
    {
        $did = GudangKarantina::find($id);
        return view('page.gk.terima.edit', compact('did'));
    }

    // history_trx
    function historyAll(Request $request)
    {
        $data = GudangKarantinaDetail::with('sparepart.Spare', 'units.produk', 'header.from', 'header.to')->where('is_draft', 0)->get();
        return datatables()->of($data)
            ->addColumn('jenis', function ($d) {
                if (empty($d->qty_unit)) {
                    return 'Sparepart';
                } else {
                    return 'Unit';
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('tanggal', function ($d) {
                if ($d->is_keluar == 1) {
                    return '<span class="badge badge-info">' . Carbon::parse($d->header->date_out)->isoFormat('D MMMM Y') . '</span>';
                } else {
                    return '<span class="badge badge-success">' . Carbon::parse($d->header->date_in)->isoFormat('D MMMM Y') . '</span>';
                }
            })
            ->addColumn('divisi', function ($d) {
                if ($d->is_keluar == 1) {
                    return '<span class="badge badge-info">' . $d->header->to->nama . '</span>';
                } else {
                    return '<span class="badge badge-success">' . $d->header->from->nama . '</span>';
                }
            })
            ->addColumn('unitt', function ($d) {
                return '-';
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->addColumn('tujuan', function ($d) {
                if (empty($d->header->deskripsi)) {
                    return '-';
                } else {
                    return $d->header->deskripsi;
                }
            })
            ->addColumn('aksi', function ($d) {
                if (empty($d->gbj_id)) {
                    return ' <a data-toggle="modal" data-target="#detailModal" class="detailModal" data-attr="" data-produk="' . $d->sparepart->nama . '"  data-id="' . $d->id . '">
                                <button class="btn btn-outline-info"><i class="far fa-eye"></i> Detail</button>
                            </a>';
                } else {
                    return ' <a data-toggle="modal" data-target="#detailModal" class="detailModal" data-attr="" data-produk="' . $d->units->produk->nama . ' ' . $d->units->nama . '"  data-id="' . $d->id . '">
                                <button class="btn btn-outline-info"><i class="far fa-eye"></i> Detail</button>
                            </a>';
                }
            })
            ->rawColumns(['tanggal', 'aksi', 'divisi'])
            ->make(true);
    }

    function get_noseri_history($id)
    {
        $cek1 = GudangKarantinaNoseri::with('detail')->where('gk_detail_id', $id)->get();
        $cek = NoseriKeluarGK::where('gk_detail_id', $id)->get();
        $data = $cek1->merge($cek);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('noser', function ($d) {
                if($d->seri) {
                    return $d->seri->noseri;
                } else {
                    return $d->noseri;
                }

            })
            ->addColumn('rusak', function ($d) {
                if($d->seri) {
                    return $d->seri->remark;
                } else {
                    return $d->remark;
                }
            })
            ->addColumn('repair', function ($d) {
                if($d->seri) {
                    return $d->seri->perbaikan;
                } else {
                    return $d->perbaikan;
                }
            })
            ->addColumn('layout', function ($d) {
                if($d->seri) {
                    return $d->seri->layout->ruang;
                } else {
                    if (empty($d->layout_id)) {
                        return '-';
                    } else {
                        return $d->layout->ruang;
                    }

                }
            })
            ->addColumn('tingkat', function ($d) {
                if($d->seri) {
                    return 'Level ' . $d->seri->tk_kerusakan;
                } else {
                    if (empty($d->layout_id)) {
                        return '-';
                    } else {
                        return 'Level ' . $d->tk_kerusakan;
                    }

                }

            })
            ->make(true);
    }

    function exportTransaksi(Request $request) {
        return Excel::download(new TransaksiGKExport(), 'transaksi-'.Carbon::now()->format('dmY').'.xlsx');
    }

    function exportProduk() {
        return Excel::download(new ProdukGKExport(), 'sparepart-'.Carbon::now()->format('dmY').'.xlsx');
    }

    function exportUnit() {
        return Excel::download(new UnitGKExport(), 'unit-'.Carbon::now()->format('dmY').'.xlsx');
    }

    function history_by_produk(Request $request)
    {
        $data = GudangKarantinaDetail::with('sparepart.Spare', 'units.produk')->where('is_draft', 0)->groupBy('sparepart_id')->groupBy('gbj_id')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jenis', function ($d) {
                if (empty($d->qty_unit)) {
                    return 'Sparepart';
                } else {
                    return 'Unit';
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('kode', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->spare->kode;
                } else {
                    return $d->units->produk->kode;
                }
            })
            ->addColumn('aksi', function ($d) {
                if (empty($d->gbj_id)) {
                    return '<a class="btn btn-info" href="' . url('gk/transaksi/' . $d->sparepart_id . '') . '?jenis=sparepart" data-id="' . $d->sparepart_id . '" data-jenis="sparepart"><i
                    class="far fa-eye"></i> Detail</a>';
                } else {
                    return '<a class="btn btn-info" href="' . url('gk/transaksi/' . $d->gbj_id . '') . '?jenis=unit" data-id="' . $d->gbj_id . '" data-jenis="unit"><i
                    class="far fa-eye"></i> Detail</a>';
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    function detail_trx($id)
    {
        $d = GudangKarantinaDetail::where('sparepart_id', $id)->orWhere('gbj_id', $id)->where('is_draft', 0)->limit(1)->get();
        $data = GudangKarantina::all()->pluck('updated_at')->groupBy(function ($item) {
            return Carbon::parse($item)->format('Y');
        });
        return view('page.gk.transaksi.show', compact('d', 'data'));
    }

    function get_detail_id($id)
    {
        $d = GudangKarantinaDetail::where('sparepart_id', $id)->orWhere('gbj_id', $id)->where('is_draft', 0)->get();
        foreach ($d as $d) {
            if (empty($d->gbj_id)) {
                $p = SparepartGudang::find($d->sparepart_id);
                $res_p = [
                    'kode' => $p->spare->kode ? $p->spare->kode : '-',
                    'nama' => $p->spare->nama,
                    'desk' => $p->deskripsi,
                    'panjang' => $p->dim_p,
                    'lebar' => $p->dim_l,
                    'tinggi' => $p->dim_t,
                ];
            } else {
                $p = GudangBarangJadi::find($d->gbj_id);
                $res_p = [
                    'kode' => $p->produk->kode ? $p->produk->kode : '-',
                    'nama' => $p->produk->nama . ' ' . $p->nama,
                    'desk' => $p->deskripsi,
                    'panjang' => $p->dim_p,
                    'lebar' => $p->dim_l,
                    'tinggi' => $p->dim_t,
                ];
            }
        }

        return $res_p;
    }

    function get_detail_id1(Request $request)
    {
        $d = GudangKarantinaNoseri::find($request->id);
        return response()->json([
            'id' => $d->id,
            'layout' => $d->layout_id,
            'note' => $d->remark,
            'repair' => $d->perbaikan,
            'tingkat' => $d->tk_kerusakan,
        ]);
    }

    function get_trx($id)
    {
        $cek = GudangKarantinaDetail::where('sparepart_id', $id)->where('is_draft', 0)->orWhere('gbj_id', $id)->get();
        return datatables()->of($cek)
            ->addColumn('tanggal', function ($d) {
                if (empty($d->header->date_in)) {
                    return Carbon::parse($d->header->date_out)->isoFormat('D MMM YYYY');
                } else {
                    return Carbon::parse($d->header->date_in)->isoFormat('D MMM YYYY');
                }
            })
            ->addColumn('divisi', function ($d) {
                if ($d->is_keluar == 1) {
                    return '<span class="badge badge-info">' . $d->header->to->nama . '</span>';
                } else {
                    return '<span class="badge badge-success">' . $d->header->from->nama . '</span>';
                }
            })
            ->addColumn('tujuan', function ($d) {
                if (empty($d->header->deskripsi)) {
                    return '-';
                } else {
                    return $d->header->deskripsi;
                }
            })
            ->addColumn('jml', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->addColumn('aksi', function ($d) {
                return '<button type="button" class="btn btn-outline-info" id="btnDetail"
                            data-id="' . $d->id . '"><i class="far fa-eye"> Detail</i></button>';
            })
            ->rawColumns(['aksi', 'divisi'])
            ->make(true);
    }

    function get_trx_tahun()
    {
        $data = GudangKarantina::select(DB::raw("distinct(date_format(date_out, '%Y')) as tahun"))->distinct()->get();
        return response()->json($data);
    }

    public function grafik_trf(Request $request)
    {
        $data = [];
        $keluar = DB::table('v_gk_keluar')
                ->where([
                    ['tahun', '=', $request->tahun],
                    ['gbj_id', '=', $request->id]
                ])
                ->orWhere([
                    ['tahun', '=', $request->tahun],
                    ['sparepart_id', '=', $request->id]
                ])
               ->get();
        foreach ($keluar as $a) {
            $data[] = [
                'bulan' => $a->bulan,
                'jumlah' => $a->jumlah == null ? 0 : $a->jumlah,
            ];
        }

        $msk = [];
        $masuk = DB::table('v_gk_masuk')
               ->where([
                   ['tahun', '=', $request->tahun],
                   ['gbj_id', '=', $request->id]
               ])
               ->orWhere([
                   ['tahun', '=', $request->tahun],
                   ['sparepart_id', '=', $request->id]
               ])
              ->get();
        foreach ($masuk as $b) {
            $msk[] = [
                'bulan' => $b->bulan,
                'jumlah' => $b->jumlah == null ? 0 : $b->jumlah,
            ];
        }

        return response()->json([
            'data' => $data,
            'masuk' => $msk,
        ]);
    }

    function getSeriDoneSpr(Request $request)
    {
        $data = GudangKarantinaNoseri::whereHas('detail', function ($q) use ($request) {
            $q->where('sparepart_id', $request->sparepart_id);
        })->where('status', 1)->where('is_draft', 0)->where('is_ready', 0)->get();
        $i = 0;
        return datatables()->of($data)
            ->addColumn('kode', function ($d) use ($i) {
                $i++;
                return '<input type="checkbox" class="cb-child" name="noseri_id[][' . $i . ']" value="' . $d->id . '">';
            })
            ->addColumn('kodenew', function ($d) use ($i) {
                $i++;
                return '<input type="checkbox" class="cb-child-new" name="noseri_id[][' . $i . ']" value="' . $d->id . '">';
            })
            ->addColumn('seri', function ($d) {
                return $d->noseri;
            })
            ->addColumn('note', function ($d) {
                return Str::limit($d->remark, 30, '...');
            })
            ->addColumn('tingkat', function ($d) {
                return 'Level ' . $d->tk_kerusakan;
            })
            ->rawColumns(['kode', 'kodenew'])
            ->make(true);
    }

    function getSeriDoneUnit(Request $request)
    {
        $data = GudangKarantinaNoseri::whereHas('detail', function ($q) use ($request) {
            $q->where('gbj_id', $request->gbj_id);
        })->where('status', 1)->where('is_draft', 0)->where('is_ready', 0)->get();
        $i = 0;
        return datatables()->of($data)
            ->addColumn('kode', function ($d) use ($i) {
                $i++;
                return '<input type="checkbox" class="cb-unit" name="noseri_id[][' . $i . ']" value="' . $d->id . '">';
            })
            ->addColumn('seri', function ($d) {
                return $d->noseri;
            })
            ->addColumn('note', function ($d) {
                return Str::limit($d->remark, 30, '...');
            })
            ->addColumn('tingkat', function ($d) {
                return 'Level ' . $d->tk_kerusakan;
            })
            ->rawColumns(['kode'])
            ->make(true);
    }

    function headerNoseriSpr(Request $request)
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) use ($request) {
        })->where('sparepart_id', $request->sparepart_id)->get();

        return $data;
    }

    // terima dari gudang
    function getProdukgudang()
    {
        $data = TFProduksiDetail::whereHas('header', function($q) {
            $q->where('ke', 12);
        })->get()->sortByDesc('header.tgl_masuk');
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('in', function($d) {
                return Carbon::createFromFormat('Y-m-d', $d->header->tgl_keluar)->isoFormat('dddd, D MMM Y');
            })
            ->addColumn('dari', function($d) {
                return $d->header->bagian->divisi->nama;
            })
            ->addColumn('produk', function($d) {
                return $d->produk->produk->nama.' '.$d->produk->nama;
            })
            ->addColumn('jumlah', function($d) {
                return $d->qty.' '.$d->produk->satuan->nama;
            })
            ->addColumn('action', function($d) {
                $seri = NoseriTGbj::where('t_gbj_detail_id', $d->id)->where('status_id', 2)->get();
                $seri_final = NoseriTGbj::where('t_gbj_detail_id', $d->id)->where('status_id', 9)->get();
                $cc = count(($seri_final));
                $c = count($seri);
                if ($c == $cc) {
                    return '<a data-toggle="modal" data-target="#detailModal" class="detailModal" data-attr="" data-produk="' . $d->produk->produk->nama . '" data-variasi="'.$d->produk->nama.'"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-info"><i class="far fa-eye"></i> Detail</button>
                        </a>';
                }
                return '<a data-toggle="modal" data-target="#detailModal" class="detailModal" data-attr="" data-produk="' . $d->produk->produk->nama . '" data-variasi="'.$d->produk->nama.'"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-info"><i class="far fa-eye"></i> Detail</button>
                        </a>
                        <a data-toggle="modal" data-target="#detailModal" class="terimamodal" data-attr="" data-produk="' . $d->produk->produk->nama . '" data-variasi="'.$d->produk->nama.'"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-primary"><i class="far fa-check"></i> Terima</button>
                        </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getSeriProduk($id)
    {
        $data = NoseriTGbj::with('layout', 'detail', 'seri')->where('t_gbj_detail_id', $id)->where('status_id', 9)->get();
        return datatables()->of($data)
            ->addColumn('layout', function ($d) {
                return $d->layout->ruang;
            })
            ->addColumn('noserii', function ($d) {
                return $d->seri->noseri;
            })
            ->addColumn('title', function ($d) {
                return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
            })
            ->make(true);
    }

    function getSeriRakit($id) {
        $data = NoseriTGbj::with('layout', 'detail', 'seri')->where('t_gbj_detail_id', $id)->where('status_id', 2)->get();
        $layout = Layout::where('jenis_id', 3)->get();
        $a = 0;
        return datatables()->of($data)
            ->addColumn('layout', function ($d) use($layout, $a) {
                $opt = '';

                foreach($layout as $l) {
                    $opt .= '<option value="'.$l->id.'">'.$l->ruang.'</option>';
                }
                $a++;
                return '<select name="layout_id[]" id="layout_id" class="form-control layout">
                        ' . $opt . '
                        </select>';

            })
            ->addColumn('noserii', function ($d) {
                return $d->seri->noseri.'<input type="hidden" name="noseri[]" id="noseri[]" value="'.$d->seri->noseri.'">';
            })
            ->addColumn('checkbox', function ($d) {
                return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
            })
            ->addColumn('title', function ($d) {
                return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
            })
            ->rawColumns(['checkbox', 'layout', 'noserii'])
            ->make(true);
    }

    // cek
    function cekNoseriTerima(Request $request)
    {
        $noseri = GudangKarantinaNoseri::whereIn('noseri', $request->noseri)->get();
        $data = GudangKarantinaNoseri::whereIn('noseri', $request->noseri)->get()->count();
        $dataseri = [];
        if ($data > 0) {
            foreach ($noseri as $item) {
                array_push($dataseri, $item->noseri);
            }
            return response()->json(['error' => 'Nomor seri ' . implode(', ', $dataseri) . ' sudah terdaftar']);
        } else {
            return response()->json(['msg' => 'Nomor seri tersimpan']);
        }
    }

    // store
    // tf
    function transfer_by_draft(Request $request)
    {
        // dd($request->all());
        $header = new GudangKarantina();
        $header->date_out = $request->date_out;
        $header->ke = $request->ke;
        $header->deskripsi = $request->deskripsi;
        $header->is_draft = 1;
        $header->is_keluar = 1;
        $header->created_at = Carbon::now();
        $header->created_by = $request->userid;
        $header->save();

        $spr = $request->sparepart;
        if (isset($spr)) {
            foreach ($spr as $k => $v) {
                $sprr = new GudangKarantinaDetail();
                $sprr->gk_id = $header->id;
                $sprr->sparepart_id = $k;
                $sprr->qty_spr = $v['jumlah'];
                $sprr->is_draft = 1;
                $sprr->is_keluar = 1;
                $sprr->created_at = Carbon::now();
                $sprr->created_by = $request->userid;
                $sprr->save();

                $x = $request->noseri;
                $id = $sprr->id;

                for ($i = 0; $i < count($request->noseri[$v]); $i++) {
                    $noseri = new NoseriKeluarGK();
                    $noseri->gk_detail_id = $id;
                    $noseri->noseri_id = json_decode($v['noseri'][$i], true);
                    $noseri->created_at = Carbon::now();
                    $noseri->created_by = $request->userid;
                    $noseri->save();

                    GudangKarantinaNoseri::find(json_decode($v['noseri'][$i], true))->update(['is_ready' => 1]);
                }
            }
        }


        $unit = $request->unit;
        if (isset($unit)) {
            foreach ($unit as $j => $vv) {
                $unitt = new GudangKarantinaDetail();
                $unitt->gk_id = $header->id;
                $unitt->gbj_id = $j;
                $unitt->qty_unit = $vv['jumlah'];
                $unitt->is_draft = 1;
                $unitt->is_keluar = 1;
                $unitt->created_by = $request->userid;
                $unitt->save();

                $idd = $unitt->id;

                for ($m = 0; $m < count($request->seriunit[$vv]); $m++) {
                    $noserii = new NoseriKeluarGK();
                    $noserii->gk_detail_id = $idd;
                    $noserii->noseri_id = json_decode($vv['noseri'][$m], true);
                    $noserii->created_at = Carbon::now();
                    $noserii->created_by = $request->userid;
                    $noserii->save();

                    GudangKarantinaNoseri::find(json_decode($vv['noseri'][$m], true))->update(['is_ready' => 1]);
                }
            }
        }


        return response()->json(['msg' => 'Data Berhasil dirancang']);
    }

    function transfer_by_final(Request $request)
    {
        // dd($request->all());
        $header = new GudangKarantina();
        $header->date_out = $request->date_out;
        $header->ke = $request->ke;
        $header->deskripsi = $request->deskripsi;
        $header->is_draft = 0;
        $header->is_keluar = 1;
        $header->created_at = Carbon::now();
        $header->created_by = $request->userid;
        $header->save();

        $spr = $request->sparepart;

        if (isset($spr)) {
            foreach ($spr as $k => $v) {
                $sprr = new GudangKarantinaDetail();
                $sprr->gk_id = $header->id;
                $sprr->sparepart_id = $k;
                $sprr->qty_spr = $v['jumlah'];
                $sprr->is_draft = 0;
                $sprr->is_keluar = 1;
                $sprr->created_at = Carbon::now();
                $sprr->created_by = $request->userid;
                $sprr->save();

                $x = $request->noseri;
                $id = $sprr->id;

                for ($i = 0; $i < count($v['noseri']); $i++) {
                    $noseri = new NoseriKeluarGK();
                    $noseri->gk_detail_id = $id;
                    // $noseri->noseri_id = json_decode($request->noseri[$v][$i], true);
                    $noseri->noseri_id = json_decode($v['noseri'][$i], true);
                    $noseri->created_at = Carbon::now();
                    $noseri->created_by = $request->userid;
                    $noseri->save();

                    GudangKarantinaNoseri::find(json_decode($v['noseri'][$i], true))->update(['is_ready' => 1]);
                }
            }
        }

        $unit = $request->unit;
        if (isset($unit)) {
            foreach ($unit as $j => $vv) {
                $unitt = new GudangKarantinaDetail();
                $unitt->gk_id = $header->id;
                $unitt->gbj_id = $j;
                $unitt->qty_unit = $vv['jumlah'];
                $unitt->is_draft = 0;
                $unitt->is_keluar = 1;
                $unitt->created_by = $request->userid;
                $unitt->save();

                $idd = $unitt->id;

                for ($m = 0; $m < count($vv['noseri']); $m++) {
                    $noserii = new NoseriKeluarGK();
                    $noserii->gk_detail_id = $idd;
                    $noserii->noseri_id = json_decode($vv['noseri'][$m], true);
                    $noserii->created_at = Carbon::now();
                    $noserii->created_by = $request->userid;
                    $noserii->save();

                    GudangKarantinaNoseri::find(json_decode($vv['noseri'][$m], true))->update(['is_ready' => 1]);
                }
            }
        }

        activity('Gudang Karantina')
        ->causedBy($request->userid)
        ->withProperties(['keterangan' => $request->deskripsi,'sparepart' => $request->sparepart, 'unit' => $request->unit, 'date_out' => $request->date_out, 'ke' => $request->ke])
        ->log('Transfer Produk');

        return response()->json(['msg' => 'Data Berhasil ditransfer']);
    }

    function terima_by_draft(Request $request)
    {
        // dd($request->all());
        $header = new GudangKarantina();
        $header->date_in = $request->date_in;
        $header->dari = $request->dari;
        $header->is_draft = 1;
        $header->is_keluar = 0;
        $header->created_at = Carbon::now();
        $header->created_by = $request->userid;
        $header->save();

        $spr = $request->sparepart_id;

       if (isset($spr)) {
        foreach ($spr as $k => $v) {
            $sprr = new GudangKarantinaDetail();
            $sprr->gk_id = $header->id;
            $sprr->sparepart_id = $request->sparepart_id[$k];
            $sprr->qty_spr = $request->qty_spr[$k];
            $sprr->is_draft = 1;
            $sprr->is_keluar = 0;
            $sprr->created_at = Carbon::now();
            $sprr->created_by = $request->userid;
            $sprr->save();

            $x = $request->noseri;
            $id = $sprr->id;

            foreach($request->noseri[$v] as $kk => $vv) {
                foreach($vv['noseri'] as $kkk => $vvv) {
                    $noseri = new GudangKarantinaNoseri();
                    $noseri->gk_detail_id = $id;
                    $noseri->noseri = strtoupper($vvv);
                    $noseri->remark = $vv['kerusakan'][$kkk];
                    $noseri->tk_kerusakan =  $vv['tingkat'][$kkk];
                    $noseri->is_draft = 1;
                    $noseri->is_keluar = 0;
                    $noseri->created_at = Carbon::now();
                    $noseri->created_by = $request->userid;
                    $noseri->save();
                }
            }
        }
       }

        $unit = $request->gbj_id;
        if (isset($unit)) {
            foreach ($unit as $j => $vv1) {
                $unitt = new GudangKarantinaDetail();
                $unitt->gk_id = $header->id;
                $unitt->gbj_id = $request->gbj_id[$j];
                $unitt->qty_unit = $request->qty_unit[$j];
                $unitt->is_draft = 1;
                $unitt->is_keluar = 0;
                $unitt->created_at = Carbon::now();
                $unitt->created_by = $request->userid;
                $unitt->save();

                $idd = $unitt->id;

                foreach($request->seriunit[$vv1] as $kkey => $vvalue) {
                    foreach($vvalue['noseri'] as $key1 => $value1) {
                        $noserii = new GudangKarantinaNoseri();
                        $noserii->gk_detail_id = $idd;
                        $noserii->noseri = strtoupper($value1);
                        $noserii->remark = $vvalue['kerusakan'][$key1];
                        $noserii->tk_kerusakan = $vvalue['tingkat'][$key1];
                        $noserii->is_draft = 1;
                        $noserii->is_keluar = 0;
                        $noserii->created_at = Carbon::now();
                        $noserii->created_by = $request->userid;
                        $noserii->save();
                    }
                }
            }
        }

        return response()->json(['msg' => 'Data Berhasil dirancang']);
    }

    function terima_by_final(Request $request)
    {
        // dd($request->all());
        $header = new GudangKarantina();
        $header->date_in = $request->date_in;
        $header->dari = $request->dari;
        $header->is_draft = 0;
        $header->is_keluar = 0;
        $header->created_at = Carbon::now();
        $header->created_by = $request->userid;
        $header->save();

        $spr = $request->sparepart_id;

       if (isset($spr)) {
        foreach ($spr as $k => $v) {
            $sprr = new GudangKarantinaDetail();
            $sprr->gk_id = $header->id;
            $sprr->sparepart_id = $request->sparepart_id[$k];
            $sprr->qty_spr = $request->qty_spr[$k];
            $sprr->is_draft = 0;
            $sprr->is_keluar = 0;
            $sprr->created_at = Carbon::now();
            $sprr->created_by = $request->userid;
            $sprr->save();

            $x = $request->noseri;
            $id = $sprr->id;

            foreach($request->noseri[$v] as $kk => $vv) {
                foreach($vv['noseri'] as $kkk => $vvv) {
                    $noseri = new GudangKarantinaNoseri();
                    $noseri->gk_detail_id = $id;
                    $noseri->noseri = strtoupper($vvv);
                    $noseri->remark = $vv['kerusakan'][$kkk];
                    $noseri->tk_kerusakan =  $vv['tingkat'][$kkk];
                    $noseri->is_draft = 0;
                    $noseri->is_keluar = 0;
                    $noseri->created_at = Carbon::now();
                    $noseri->created_by = $request->userid;
                    $noseri->save();
                }
            }
        }
       }

        $unit = $request->gbj_id;
        if (isset($unit)) {
            foreach ($unit as $j => $vv1) {
                $unitt = new GudangKarantinaDetail();
                $unitt->gk_id = $header->id;
                $unitt->gbj_id = $request->gbj_id[$j];
                $unitt->qty_unit = $request->qty_unit[$j];
                $unitt->is_draft = 0;
                $unitt->is_keluar = 0;
                $unitt->created_at = Carbon::now();
                $unitt->created_by = $request->userid;
                $unitt->save();

                $idd = $unitt->id;

                foreach($request->seriunit[$vv1] as $kkey => $vvalue) {
                    foreach($vvalue['noseri'] as $key1 => $value1) {
                        $noserii = new GudangKarantinaNoseri();
                        $noserii->gk_detail_id = $idd;
                        $noserii->noseri = strtoupper($value1);
                        $noserii->remark = $vvalue['kerusakan'][$key1];
                        $noserii->tk_kerusakan = $vvalue['tingkat'][$key1];
                        $noserii->is_draft = 0;
                        $noserii->is_keluar = 0;
                        $noserii->created_at = Carbon::now();
                        $noserii->created_by = $request->userid;
                        $noserii->save();
                    }
                }
            }
        }

        activity('Gudang Karantina')
            ->causedBy($request->userid)
            ->withProperties(['sparepart' => $request->sparepart_id[$k], 'unit' => $request->gbj_id[$j], 'date_in' => $request->date_in, 'dari' => $request->dari])
            ->log('Terima Produk');

        return response()->json(['msg' => 'Data Berhasil Diterima']);
    }

    function edit_draft_terima(Request $request)
    {
        $cekid = GudangKarantina::find($request->id);

        $sprid = GudangKarantinaDetail::where('gk_id', $cekid->id)->whereNotNull('sparepart_id')->get();
        $arr_spr = [];
        $arr_serispr = [];
        foreach ($sprid as $s) {
            $serisprid = GudangKarantinaNoseri::where('gk_detail_id', $s->id)->get();
            foreach($serisprid as $ss) {
                $arr_serispr[] = [
                    'seri' => $ss->noseri,
                    'remark' => $ss->remark,
                    'tingkat' => $ss->tk_kerusakan,
                    'id' => $ss->id
                ];
            }
            $arr_spr[] = [
                'sparepart_id' => $s->sparepart_id,
                'qty'           => $s->qty_spr,
                'nama'          => $s->sparepart->nama,
                'kode'          => $s->id,
                'seri'          => $serisprid,

            ];
        }

        $unitid = GudangKarantinaDetail::where('gk_id', $cekid->id)->whereNotNull('gbj_id')->get();
        $arr_unit = [];
        $arr_seriunit = [];
        foreach ($unitid as $u) {
            $seriunitid = GudangKarantinaNoseri::where('gk_detail_id', $u->id)->get();
            foreach($seriunitid as $uu) {
                $arr_seriunit[] = [
                    'seri' => $uu->noseri,
                    'remark' => $uu->remark,
                    'tingkat' => $uu->tk_kerusakan,
                    'id' => $uu->id
                ];
            }
            $arr_unit[] = [
                'gbj_id'        => $u->gbj_id,
                'qty'           => $u->qty_unit,
                'nama'          => $u->units->produk->nama . ' ' . $u->units->nama,
                'kode'          => $u->id,
                'seri'          => $seriunitid,

            ];
        }

        return response()->json([
            'header' => $cekid,
            'unit' => $arr_unit,
            'spr'   => $arr_spr,
        ]);
    }

    function updateTerima(Request $request)
    {
        $header = GudangKarantina::find($request->id);

        $header->is_draft = 1;
        $header->is_keluar = 0;
        $header->updated_at = Carbon::now();
        $header->updated_by = $request->userid;
        $header->save();

        if (isset($request->sprid)) {
            GudangKarantinaNoseri::whereIn('gk_detail_id', $request->sprid)->delete();
            GudangKarantinaDetail::whereIn('sparepart_id', $request->sparepart_id)->where('gk_id', $header->id)->delete();
            $spr = $request->sparepart_id;
            foreach ($spr as $k => $vv) {
                $sprr = new GudangKarantinaDetail();
                $sprr->gk_id = $header->id;
                $sprr->sparepart_id = $request->sparepart_id[$k];
                $sprr->qty_spr = $request->qty_spr[$k];
                $sprr->is_draft = 1;
                $sprr->is_keluar = 0;
                $sprr->updated_at = Carbon::now();
                $sprr->updated_by = $request->userid;
                $sprr->save();

                $x = $request->noseri;
                $id = $sprr->id;

                for ($i = 0; $i < count($request->noseri[$vv]); $i++) {
                    $noseri = new GudangKarantinaNoseri();
                    $noseri->gk_detail_id = $id;
                    $noseri->noseri = $request->noseri[$vv][$i]["noseri"];
                    $noseri->remark = $request->noseri[$vv][$i]['kerusakan'];
                    // $noseri->tk_kerusakan = $request->noseri[$vv][$i]['tingkat'];
                    $noseri->is_draft = 1;
                    $noseri->is_keluar = 0;
                    $noseri->updated_at = Carbon::now();
                    $noseri->updated_by = $request->userid;
                    $noseri->save();
                }
            }
        } else {
            // return 'b spr';
            $spr = $request->sparepart_id;
            foreach ($spr as $k => $vv) {
                $sprr = new GudangKarantinaDetail();
                $sprr->gk_id = $header->id;
                $sprr->sparepart_id = $request->sparepart_id[$k];
                $sprr->qty_spr = $request->qty_spr[$k];
                $sprr->is_draft = 1;
                $sprr->is_keluar = 0;
                $sprr->updated_at = Carbon::now();
                $sprr->updated_by = $request->userid;
                $sprr->save();

                $x = $request->noseri;
                $id = $sprr->id;

                for ($i = 0; $i < count($request->noseri[$vv]); $i++) {
                    $noseri = new GudangKarantinaNoseri();
                    $noseri->gk_detail_id = $id;
                    $noseri->noseri = $request->noseri[$vv][$i]["noseri"];
                    $noseri->remark = $request->noseri[$vv][$i]['kerusakan'];
                    // $noseri->tk_kerusakan = $request->noseri[$vv][$i]['tingkat'];
                    $noseri->is_draft = 1;
                    $noseri->is_keluar = 0;
                    $noseri->updated_at = Carbon::now();
                    $noseri->updated_by = $request->userid;
                    $noseri->save();
                }
            }
        }


        if (isset($request->unitid)) {
                GudangKarantinaNoseri::whereIn('gk_detail_id', $request->unitid)->delete();
                GudangKarantinaDetail::whereIn('gbj_id', $request->gbj_id)->where('gk_id', $header->id)->delete();
                $unit = $request->gbj_id;
                foreach ($unit as $j => $uu) {
                    $unitt = new GudangKarantinaDetail();
                    $unitt->gk_id = $header->id;
                    $unitt->gbj_id = $request->gbj_id[$j];
                    $unitt->qty_unit = $request->qty_unit[$j];
                    $unitt->is_draft = 1;
                    $unitt->is_keluar = 0;
                    $unitt->updated_at = Carbon::now();
                    $unitt->updated_by = $request->userid;
                    $unitt->save();

                    $idd = $unitt->id;

                    for ($m = 0; $m < count($request->seriunit[$uu]); $m++) {

                        $noserii = new GudangKarantinaNoseri();
                        $noserii->gk_detail_id = $idd;
                        $noserii->noseri = $request->seriunit[$uu][$m]["noseri"];
                        $noserii->remark = $request->seriunit[$uu][$m]['kerusakan'];
                        // $noserii->tk_kerusakan = $request->seriunit[$uu][$m]['tingkat'];
                        $noserii->is_draft = 1;
                        $noserii->is_keluar = 0;
                        $noserii->updated_at = Carbon::now();
                        $noserii->updated_by = $request->userid;
                        $noserii->save();
                    }
                }
        } else {
            // return 'b unit';
            $unit = $request->gbj_id;
            foreach ($unit as $j => $uu) {
                    $unitt = new GudangKarantinaDetail();
                    $unitt->gk_id = $header->id;
                    $unitt->gbj_id = $request->gbj_id[$j];
                    $unitt->qty_unit = $request->qty_unit[$j];
                    $unitt->is_draft = 1;
                    $unitt->is_keluar = 0;
                    $unitt->updated_at = Carbon::now();
                    $unitt->updated_by = $request->userid;
                    $unitt->save();

                    $idd = $unitt->id;

                    for ($m = 0; $m < count($request->seriunit[$uu]); $m++) {

                        $noserii = new GudangKarantinaNoseri();
                        $noserii->gk_detail_id = $idd;
                        $noserii->noseri = $request->seriunit[$uu][$m]["noseri"];
                        $noserii->remark = $request->seriunit[$uu][$m]['kerusakan'];
                        // $noserii->tk_kerusakan = $request->seriunit[$uu][$m]['tingkat'];
                        $noserii->is_draft = 1;
                        $noserii->is_keluar = 0;
                        $noserii->updated_at = Carbon::now();
                        $noserii->updated_by = $request->userid;
                        $noserii->save();
                    }
                }
        }

        // return response()->json(['msg' => 'Data Rancang berhasil diubah']);
    }

    function updateTerimaFinal(Request $request)
    {
        $header = GudangKarantina::find($request->id);

        $header->is_draft = 0;
        $header->is_keluar = 0;
        $header->updated_at = Carbon::now();
        $header->updated_by = $request->userid;
        $header->save();
        if (isset($request->sprid)) {
                GudangKarantinaNoseri::whereIn('gk_detail_id', $request->sprid)->delete();
                GudangKarantinaDetail::whereIn('sparepart_id', $request->sparepart_id)->where('gk_id', $header->id)->delete();
                $spr = $request->sparepart_id;
                foreach ($spr as $k => $vv) {
                    $sprr = new GudangKarantinaDetail();
                    $sprr->gk_id = $header->id;
                    $sprr->sparepart_id = $request->sparepart_id[$k];
                    $sprr->qty_spr = $request->qty_spr[$k];
                    $sprr->is_draft = 0;
                    $sprr->is_keluar = 0;
                    $sprr->updated_at = Carbon::now();
                    $sprr->updated_by = $request->userid;
                    $sprr->save();

                    $x = $request->noseri;
                    $id = $sprr->id;

                    for ($i = 0; $i < count($request->noseri[$vv]); $i++) {
                        $noseri = new GudangKarantinaNoseri();
                        $noseri->gk_detail_id = $id;
                        $noseri->noseri = $request->noseri[$vv][$i]["noseri"];
                        $noseri->remark = $request->noseri[$vv][$i]['kerusakan'];
                        // $noseri->tk_kerusakan = $request->noseri[$vv][$i]['tingkat'];
                        $noseri->is_draft = 0;
                        $noseri->is_keluar = 0;
                        $noseri->updated_at = Carbon::now();
                        $noseri->updated_by = $request->userid;
                        $noseri->save();
                    }
                }
        } else {
            $spr = $request->sparepart_id;
            foreach ($spr as $k => $vv) {
                $sprr = new GudangKarantinaDetail();
                $sprr->gk_id = $header->id;
                $sprr->sparepart_id = $request->sparepart_id[$k];
                $sprr->qty_spr = $request->qty_spr[$k];
                $sprr->is_draft = 0;
                $sprr->is_keluar = 0;
                $sprr->updated_at = Carbon::now();
                $sprr->updated_by = $request->userid;
                $sprr->save();

                $x = $request->noseri;
                $id = $sprr->id;

                for ($i = 0; $i < count($request->noseri[$vv]); $i++) {
                    $noseri = new GudangKarantinaNoseri();
                    $noseri->gk_detail_id = $id;
                    $noseri->noseri = $request->noseri[$vv][$i]["noseri"];
                    $noseri->remark = $request->noseri[$vv][$i]['kerusakan'];
                    // $noseri->tk_kerusakan = $request->noseri[$vv][$i]['tingkat'];
                    $noseri->is_draft = 0;
                    $noseri->is_keluar = 0;
                    $noseri->updated_at = Carbon::now();
                    $noseri->updated_by = $request->userid;
                    $noseri->save();
                }
            }
        }

        if (isset($request->unitid)) {
            GudangKarantinaNoseri::whereIn('gk_detail_id', $request->unitid)->delete();
                GudangKarantinaDetail::whereIn('gbj_id', $request->gbj_id)->where('gk_id', $header->id)->delete();
                $unit = $request->gbj_id;
                foreach ($unit as $j => $uu) {
                    $unitt = new GudangKarantinaDetail();
                    $unitt->gk_id = $header->id;
                    $unitt->gbj_id = $request->gbj_id[$j];
                    $unitt->qty_unit = $request->qty_unit[$j];
                    $unitt->is_draft = 0;
                    $unitt->is_keluar = 0;
                    $unitt->updated_at = Carbon::now();
                    $unitt->updated_by = $request->userid;
                    $unitt->save();

                    $idd = $unitt->id;

                    for ($m = 0; $m < count($request->seriunit[$uu]); $m++) {

                        $noserii = new GudangKarantinaNoseri();
                        $noserii->gk_detail_id = $idd;
                        $noserii->noseri = $request->seriunit[$uu][$m]["noseri"];
                        $noserii->remark = $request->seriunit[$uu][$m]['kerusakan'];
                        // $noserii->tk_kerusakan = $request->seriunit[$uu][$m]['tingkat'];
                        $noserii->is_draft = 0;
                        $noserii->is_keluar = 0;
                        $noserii->updated_at = Carbon::now();
                        $noserii->updated_by = $request->userid;
                        $noserii->save();
                    }
                }
        } else {
            $unit = $request->gbj_id;
                foreach ($unit as $j => $uu) {
                    $unitt = new GudangKarantinaDetail();
                    $unitt->gk_id = $header->id;
                    $unitt->gbj_id = $request->gbj_id[$j];
                    $unitt->qty_unit = $request->qty_unit[$j];
                    $unitt->is_draft = 0;
                    $unitt->is_keluar = 0;
                    $unitt->updated_at = Carbon::now();
                    $unitt->updated_by = $request->userid;
                    $unitt->save();

                    $idd = $unitt->id;

                    for ($m = 0; $m < count($request->seriunit[$uu]); $m++) {

                        $noserii = new GudangKarantinaNoseri();
                        $noserii->gk_detail_id = $idd;
                        $noserii->noseri = $request->seriunit[$uu][$m]["noseri"];
                        $noserii->remark = $request->seriunit[$uu][$m]['kerusakan'];
                        // $noserii->tk_kerusakan = $request->seriunit[$uu][$m]['tingkat'];
                        $noserii->is_draft = 0;
                        $noserii->is_keluar = 0;
                        $noserii->updated_at = Carbon::now();
                        $noserii->updated_by = $request->userid;
                        $noserii->save();
                    }
                }
        }

        return response()->json(['msg' => 'Data berhasil diterima']);
    }

    function updateTransfer(Request $request) {
        $header = GudangKarantina::find($request->id);
        $header->deskripsi = $request->tujuan;
        $header->is_draft = 0;
        $header->is_keluar = 1;
        $header->updated_at = Carbon::now();
        $header->updated_by = $request->userid;
        $header->save();
        if (isset($request->kodespr)) {
            NoseriKeluarGK::whereIn('gk_detail_id', [$request->kodespr])->delete();
                GudangKarantinaDetail::whereIn('id', [$request->kodespr])->delete();
                foreach($request->data as $ks => $vs) {
                    $spr = new GudangKarantinaDetail();
                    $spr->gk_id = $header->id;
                    $spr->sparepart_id = $ks;
                    $spr->qty_spr = $vs['jumlah'];
                    $spr->is_draft = 0;
                    $spr->is_keluar = 1;
                    $spr->created_at = Carbon::now();
                    $spr->created_by = $request->userid;
                    $spr->save();

                    $did = $spr->id;

                    foreach($vs['noseri'] as $k => $v) {
                        $nspr = new NoseriKeluarGK();
                        $nspr->gk_detail_id = $did;
                        $nspr->noseri_id = $v;
                        $nspr->created_at = Carbon::now();
                        $nspr->created_by = $request->userid;
                        $nspr->save();

                        // GudangKarantinaNoseri::find($v)->update(['is_ready' => 1]);
                    }
                }
        } else {
            foreach($request->data as $ks => $vs) {
                $spr = new GudangKarantinaDetail();
                $spr->gk_id = $header->id;
                $spr->sparepart_id = $ks;
                $spr->qty_spr = $vs['jumlah'];
                $spr->is_draft = 0;
                $spr->is_keluar = 1;
                $spr->created_at = Carbon::now();
                $spr->created_by = $request->userid;
                $spr->save();

                $did = $spr->id;

                foreach($vs['noseri'] as $k => $v) {
                    $nspr = new NoseriKeluarGK();
                    $nspr->gk_detail_id = $did;
                    $nspr->noseri_id = $v;
                    $nspr->created_at = Carbon::now();
                    $nspr->created_by = $request->userid;
                    $nspr->save();

                    // GudangKarantinaNoseri::find($v)->update(['is_ready' => 1]);
                }
            }
        }

        if (isset($request->kodeunit)) {
            NoseriKeluarGK::whereIn('gk_detail_id', [$request->kodeunit])->delete();
                GudangKarantinaDetail::whereIn('id', [$request->kodeunit])->delete();
                foreach($request->dataunit as $ku => $vu) {
                    $unit = new GudangKarantinaDetail();
                    $unit->gk_id = $header->id;
                    $unit->gbj_id = $ku;
                    $unit->qty_unit = $vu['jumlah'];
                    $unit->is_draft = 0;
                    $unit->is_keluar = 1;
                    $unit->created_at = Carbon::now();
                    $unit->created_by = $request->userid;
                    $unit->save();

                    $didd = $unit->id;

                    foreach($vu['noseri'] as $kk => $vv) {
                        $uspr = new NoseriKeluarGK();
                        $uspr->gk_detail_id = $didd;
                        $uspr->noseri_id = $vv;
                        $uspr->created_at = Carbon::now();
                        $uspr->created_by = $request->userid;
                        $uspr->save();

                        // GudangKarantinaNoseri::find($vv)->update(['is_ready' => 1]);
                    }
                }
        } else {
            foreach($request->dataunit as $ku => $vu) {
                $unit = new GudangKarantinaDetail();
                $unit->gk_id = $header->id;
                $unit->gbj_id = $ku;
                $unit->qty_unit = $vu['jumlah'];
                $unit->is_draft = 0;
                $unit->is_keluar = 1;
                $unit->created_at = Carbon::now();
                $unit->created_by = $request->userid;
                $unit->save();

                $didd = $unit->id;

                foreach($vu['noseri'] as $kk => $vv) {
                    $uspr = new NoseriKeluarGK();
                    $uspr->gk_detail_id = $didd;
                    $uspr->noseri_id = $vv;
                    $uspr->created_at = Carbon::now();
                    $uspr->created_by = $request->userid;
                    $uspr->save();

                    // GudangKarantinaNoseri::find($vv)->update(['is_ready' => 1]);
                }
            }
        }

        return response()->json(['msg' => 'Data berhasil di Transfer']);
    }

    function updateTransferDraft(Request $request) {

        $header = GudangKarantina::find($request->id);
        $header->is_draft = 1;
        $header->is_keluar = 1;
        $header->updated_at = Carbon::now();
        $header->updated_by = $request->userid;
        $header->save();
        if (isset($request->kodespr)) {
            NoseriKeluarGK::whereIn('gk_detail_id', [$request->kodespr])->delete();
                GudangKarantinaDetail::whereIn('id', [$request->kodespr])->delete();
                foreach($request->data as $ks => $vs) {
                    $spr = new GudangKarantinaDetail();
                    $spr->gk_id = $header->id;
                    $spr->sparepart_id = $ks;
                    $spr->qty_spr = $vs['jumlah'];
                    $spr->is_draft = 1;
                    $spr->is_keluar = 1;
                    $spr->created_at = Carbon::now();
                    $spr->created_by = $request->userid;
                    $spr->save();

                    $did = $spr->id;

                    foreach($vs['noseri'] as $k => $v) {
                        $nspr = new NoseriKeluarGK();
                        $nspr->gk_detail_id = $did;
                        $nspr->noseri_id = $v;
                        $nspr->created_at = Carbon::now();
                        $nspr->created_by = $request->userid;
                        $nspr->save();
                    }
                }
        } else {
            foreach($request->data as $ks => $vs) {
                $spr = new GudangKarantinaDetail();
                $spr->gk_id = $header->id;
                $spr->sparepart_id = $ks;
                $spr->qty_spr = $vs['jumlah'];
                $spr->is_draft = 1;
                $spr->is_keluar = 1;
                $spr->created_at = Carbon::now();
                $spr->created_by = $request->userid;
                $spr->save();

                $did = $spr->id;

                foreach($vs['noseri'] as $k => $v) {
                    $nspr = new NoseriKeluarGK();
                    $nspr->gk_detail_id = $did;
                    $nspr->noseri_id = $v;
                    $nspr->created_at = Carbon::now();
                    $nspr->created_by = $request->userid;
                    $nspr->save();
                }
            }
        }

        if (isset($request->kodeunit)) {
            NoseriKeluarGK::whereIn('gk_detail_id', [$request->kodeunit])->delete();
                GudangKarantinaDetail::whereIn('id', [$request->kodeunit])->delete();
                foreach($request->dataunit as $ku => $vu) {
                    $unit = new GudangKarantinaDetail();
                    $unit->gk_id = $header->id;
                    $unit->gbj_id = $ku;
                    $unit->qty_unit = $vu['jumlah'];
                    $unit->is_draft = 1;
                    $unit->is_keluar = 1;
                    $unit->created_at = Carbon::now();
                    $unit->created_by = $request->userid;
                    $unit->save();

                    $didd = $unit->id;

                    foreach($vu['noseri'] as $kk => $vv) {
                        $uspr = new NoseriKeluarGK();
                        $uspr->gk_detail_id = $didd;
                        $uspr->noseri_id = $vv;
                        $uspr->created_at = Carbon::now();
                        $uspr->created_by = $request->userid;
                        $uspr->save();
                    }
                }

        } else {
            foreach($request->dataunit as $ku => $vu) {
                $unit = new GudangKarantinaDetail();
                $unit->gk_id = $header->id;
                $unit->gbj_id = $ku;
                $unit->qty_unit = $vu['jumlah'];
                $unit->is_draft = 1;
                $unit->is_keluar = 1;
                $unit->created_at = Carbon::now();
                $unit->created_by = $request->userid;
                $unit->save();

                $didd = $unit->id;

                foreach($vu['noseri'] as $kk => $vv) {
                    $uspr = new NoseriKeluarGK();
                    $uspr->gk_detail_id = $didd;
                    $uspr->noseri_id = $vv;
                    $uspr->created_at = Carbon::now();
                    $uspr->created_by = $request->userid;
                    $uspr->save();
                }
            }
        }
        return response()->json(['msg' => 'Data Tersimpan ke Rancangan']);
    }

    function getNoseriEdit(Request $request) {
        $header = GudangKarantina::find($request->id);

        $cek = GudangKarantinaDetail::where('sparepart_id', $request->sparepart_id)->where('gk_id', $header->id)->get();
        if (count($cek) > 0) {
            foreach($cek as $c => $v) {
                return GudangKarantinaNoseri::whereIn('gk_detail_id', [$v->id])->get();
            }
        }
    }

    function getNoseriEditUnit(Request $request) {
        $header = GudangKarantina::find($request->id);

        $cek = GudangKarantinaDetail::where('gbj_id', $request->gbj_id)->where('gk_id', $header->id)->get();
        if (count($cek) > 0) {
            foreach($cek as $c => $v) {
                return GudangKarantinaNoseri::where('gk_detail_id', $v->id)->get();
            }
        }
    }

    function getOutSeriEdit(Request $request){
        $data = GudangKarantinaNoseri::whereHas('detail', function ($q) use ($request) {
            $q->where('sparepart_id', $request->sparepart_id);
        })->where('status', 1)->where('is_draft', 0)->get();
        $i = 0;
        return datatables()->of($data)
            ->addColumn('kode', function ($d) use ($i) {
                $i++;
                if ($d->is_ready == 1) {
                    return '<input type="checkbox" checked class="cb-child" name="noseri_id[][' . $i . ']" value="' . $d->id . '">';
                } else {
                    return '<input type="checkbox" class="cb-child" name="noseri_id[][' . $i . ']" value="' . $d->id . '">';
                }

            })
            ->addColumn('seri', function ($d) {
                return $d->noseri;
            })
            ->addColumn('note', function ($d) {
                return Str::limit($d->remark, 30, '...');
            })
            ->addColumn('tingkat', function ($d) {
                return 'Level ' . $d->tk_kerusakan;
            })
            ->rawColumns(['kode'])
            ->make(true);
    }

    function getOutSeriEditUnit(Request $request){
        $data = GudangKarantinaNoseri::whereHas('detail', function ($q) use ($request) {
            $q->where('gbj_id', $request->gbj_id);
        })->where('status', 1)->where('is_draft', 0)->get();
        $i = 0;
        return datatables()->of($data)
            ->addColumn('kode', function ($d) use ($i) {
                $i++;
                if ($d->is_ready == 1) {
                    return '<input type="checkbox" checked class="cb-unit" name="noseri_id[][' . $i . ']" value="' . $d->id . '">';
                } else {
                    return '<input type="checkbox" class="cb-unit" name="noseri_id[][' . $i . ']" value="' . $d->id . '">';
                }

            })
            ->addColumn('seri', function ($d) {
                return $d->noseri;
            })
            ->addColumn('note', function ($d) {
                return Str::limit($d->remark, 30, '...');
            })
            ->addColumn('tingkat', function ($d) {
                return 'Level ' . $d->tk_kerusakan;
            })
            ->rawColumns(['kode'])
            ->make(true);
    }

    function deleteDraftTerima(Request $request)
    {
        $noseri_cek = GudangKarantinaNoseri::where('gk_detail_id', $request->id)->delete();
        $cek = GudangKarantinaDetail::find($request->id);
        $cek->delete();
        return response()->json(['msg' => 'Data Berhasil Dihapus']);
    }

    function deleteDraftTransfer(Request $request) {
        $cek = NoseriKeluarGK::where('gk_detail_id', $request->id)->get();
        foreach($cek as $c) {
            GudangKarantinaNoseri::whereIn('id',[$c->noseri_id])->update(['is_ready' => 0]);
        }

        NoseriKeluarGK::where('gk_detail_id', $request->id)->delete();
        $cek = GudangKarantinaDetail::find($request->id);
        $cek->delete();

        return response()->json(['msg' => 'Data Berhasil Dihapus']);
    }

    function deleteNoseriTerima(Request $request) {

    }

    function uncheckNoseri(Request $request) {
        GudangKarantinaNoseri::find($request->id)->update(['is_ready' => 0]);
    }

    function checkNoseri(Request $request) {
        GudangKarantinaNoseri::find($request->id)->update(['is_ready' => 1]);
    }

    // transaksi noseri
    function updateUnit(Request $request)
    {
        $data = GudangKarantinaNoseri::find($request->id);
        $data->layout_id = $request->layout_id;
        $data->remark = $request->remark;
        $data->tk_kerusakan = $request->tk_kerusakan;
        $data->perbaikan = $request->perbaikan;
        $data->status = 1;
        $data->updated_at = Carbon::now();
        $data->updated_by = $request->userid;
        $data->save();

        return response()->json(['msg' => 'Data Berhasil diubah']);
    }

    // dashboard
    function stok34()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_spr', [3, 4])
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_unit', [3, 4])
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function stok510()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_spr', [5, 10])
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_unit', [5, 10])
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function stok10plus()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('qty_spr', '>', 10)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('qty_unit', '>', 10)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function h_stok34()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_spr', [3, 4])
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_unit', [3, 4])
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);
        return count($data);
    }

    function h_stok510()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_spr', [5, 10])
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->whereBetween('qty_unit', [5, 10])
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);

        return count($data);
    }

    function h_stok10plus()
    {
        $spr = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'), 'm_gs.nama as variasi')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('qty_spr', '>', 10)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get());
        $unit = collect(GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'), 'gdg_barang_jadi.nama as variasi')
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('qty_unit', '>', 10)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get());
        $data = $spr->merge($unit);

        return count($data);
    }

    // in
    function in36()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subMonths(6), Carbon::now()->subMonths(3)]);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('in', function ($d) {
                if (empty($d->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->header->date_in));
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function in612()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subMonths(12), Carbon::now()->subMonths(6)]);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('in', function ($d) {
                if (empty($d->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->header->date_in));
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function in1236()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subYears(3), Carbon::now()->subYears(1)]);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('in', function ($d) {
                if (empty($d->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->header->date_in));
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function in36plus()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->where('date_in', '<=', Carbon::now()->subYears(3));
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('in', function ($d) {
                if (empty($d->header->date_in)) {
                    return '-';
                } else {
                    return date('d-m-Y', strtotime($d->header->date_in));
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->gbj_id)) {
                    return $d->sparepart->nama;
                } else {
                    return $d->units->produk->nama . ' ' . $d->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->qty_unit)) {
                    return $d->qty_spr . ' Unit';
                } else {
                    return $d->qty_unit . ' ' . $d->units->satuan->nama;
                }
            })
            ->make(true);
    }

    function h_in36()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subMonths(6), Carbon::now()->subMonths(3)]);
        })->get();
        return count($data);
    }

    function h_in612()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subMonths(12), Carbon::now()->subMonths(6)]);
        })->get();
        return count($data);
    }

    function h_in1236()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->whereBetween('date_in', [Carbon::now()->subYears(3), Carbon::now()->subYears(1)]);
        })->get();
        return count($data);
    }

    function h_in36plus()
    {
        $data = GudangKarantinaDetail::whereHas('header', function ($q) {
            $q->where('date_in', '<=', Carbon::now()->subYears(3));
        })->get();
        return count($data);
    }

    function byLayout()
    {
        $d = GudangKarantinaNoseri::with('detail')->select('*', DB::raw('count(layout_id) as jml'))
            ->where('is_draft', 0)
            ->where('status', 1)
            ->groupBy('layout_id')
            ->groupBy('gk_detail_id')
            ->get();
        return datatables()->of($d)
            ->addIndexColumn()
            ->addColumn('produk', function ($d) {
                if (empty($d->detail->gbj_id)) {
                    return $d->detail->sparepart->nama;
                } else {
                    return $d->detail->units->produk->nama . ' ' . $d->detail->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->detail->qty_unit)) {
                    return $d->jml . ' Unit';
                } else {
                    return $d->jml . ' ' . $d->detail->units->satuan->nama;
                }
            })
            ->addColumn('layout', function ($d) {
                if (empty($d->layout_id)) {
                    return $d->Layout->ruang;
                } else {
                    return $d->layout->ruang;
                }
            })
            ->make(true);
    }

    function byTingkat()
    {
        $d = GudangKarantinaNoseri::with('detail')->select('*', DB::raw('count(tk_kerusakan) as jml'))
            ->where('is_draft', 0)
            ->where('status', 1)
            ->groupBy('tk_kerusakan')
            ->groupBy('gk_detail_id')
            ->get();
        return datatables()->of($d)
            ->addIndexColumn()
            ->addColumn('kode', function ($d) {
                if (empty($d->detail->gbj_id)) {
                    return $d->detail->sparepart->spare->kode;
                } else {
                    return $d->detail->units->produk->product->kode . '' . $d->detail->units->produk->kode;
                }
            })
            ->addColumn('produk', function ($d) {
                if (empty($d->detail->gbj_id)) {
                    return $d->detail->sparepart->nama;
                } else {
                    return $d->detail->units->produk->nama . ' ' . $d->detail->units->nama;
                }
            })
            ->addColumn('jumlah', function ($d) {
                if (empty($d->detail->qty_unit)) {
                    return $d->jml . ' Unit';
                } else {
                    return $d->jml . ' ' . $d->detail->units->satuan->nama;
                }
            })
            ->addColumn('tingkat', function ($d) {
                return 'Level ' . $d->tk_kerusakan;
            })
            ->addColumn('jenis', function ($d) {
                if (empty($d->detail->qty_unit)) {
                    return 'Sparepart';
                } else {
                    return 'Unit';
                }
            })
            ->addColumn('button', function ($d) {
                if (empty($d->detail->gbj_id)) {
                    return '<a href="' . url('gk/gudang/sparepart/' . $d->detail->sparepart_id . '') . '" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i>';
                } else {
                    return '<a href="' . url('gk/gudang/unit/' . $d->detail->gbj_id . '') . '" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i>';
                }
            })
            ->rawColumns(['button'])
            ->make(true);
    }
}
