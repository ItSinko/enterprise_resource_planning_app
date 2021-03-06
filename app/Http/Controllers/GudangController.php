<?php

namespace App\Http\Controllers;

use App\Exports\GBJExportSPB;
use App\Exports\ImportNoseri;
use App\Exports\SpbExport;
use App\Models\DetailEkatalog;
use App\Models\DetailEkatalogProduk;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Divisi;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\GudangBarangJadiHis;
use App\Models\JadwalRakitNoseri;
use App\Models\Layout;
use App\Models\LogSurat;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriBrgJadiLog;
use App\Models\NoseriTGbj;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;
use Mockery\Undefined;

class GudangController extends Controller
{
    function updateStokGudang($id)
    {
        $d = NoseriBarangJadi::whereHas('gudang', function($q) use($id) {
            $q->where('gdg_barang_jadi_id', $id);
        })->where('is_aktif', 1)->count();
        $a = NoseriBarangJadi::whereHas('gudang', function($q) use($id) {
            $q->where('gdg_barang_jadi_id', $id);
        })->where('is_aktif', 1)->where('is_ready', 0)->count();
        GudangBarangJadi::find($id)->update(['stok' => $d, 'stok_siap' => $a]);
    }
    // get
    public function get_data_barang_jadi(Request $request)
    {
        try {
            $data = GudangBarangJadi::with('produk', 'satuan', 'detailpesananproduk')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($data) {
                    return $data->produk->nama . ' ' . $data->nama;
                })
                ->addColumn('kode_produk', function ($data) {
                    return $data->produk->product->kode . '' . $data->produk->kode;
                })
                ->addColumn('jumlah', function ($data) {
                    $d = $data->get_sum_noseri();
                    $a = $data->get_sum_seri_siap();
                    $this->updateStokGudang($data->id);
                    return $d . ' ' . $data->satuan->nama.'<br><span class="badge badge-dark">Stok Siap: '.$a.' '.$data->satuan->nama.'</span>';
                })
                ->addColumn('jumlah1', function ($data) {
                    $d = $data->get_sum_noseri();
                    $ss = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("spa", "") + $data->getJumlahPermintaanPesanan("spb", "");
                    return $d - $ss . ' ' . $data->satuan->nama;
                })
                ->addColumn('kelompok', function ($data) {
                    return $data->produk->KelompokProduk->nama;
                })
                ->addColumn('merk', function ($data) {
                    return $data->produk->merk;
                })
                ->addColumn('action', function ($data) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-success btn-sm" type="button" >
                                <i class="far fa-edit"></i>&nbsp;Edit
                                </button>
                            </a>

                            <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>

                            <a data-toggle="modal" data-target="#stokmodal" class="stokmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-warning btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Daftar Stok
                                </button>
                            </a>';
                })
                ->addColumn('action_direksi', function ($data) {
                    return  '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'action_direksi', 'jumlah'])
                ->make(true);

        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'msg' => $e->getMessage()]);
        }
    }

    function GetBarangJadiByID(Request $request)
    {
        try {
            $data = GudangBarangJadi::with('produk', 'satuan')->where('id', $request->id)->get();
            $dataid = $data->pluck('produk_id');
            $datas = Produk::with('product')->where('id', $dataid)->get();
            return response()->json([
                'data' => $data,
                'nama_produk' => $datas
            ]);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'msg' => $e->getMessage()]);
        }

    }

    function getNoseri(Request $request, $id)
    {
        try {
            $data = NoseriBarangJadi::whereHas('gudang', function ($d) use ($id) {
                $d->where('id', $id);
            })
            ->where([
                'is_aktif' => 1,
                'is_ready' => 0,
                'is_change' => 1,
                'is_delete' => 0
            ])->get();
            $layout = Layout::where('jenis_id', 1)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('ids', function ($d) {
                    return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
                })
                ->addColumn('seri', function ($d) {
                    return '<input type="hidden" class="form-control" id="noseriOri[]" value="' . $d->noseri . '"><input type="text" class="form-control" id="noseri[]" value="' . $d->noseri . '" disabled>';
                })
                ->addColumn('nomor', function($d){
                    return $d->noseri;
                })
                ->addColumn('Layout', function ($d) use ($layout) {
                    $opt = '';
                    foreach ($layout as $l) {
                        if ($d->layout_id == $l->id) {
                            $opt .= '<option value="' . $l->id . '" selected>' . $l->ruang . '</option>';
                        } else {
                            $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                        }
                    }
                    return '<select name="layout_id[]" id="layout_id[]" class="form-control">
                            ' . $opt . '
                            </select>';
                })
                ->addColumn('aksi', function ($d) {
                    return '<a data-toggle="modal" data-target="#viewStock" class="viewStock" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                            <button class="btn btn-outline-info btn-sm" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Detail
                            </button>
                        </a>';
                })
                ->rawColumns(['ids', 'Layout', 'aksi', 'seri'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'msg' => $e->getMessage()]);
        }

    }

    function getNoseriDone(Request $request, $id)
    {
        try {
            $data = NoseriBarangJadi::whereHas('gudang', function ($d) use ($id) {
                $d->where('id', $id);
            })->where([
                'is_aktif' => 1,
                'is_ready' => 1,
                'is_change' => 1,
                'is_delete' => 0
            ])->get();
            $layout = Layout::where('jenis_id', 1)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('ids', function ($d) {
                    return '<input type="checkbox" class="cb-child1" value="' . $d->id . '">';
                })
                ->addColumn('seri', function ($d) {
                    return '<input type="hidden" class="form-control" id="noseriOri[]" value="' . $d->noseri . '"><input type="text" class="form-control" id="noseri[]" value="' . $d->noseri . '" disabled>';
                })
                ->addColumn('nomor', function($d){
                    return $d->noseri;
                })
                ->addColumn('Layout', function ($d) use ($layout) {
                    $opt = '';
                    foreach ($layout as $l) {
                        if ($d->layout_id == $l->id) {
                            $opt .= '<option value="' . $l->id . '" selected>' . $l->ruang . '</option>';
                        } else {
                            $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                        }
                    }
                    return '<select name="layout_id[]" id="layout_id[]" class="form-control">
                            ' . $opt . '
                            </select>';
                })
                ->addColumn('used', function ($d) {
                    return $d->used_by == null ? '-' : $d->pesanan->so;
                })
                ->addColumn('aksi', function ($d) {
                    return '<a data-toggle="modal" data-target="#viewStock" class="viewStock" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                            <button class="btn btn-outline-info btn-sm" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Detail
                            </button>
                        </a>';
                })
                ->rawColumns(['ids', 'Layout', 'aksi', 'seri'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'msg' => $e->getMessage()]);
        }

    }

    function getHistory($id)
    {
        try {
            $data = NoseriBarangJadi::with('from', 'to')->where('noseri', $id)->get()->unique(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d');
            });

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($d) {
                    return Carbon::parse($d->created_at)->isoFormat('dddd, D MMMM Y');
                })
                ->addColumn('dari', function ($d) {
                    return $d->from->nama;
                })->make(true);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'msg' => $e->getMessage()]);
        }
    }

    function getHistorybyProduk()
    {
        try {
            $data = GudangBarangJadi::with('produk', 'satuan', 'detailpesananproduk')->has('TrxProduk')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('stock', function ($d) {
                $dd = $d->get_sum_noseri();
                return $dd . ' ' . $d->satuan->nama;
            })
            ->addColumn('stok_jual', function ($data) {
                if ($data->id) {
                    $d = $data->get_sum_noseri();
                    $ss = $data->getJumlahPermintaanPesanan("ekatalog", "sepakat") + $data->getJumlahPermintaanPesanan("ekatalog", "negosiasi") + $data->getJumlahPermintaanPesanan("spa", "") + $data->getJumlahPermintaanPesanan("spb", "");
                    return $d - $ss . ' ' . $data->satuan->nama;
                } else {
                    return '-';
                }
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
                return '<a class="btn btn-info" href="' . url('gbj/tp/' . $d->id . '') . '"><i
                        class="far fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'msg' => $e->getMessage()]);
        }

    }

    function getAllTransaksi()
    {
        try {
            $data1 = DB::table('t_gbj_detail')
                ->leftjoin('t_gbj as h', 'h.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('gdg_barang_jadi as g', 'g.id', '=', 't_gbj_detail.gdg_brg_jadi_id')
                ->leftjoin('m_satuan as satuan', 'satuan.id', '=', 'g.satuan_id')
                ->leftjoin('produk as prd', 'prd.id', '=', 'g.produk_id')
                ->leftjoin('pesanan as p', 'p.id', '=', 'h.pesanan_id')
                ->leftjoin('m_state as stt', 'stt.id', '=', 'p.log_id')
                ->leftjoin('divisi as d', 'd.id', '=', 'h.dari')
                ->leftjoin('divisi as dd', 'd.id', '=', 'h.ke')
                ->select('p.so', 'p.no_po', 'p.log_id', 'h.tgl_masuk', 'h.tgl_keluar', 'h.jenis', 'h.deskripsi', 't_gbj_detail.qty', 'stt.nama', 'd.nama as dari', 'dd.nama as ke', DB::raw('concat(prd.nama, " ", g.nama) as produkk'), 't_gbj_detail.id')
                ->get();
        $g = datatables()->of($data1)
            ->addIndexColumn()
            ->addColumn('so', function ($d) {
                if (isset($d->so)) {
                    return $d->so;
                } else {
                    return '-';
                }
            })
            ->addColumn('po', function ($d) {
                if (isset($d->no_po)) {
                    return $d->no_po;
                } else {
                    return '-';
                }
            })
            ->addColumn('logs', function($d) {
                if (isset($d->so)) {
                    if ($d->log_id == 9) {
                        $ax = "<span class='badge badge-pill badge-secondary'>".$d->nama."</span>";
                    } else if ($d->log_id == 6) {
                        $ax = "<span class='badge badge-pill badge-warning'>".$d->nama."</span>";
                    } elseif ($d->log_id == 8) {
                        $ax = "<span class='badge badge-pill badge-info'>".$d->nama."</span>";
                    } elseif ($d->log_id == 11) {
                        $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                    } elseif ($d->log_id == 10) {
                        $ax = "<span class='badge badge-pill badge-success'>".$d->nama."</span>";
                    } else {
                        $ax = "<span class='badge badge-pill badge-danger'>".$d->nama."</span>";
                    }

                    return $ax;
                } else {
                    return '-';
                }
            })
            ->addColumn('date_in', function ($d) {
                if (isset($d->tgl_masuk)) {
                    return Carbon::parse($d->tgl_masuk)->isoFormat('D MMMM Y');
                } else {
                    return "-";
                }
            })
            ->addColumn('date_out', function ($d) {
                if (isset($d->tgl_keluar)) {
                    return Carbon::parse($d->tgl_keluar)->isoFormat('D MMMM Y');
                } else {
                    return "-";
                }
            })
            ->addColumn('divisi', function ($d) {
                if ($d->jenis == 'keluar') {
                    return '<span class="badge badge-info">' . $d->ke . '</span>';
                } else {
                    return '<span class="badge badge-success">' . $d->dari . '</span>';
                }
            })
            ->addColumn('tujuan', function ($d) {
                return $d->deskripsi;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty.' Unit';
            })
            ->addColumn('product', function ($d) {
                return $d->produkk;
            })
            ->addColumn('action', function ($d) {
                return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                <button class="btn btn-info"><i
                class="far fa-eye"></i> Detail</button>
                        </a>';
            })
            ->rawColumns(['divisi', 'action', 'logs'])
            ->make(true);

            return $g;
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'msg' => $e->getMessage()]);
        }
    }

    function getDetailAll($id)
    {
        try {
            $data = NoseriTGbj::with('layout', 'detail', 'seri')->where('t_gbj_detail_id', $id)->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('layout', function ($d) {
                if (isset($d->layout->ruang)) {
                    return $d->layout->ruang;
                } else {
                    return '-';
                }
            })
            ->addColumn('seri', function ($d) {
                return $d->seri->noseri;
            })
            ->addColumn('checkbox', function ($d) {
                return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
            })
            ->addColumn('title', function ($d) {
                return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
            })
            ->rawColumns(['checkbox', 'layout'])
            ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'msg' => $e->getMessage()]);
        }

    }

    function getDetailHistory($id)
    {
        try {
            $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->where('gdg_brg_jadi_id', $id)->get();

        return datatables()->of($data1)
            ->addIndexColumn()
            ->addColumn('so', function ($d) {
                if (isset($d->header->pesanan_id)) {
                    return $d->header->pesanan->so;
                } else {
                    return '-';
                }
            })
            ->addColumn('po', function ($d) {
                if (isset($d->header->pesanan_id)) {
                    return $d->header->pesanan->no_po;
                } else {
                    return '-';
                }
            })
            ->addColumn('logs', function($d) {
                if (isset($d->header->pesanan_id)) {
                    if ($d->header->pesanan->log_id == 9) {
                        $ax = "<span class='badge badge-pill badge-secondary'>".$d->header->pesanan->log->nama."</span>";
                    } else if ($d->header->pesanan->log_id == 6) {
                        $ax = "<span class='badge badge-pill badge-warning'>".$d->header->pesanan->log->nama."</span>";
                    } elseif ($d->header->pesanan->log_id == 8) {
                        $ax = "<span class='badge badge-pill badge-info'>".$d->header->pesanan->log->nama."</span>";
                    } elseif ($d->header->pesanan->log_id == 11) {
                        $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                    } elseif ($d->header->pesanan->log_id == 10) {
                        $ax = "<span class='badge badge-pill badge-success'>".$d->header->pesanan->log->nama."</span>";
                    } else {
                        $ax = "<span class='badge badge-pill badge-danger'>".$d->header->pesanan->log->nama."</span>";
                    }

                    return $ax;
                } else {
                    return '-';
                }
            })
            ->addColumn('date_in', function ($d) {
                if (isset($d->header->tgl_masuk)) {
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
                } else {
                    return "-";
                }
            })
            ->addColumn('date_out', function ($d) {
                if (isset($d->header->tgl_keluar)) {
                    return date('d-m-Y', strtotime($d->header->tgl_keluar));
                } else {
                    return "-";
                }
            })
            ->addColumn('divisi', function ($d) {
                if ($d->header->jenis == 'keluar') {
                    return '<span class="badge badge-info">' . $d->header->divisi->nama . '</span>';
                } else {
                    return '<span class="badge badge-success">' . $d->header->darii->nama . '</span>';
                }
            })
            ->addColumn('tujuan', function ($d) {
                return $d->header->deskripsi;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                            <button type="button" class="btn btn-outline-info"><i
                            class="far fa-eye"> Detail</i></button>
                        </a>';
            })
            ->rawColumns(['divisi', 'action', 'logs'])
            ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'msg' => $e->getMessage()]);
        }

    }

    function getDetailHistorySeri($id)
    {
        try {
            $data = NoseriTGbj::with('seri', 'layout')->where('t_gbj_detail_id', $id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('noser', function ($d) {
                return $d->seri->noseri;
            })
            ->addColumn('posisi', function ($d) {
                return $d->layout_id == null ? '-' : $d->layout->ruang;
                // return $d
            })
            ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'msg' => $e->getMessage()]);
        }
    }

    function getDetailHistory1($id)
    {
        $header = GudangBarangJadi::with('produk')->where('id', $id)->first();
        $data = GudangBarangJadi::with('produk')->where('id', $id)->get();
        $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->where('gdg_brg_jadi_id', $id)->get();
        return view('page.gbj.tp.show', compact('data', 'data1', 'header'));
    }

    function get_data_waiting_approve(Request $request) {
        try {
            $data = NoseriBrgJadiLog::where([
                'status' => 'waiting'
            ])->whereHas('noseri', function($d) use($request){
                $d->where('gdg_barang_jadi_id', $request->gbjid);
            })->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function($d) {
                    return '<input type="checkbox" id="noseriid" name="id" value="'.$d->noseri_id.'">';
                })
                ->addColumn('noseri_lama', function($d) {
                    return $d->data_lama;
                })
                ->addColumn('noseri', function($d) {
                    return $d->data_baru == null ? '-' : $d->data_baru;
                })
                ->editColumn('action',function($d){
                    return $d->action == 'delete' ? '<span class="badge badge-danger">Hapus</span>': '<span class="badge badge-info">Ubah</span>';
                })
                ->addColumn('requested', function($d) {
                    return $d->actionn->nama;
                })
                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function delete_noseri(Request $request) {
        try {
            // dd($request->all());
            $check = NoseriTGbj::whereIn('noseri_id', $request->noseriid);
            $dataseri = [];

            foreach($check->get() as $c) {
                $nbj = NoseriBarangJadi::find($c->noseri_id);
                array_push($dataseri, $nbj->is_ready);
            }
            if (count($dataseri) == 0) {
                $cek = NoseriBarangJadi::whereIn('id', $request->noseriid)->get();

                foreach($cek as $cc) {
                    $nbjj = NoseriBarangJadi::find($cc->id);
                    // return $nbjj;
                    NoseriBrgJadiLog::create([
                            'noseri_id' => $cc->id,
                            'data_lama' => $nbjj->noseri,
                            'action' => 'delete',
                            'action_by' => $request->actionby,
                            'status' => 'waiting'
                        ]);
                    NoseriBarangJadi::find($cc->id)->update(['is_change' => 0, 'is_delete' => 1]);
                }
                return response()->json(['error'=>false, 'msg'=> 'Mohon Tunggu Persetujuan dari Manajer']);
            } else {
                if (empty(array_filter($dataseri))) {
                    if (count($check->get()) > 0) {
                        foreach($check->get() as $d) {
                            $nbjj = NoseriBarangJadi::find($d->noseri_id);

                            NoseriBrgJadiLog::create([
                                    'noseri_id' => $d->noseri_id,
                                    'data_lama' => $nbjj->noseri,
                                    'action' => 'delete',
                                    'action_by' => $request->actionby,
                                    'status' => 'waiting'
                                ]);
                            NoseriBarangJadi::find($d->noseri_id)->update(['is_change' => 0, 'is_delete' => 1]);
                        }

                    }
                    return response()->json(['error'=>false, 'msg'=> 'Mohon Tunggu Persetujuan dari Manajer']);
                } else {
                    return response()->json(['error' => true, 'msg' => 'Noseri Ada yang Sedang Digunakan']);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function list_approve_noseri(Request $request)
    {
        try {
            $data = NoseriBarangJadi::where([
                'is_change' => 0,
                'is_delete' => 1
            ])->groupBy('gdg_barang_jadi_id')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('merk', function($d){
                    return $d->gudang->produk->merk;
                })
                ->addColumn('produk', function($d){
                    return $d->gudang->nama == null ? $d->gudang->produk->nama : $d->gudang->produk->nama.' <b>'.$d->gudang->nama.'</b>';
                })
                ->addColumn('kelompok', function($d){
                    return $d->gudang->produk->KelompokProduk->nama;
                })
                ->addColumn('action', function($d) {
                    return '<a data-toggle="modal" data-target="#deletemodal" class="deletemodal" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                                <button class="btn btn-outline-success btn-sm" type="button" >
                                <i class="far fa-eye"></i> Detail
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'produk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function list_update_noseri(Request $request)
    {
        try {
            $data = NoseriBarangJadi::where([
                'is_change' => 0,
                'is_delete' => 0
            ])->groupBy('gdg_barang_jadi_id')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('merk', function($d){
                    return $d->gudang->produk->merk;
                })
                ->addColumn('produk', function($d){
                    return $d->gudang->nama == null ? $d->gudang->produk->nama : $d->gudang->produk->nama.' <b>'.$d->gudang->nama.'</b>';
                })
                ->addColumn('kelompok', function($d){
                    return $d->gudang->produk->KelompokProduk->nama;
                })
                ->addColumn('action', function($d) {
                    return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                                <button class="btn btn-outline-success btn-sm" type="button" >
                                <i class="far fa-eye"></i> Detail
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'produk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function detail_list_delete_noseri(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::where([
                'status' => 'waiting',
                'action' => 'delete'
            ])->whereHas('noseri', function($d) use($request) {
                $d->where('gdg_barang_jadi_id', $request->gbj);
            })->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function($d) {
                    return '<input type="checkbox" class="cb-child1" id="noseriid" name="id" value="'.$d->noseri_id.'">';
                })
                ->addColumn('produk', function($d){
                    return $d->noseri->gudang->nama == null ? $d->noseri->gudang->produk->nama : $d->noseri->gudang->produk->nama.' <b>'.$d->noseri->gudang->nama.'</b>';
                })
                ->addColumn('merk', function($d){
                    return $d->noseri->gudang->produk->merk;
                })
                ->addColumn('noseri', function($d) {
                    return $d->noseri->noseri;
                })
                ->addColumn('requested', function($d) {
                    return $d->actionn->nama;
                })
                ->addColumn('tgl_aju', function($d){
                    return Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at)->isoFormat('D MMMM YYYY');
                })
                ->rawColumns(['checkbox', 'produk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function detail_list_update_noseri(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::where([
                'status' => 'waiting',
                'action' => 'update',
            ])->whereHas('noseri', function($d) use($request) {
                $d->where('gdg_barang_jadi_id', $request->gbj);
            })->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function($d) {
                    return '<input type="checkbox" class="cb-child" id="noseriid" name="id" value="'.$d->noseri_id.'">';
                })
                ->addColumn('produk', function($d){
                    return $d->noseri->gudang->nama == null ? $d->noseri->gudang->produk->nama : $d->noseri->gudang->produk->nama.' <b>'.$d->noseri->gudang->nama.'</b>';
                })
                ->addColumn('merk', function($d){
                    return $d->noseri->gudang->produk->merk;
                })
                ->addColumn('kelompok', function($d){
                    return $d->noseri->gudang->produk->KelompokProduk->nama;
                })
                ->addColumn('lama', function($d) {
                    return $d->data_lama;
                })
                ->addColumn('baru', function($d) {
                    return $d->data_baru;
                })
                ->addColumn('tgl_aju', function($d){
                    return Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at)->isoFormat('D MMMM YYYY');
                })
                ->addColumn('requested', function($d) {
                    return $d->actionn->nama;
                })
                ->rawColumns(['checkbox', 'produk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }



    function edit_noseri(Request $request) {
        try {
            // dd($request->all());
            $dataseri = [];
            $data = NoseriBarangJadi::whereIn('id',$request->data);
            $check = NoseriBarangJadi::whereIn('noseri', $request->new)->get();
            if (count($check) > 0) {
                foreach($check as $d) {
                    array_push($dataseri, $d->noseri);
                }
                return response()->json(['error' => true, 'msg' => 'Noseri '.implode(', ', $dataseri).' Sudah Terdaftar']);
            } else {
                foreach($data->get() as $k => $c) {
                    NoseriBrgJadiLog::create([
                        'noseri_id' => $c->id,
                        'data_lama' => $c->noseri,
                        'data_baru' => $request->new[$k],
                        'action' => 'update',
                        'action_by' => $request->actionby,
                        'status' => 'waiting'
                    ]);
                    NoseriBarangJadi::find($c->id)->update(['noseri' => $request->new[$k], 'is_change' => 0]);
                }

                return response()->json(['error' => false, 'msg' => 'Mohon Tunggu Persetujuan dari Manager']);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function proses_delete_noseri(Request $request)
    {
        try {
            $check = NoseriTGbj::whereIn('noseri_id', $request->noseriid);
            $dataseri = [];

            foreach($check->get() as $c) {
                $nbj = NoseriBarangJadi::find($c->noseri_id);
                array_push($dataseri, $nbj->is_ready);
            }
            if ($request->is_acc == 'rejected') {
                if (count($dataseri) == 0) {
                    $cek = NoseriBarangJadi::whereIn('id', $request->noseriid)->get();

                    foreach($cek as $cc) {
                        NoseriBrgJadiLog::where('noseri_id', $cc->id)->where([
                            'action' => 'delete',
                            'status' => 'waiting'
                        ])->update(['status' => 'rejected', 'acc_by' => $request->accby]);
                        NoseriBarangJadi::find($cc->id)->update(['is_change' => 1, 'is_delete' => 0]);
                    }
                } else {
                    foreach($check->get() as $ddd) {
                        NoseriBrgJadiLog::where('noseri_id', $ddd->noseri_id)->where([
                            'action' => 'delete',
                            'status' => 'waiting'
                        ])->update(['status' => 'rejected', 'acc_by' => $request->accby]);
                        NoseriBarangJadi::find($ddd->noseri_id)->update(['is_change' => 1, 'is_delete' => 0]);
                    }
                }
                return response()->json(['error'=>false, 'msg'=> 'Penolakan Berhasil Dilakukan']);
            } else {
                if (count($dataseri) == 0) {
                    $cekk = NoseriBarangJadi::whereIn('id', $request->noseriid)->get();

                    foreach($cekk as $ckc) {
                        NoseriBrgJadiLog::where('noseri_id', $ckc->id)->where([
                            'action' => 'delete',
                            'status' => 'waiting'
                        ])->update(['status' => 'approve', 'acc_by' => $request->accby]);
                        NoseriBarangJadi::find($ckc->id)->delete();
                    }
                    return response()->json(['error'=>false, 'msg'=> 'Noseri Berhasil Dihapus']);
                } else {
                    if (empty(array_filter($dataseri))) {
                        foreach($check->get() as $d) {
                            NoseriBrgJadiLog::where('noseri_id', $d->noseri_id)->where([
                                'action' => 'delete',
                                'status' => 'waiting'
                            ])->update(['status' => 'approve', 'acc_by' => $request->accby]);
                            NoseriTGbj::where('noseri_id',$d->noseri_id)->delete();
                            NoseriBarangJadi::find($d->noseri_id)->delete();
                        }
                        return response()->json(['error'=>false, 'msg'=> 'Noseri Berhasil Dihapus']);
                    } else {
                        return 'ok';
                        foreach($check->get() as $dd) {
                            NoseriBrgJadiLog::where('noseri_id', $dd->noseri_id)->where([
                                'action' => 'delete',
                                'status' => 'waiting'
                            ])->update(['status' => 'rejected', 'acc_by' => $request->accby]);
                            NoseriBarangJadi::find($dd->noseri_id)->update(['is_change' => 1]);
                        }
                        return response()->json(['error' => true, 'msg' => 'Noseri Ada yang Sedang Digunakan']);
                    }
                }


            }

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function proses_update_noseri(Request $request)
    {
        try {
            if ($request->is_acc == 'rejected') {
                $a = NoseriBrgJadiLog::whereIn('noseri_id', $request->noseriid)->where([
                    'action' => 'update',
                    'status' => 'waiting'
                    ])->get()->pluck('data_lama');
                for ($i=0; $i < count($a); $i++) {
                    NoseriBrgJadiLog::where('noseri_id', $request->noseriid[$i])->where([
                        'action' => 'update',
                        'status' => 'waiting'
                    ])->update(['status' => 'rejected', 'acc_by' => $request->accby]);
                    NoseriBarangJadi::where('id', $request->noseriid[$i])->update(['is_change' => 1, 'noseri'=> $a[$i]]);
                }
                return response()->json(['error' => false, 'msg' => 'Noseri Batal Diubah']);
            } else {
                // return 'acc';
                $data = NoseriBarangJadi::whereIn('id',$request->noseriid);
                foreach($data->get() as $k => $c) {
                    NoseriBrgJadiLog::where('noseri_id', $c->id)->where([
                        'action' => 'update',
                        'status' => 'waiting'
                    ])->update(['status' => 'approved', 'acc_by' => $request->accby]);
                    NoseriBarangJadi::find($c->id)->update(['is_change' => 1]);
                }
                return response()->json(['error' => false, 'msg' => 'Noseri Berhasil Diubah']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getRakit()
    {
        try {
            $data = DB::select("select tg.tgl_masuk, tgd.gdg_brg_jadi_id, concat(p.nama, ' ', gbj.nama) as produkk, count(tgn.noseri_id) as total from t_gbj_noseri tgn
            left join t_gbj_detail tgd on tgd.id = tgn.t_gbj_detail_id
            left join t_gbj tg on tg.id  = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            where tg.dari = 17 and tg.ke = 13
            group by tg.tgl_masuk, tgd.gdg_brg_jadi_id
            except
            select tg.tgl_masuk, tgd.gdg_brg_jadi_id, concat(p.nama, ' ', gbj.nama) as produkk, count(tgn2.noseri_id) from t_gbj_noseri tgn2
            left join t_gbj_detail tgd on tgd.id = tgn2.t_gbj_detail_id
            left join t_gbj tg on tg.id  = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            where tg.dari = 17 and tg.ke = 13 and tgn2.status_id = 3
            group by tg.tgl_masuk, tgd.gdg_brg_jadi_id");
            // return $data;
        $x = [];
        $y = [];
        foreach ($data as $k) {
            $x[] = $k->tgl_masuk;
            $y[] = $k->gdg_brg_jadi_id;
        }

        // $datax = TFProduksiDetail::with('header')
        //     ->leftJoin('t_gbj as tg', 't_gbj_detail.t_gbj_id', '=', 'tg.id')
        //     ->select('*', DB::raw('sum(t_gbj_detail.qty) as total'))
        //     ->groupBy('tg.tgl_masuk')
        //     ->groupBy('t_gbj_detail.gdg_brg_jadi_id')
        //     ->where('tg.dari', 17)
        //     ->where('tg.ke', 13)
        //     ->whereIn('tg.tgl_masuk', $x)
        //     ->whereIn('t_gbj_detail.gdg_brg_jadi_id', $y)
        //     ->get();
        $datax = TFProduksiDetail::
            leftJoin('t_gbj as tg', 't_gbj_detail.t_gbj_id', '=', 'tg.id')
            ->leftJoin('gdg_barang_jadi as gbj', 'gbj.id','=','t_gbj_detail.gdg_brg_jadi_id')
            ->leftJoin('produk as p', 'p.id','=','gbj.produk_id')
            ->select('tg.tgl_masuk', DB::raw("concat(p.nama, ' ', gbj.nama) as produkk"), DB::raw('sum(t_gbj_detail.qty) as total'), 't_gbj_detail.gdg_brg_jadi_id', )
            ->groupBy('tg.tgl_masuk')
            ->groupBy('t_gbj_detail.gdg_brg_jadi_id')
            ->where('tg.dari', 17)
            ->where('tg.ke', 13)
            ->whereIn('tg.tgl_masuk', $x)
            ->whereIn('t_gbj_detail.gdg_brg_jadi_id', $y)
            ->get();

            // return $datax;

        return datatables()->of($datax)
            ->addIndexColumn()
            ->addColumn('bppb', function($d) {
                $seri_done = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                    $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                    $q->whereHas('header', function ($a) use ($d) {
                        $a->where('tgl_masuk', $d->tgl_masuk)->where('ke', 13)->where('dari', 17);
                    });
                })->where('jenis', 'masuk')->first();

                $nobppb = JadwalRakitNoseri::with('header')->where('noseri', $seri_done->seri->noseri)->first();
                return $nobppb->header->no_bppb == '-' ? '-' : $nobppb->header->no_bppb;
            })
            ->addColumn('tgl_masuk', function ($d) {
                if (isset($d->tgl_masuk)) {
                    return Carbon::parse($d->tgl_masuk)->isoFormat('D MMMM Y');
                } else {
                    return '-';
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produkk;
            })
            ->addColumn('jumlah', function ($d) {
                $seri_done = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                    $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                    $q->whereHas('header', function ($a) use ($d) {
                        $a->where('tgl_masuk', $d->tgl_masuk)->where('ke', 13)->where('dari', 17);
                    });
                })->where('jenis', 'masuk')->where('status_id', 3)->get()->count();

                return $d->total.'<br><span class="badge badge-dark"> Sisa Diterima ' . intval($d->total - $seri_done) . '</span>';
            })
            ->addColumn('action', function ($d) {
                $seri_done = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                    $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                    $q->whereHas('header', function ($a) use ($d) {
                        $a->where('tgl_masuk', $d->tgl_masuk);
                        $a->where('dari', 17);
                    });
                })->where('jenis', 'masuk')->where('status_id', 3)->get()->count();

                $seri = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                    $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                    $q->whereHas('header', function ($a) use ($d) {
                        $a->where('tgl_masuk', $d->tgl_masuk);
                        $a->where('dari', 17);
                    });
                })->where('jenis', 'masuk')->get()->count();

                if ($seri == $seri_done) {
                    return  '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-produk="' . $d->produkk . '" data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>';
                } else {
                    return  '
                            <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-produk="' . $d->produkk . '"data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>
                            <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-produk="' . $d->produkk . '" data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                                <button class="btn btn-outline-primary btn-sm" type="button" >
                                <i class="far fa-edit"></i>&nbsp;Terima
                                </button>
                            </a>

                           ';
                }
            })
            ->rawColumns(['action', 'jumlah'])
            ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getRakitNoseri($id, $value)
    {
       try {
        $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $value) {
            $q->where('gdg_brg_jadi_id', $id);
            $q->whereHas('header', function ($a) use ($value) {
                $a->where('tgl_masuk', $value);
            });
        })->where('status_id', 3)->where('jenis', 'masuk')->get();
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
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getTerimaRakit($id, $value)
    {
        try {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $value) {
                $q->where('gdg_brg_jadi_id', $id);
                $q->whereHas('header', function ($a) use ($value) {
                    $a->where('tgl_masuk', $value)->where('dari', 17)->where('ke', 13);
                });
            })->where('status_id', null)->where('jenis', 'masuk')->get();
            $layout = Layout::where('jenis_id', 1)->get();
            $a = 0;
            return datatables()->of($data)
                ->addColumn('layout', function ($d) use ($layout, $a) {
                    $opt = '';

                    foreach ($layout as $l) {
                        $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                    }
                    $a++;
                    return '<select name="layout_id[]" id="layout_id" class="form-control layout">
                            ' . $opt . '
                            </select>';
                })
                ->addColumn('noserii', function ($d) {
                    return $d->seri->noseri . '<input type="hidden" name="noseri[]" id="noseri[]" value="' . $d->seri->noseri . '">';
                })
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
                })
                ->addColumn('title', function ($d) {
                    return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
                })
                ->rawColumns(['checkbox', 'layout', 'noserii'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getDraftPerakitan(Request $request)
    {
        // detail
        try {
            if ($request->id) {
                $data = TFProduksiDetail::whereHas('header', function ($q) {
                    $q->where('status_id', 1);
                })->with('header', 'produk', 'noseri')->where('t_gbj_id', $request->id)->get();
                return datatables()->of($data)
                    ->addIndexColumn()
                    ->addColumn('nama_produk', function ($d) {
                        return $d->produk->produk->nama . ' ' . $d->produk->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $d->gdg_brg_jadi_id . '">';
                    })
                    ->addColumn('jml', function ($d) {
                        return $d->qty . ' ' . $d->produk->satuan->nama . '<input type="hidden" name="qty[]" id="qty" value="' . $d->qty . '"><input type="hidden" name="tfid[]" id="tfid" value="' . $d->id . '">';
                    })
                    ->addColumn('kode_prd', function ($d) {
                        return $d->gdg_brg_jadi_id;
                    })
                    ->addColumn('action', function ($d) {
                        return '<a data-toggle="modal" data-target="#detail" class="detail" data-attr="" data-var="' . $d->produk->nama . '" data-nama="' . $d->produk->produk->nama . '" data-gbj=' . $d->gdg_brg_jadi_id . ' data-id="' . $d->id . '">
                                    <button class="btn btn-info"><i
                                    class="far fa-eye"></i> Detail</button>
                                </a>';
                    })
                    ->addColumn('in', function ($d) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM Y');
                    })
                    ->addColumn('from', function ($d) {
                        return $d->header->darii->nama;
                    })
                    ->addColumn('tujuan', function ($d) {
                        return $d->header->deskripsi;
                    })
                    ->rawColumns(['action', 'nama_produk', 'jml'])
                    ->make(true);
            } else {
                #header awal
                $data = TFProduksi::with('detail', 'darii')->where(['jenis' => 'masuk', 'status_id' => 1])->get();
                return datatables()->of($data)
                    ->addColumn('in', function ($d) {
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM Y');
                    })
                    ->addColumn('from', function ($d) {
                        return $d->darii->nama;
                    })
                    ->addColumn('tujuan', function ($d) {
                        return $d->deskripsi;
                    })
                    ->addColumn('action', function ($d) {
                        return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                                    <button class="btn btn-info"><i
                                    class="far fa-eye"></i> Detail</button>
                                </a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getNoseriDraftRakit(Request $request)
    {
        try {
            //code...
            $data = NoseriTGbj::with('seri', 'layout')->where('t_gbj_detail_id', $request->t_gbj_detail_id)->get();
            $layout = Layout::where('jenis_id', 1)->get();
            return datatables()->of($data)
                ->addColumn('serii', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('posisi', function ($d) use ($layout) {
                    $opt = '';
                    foreach ($layout as $l) {
                        if ($d->layout_id == $l->id) {
                            $opt .= '<option value="' . $l->id . '" selected>' . $l->ruang . '</option>';
                        } else {
                            $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                        }
                    }
                    return '<select name="layout_id[]" id="layout_id[]" class="form-control">
                            ' . $opt . '
                            </select>';
                })
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child-rancang" value="' . $d->id . '" data-id="' . $d->noseri_id . '">';
                })
                ->rawColumns(['checkbox', 'posisi'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function terimaRakit()
    {
        $layout = Layout::where('jenis_id', 1)->get();
        return view('page.gbj.dp', compact('layout'));
    }

    function ceknoseri(Request $request)
    {
        try {
            //code...
            $data = NoseriBarangJadi::whereIn('noseri', $request->noseri)->get();
            $datarakit = JadwalRakitNoseri::whereIn('noseri', $request->noseri)->get();
            $arr_seri = [];
            $arr_rakit = [];

            if (count($data) == 0 && count($datarakit) == 0) {
                return response()->json(['msg' => 'Noseri tersimpan']);
            } else {
                foreach ($data as $d) {
                    array_push($arr_seri, $d->noseri);
                }

                foreach($datarakit as $c) {
                    array_push($arr_rakit, $c->noseri);
                }

                if (count($data) > 0) {
                    return response()->json(['error' => 'Nomor seri ' . implode(', ', $arr_seri) . ' sudah terdaftar di gudang']);
                }

                if (count($datarakit) > 0) {
                    return response()->json(['error' => 'Nomor seri ' . implode(', ', $arr_rakit) . ' sudah terdaftar di perakitan']);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function allTp()
    {
        $data1 = TFProduksi::with('pesanan')->where([
            ['jenis', '=', 'keluar'],
            ['status_id', '=', 2],
        ])->whereNotNull('pesanan_id')->get();
        return view('page.gbj.tp.tp', compact('data1'));
    }

    function getSODone()
    {
        try {
            //code...
            $data = TFProduksi::with('pesanan')->where([
                ['jenis', '=', 'keluar'],
                ['status_id', '=', 2],
            ])->whereNotNull('pesanan_id')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('noso', function ($d) {
                    return $d->pesanan->so;
                })
                ->addColumn('nopo', function ($d) {
                    return $d->pesanan->no_po;
                })
                ->addColumn('logs', function($d) {
                    if (isset($d->pesanan_id)) {
                        if ($d->pesanan->log_id == 9) {
                            $ax = "<span class='badge badge-pill badge-secondary'>".$d->pesanan->log->nama."</span>";
                        } else if ($d->pesanan->log_id == 6) {
                            $ax = "<span class='badge badge-pill badge-warning'>".$d->pesanan->log->nama."</span>";
                        } elseif ($d->pesanan->log_id == 8) {
                            $ax = "<span class='badge badge-pill badge-info'>".$d->pesanan->log->nama."</span>";
                        } elseif ($d->pesanan->log_id == 11) {
                            $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                        } elseif ($d->pesanan->log_id == 10) {
                            $ax = "<span class='badge badge-pill badge-success'>".$d->pesanan->log->nama."</span>";
                        } else {
                            $ax = "<span class='badge badge-pill badge-danger'>".$d->pesanan->log->nama."</span>";
                        }

                        return $ax;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('customer', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    for ($i = 1; $i < count($name); $i++) {
                        if ($name[1] == 'EKAT') {
                            return $data->pesanan->Ekatalog->Customer->nama;
                        } elseif ($name[1] == 'SPA') {
                            return $data->pesanan->Spa->Customer->nama;
                        } elseif ($name[1] == 'SPB') {
                            return $data->pesanan->Spb->Customer->nama;
                        }
                    }
                })
                ->addColumn('tgl_kontrak', function ($d) {
                    if (isset($d->pesanan->Ekatalog->tgl_kontrak)) {

                        if ($d->pesanan->Ekatalog->Provinsi->status == 1) {
                            return Carbon::parse($d->pesanan->Ekatalog->tgl_kontrak)->subWeeks(5)->format('d-m-Y');
                        }

                        if ($d->pesanan->Ekatalog->Provinsi->status == 2) {
                            return Carbon::parse($d->pesanan->Ekatalog->tgl_kontrak)->subWeeks(4)->format('d-m-Y');
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('aksi', function ($d) {
                    return 12;
                    return '<td><a href="' . url('gbj/export_spb/' . $d->pesanan->id . '') . '">
                            <button class="btn btn-outline-primary"><i class="fas fa-print"></i> Cetak</button>
                            </a></td>';
                })
                ->rawColumns(['aksi', 'logs'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }
    // Export Excell
    function exportSpb($id)
    {
        try {
            return Excel::download(new SpbExport($id), 'SPB.xlsx');
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function download_template_noseri(Request $request)
    {
        try {
            $no = 1;

            $produk = GudangBarangJadi::with('produk', 'satuan', 'detailpesananproduk')->get()->sortBy('produk.nama');

            // spreadsheet
            $spreadsheet = new Spreadsheet();
            $spreadsheet->createSheet();

            // workshet noseri
            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setTitle('Noseri');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Noseri');

            $validation = $spreadsheet->getActiveSheet()->getCell('B2')
                ->getDataValidation();
            $validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
            $validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');

            $validation->setFormula1('\'Produk\'!$C$2:$C$288');
            // $validation->setFormula1('"Item A,Item B,Item C"');
            $validation->setSqref('B2:B10000');

            // check duplicate input noseri
            $duplicate = new Conditional();
            $duplicate->setConditionType(Conditional::CONDITION_DUPLICATES);
            $duplicate->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
            $duplicate->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
            $duplicate->getStyle()->getFill()->getEndColor()->setARGB(Color::COLOR_YELLOW);

            $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('C2:C10000')->getConditionalStyles();
            $conditionalStyles[] = $duplicate;

            $spreadsheet->getActiveSheet()->getStyle('C2:C10000')->setConditionalStyles($conditionalStyles);

            // workshet master
            $spreadsheet->setActiveSheetIndex(1);
            $spreadsheet->getActiveSheet()->setTitle('Produk');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Merk');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

            $noo = 2;
            foreach($produk as $p) {
                $spreadsheet->getActiveSheet()->setCellValue('A'. $noo, $p->id);
                $spreadsheet->getActiveSheet()->setCellValue('B'. $noo, $p->produk->merk);
                $spreadsheet->getActiveSheet()->setCellValue('C'. $noo, $p->produk->nama.' '.$p->nama);
                $noo++;
                $no++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Template Noseri.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function import_noseri(Request $request)
    {
        try {
            $file = $request->file('file_csv');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();

            $file->move(public_path('upload/noseri/'), $filename);

            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/noseri/'. $filename));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range( 2, $row_limit );
            $column_range = range( 'C', $column_limit );
            $startcount = 2;
            $data = array();
            foreach ( $row_range as $row ) {
                $data[] = [
                    'no' =>$sheet->getCell( 'A' . $row )->getValue(),
                    'produk' => $sheet->getCell( 'B' . $row )->getValue(),
                    'noseri' => $sheet->getCell( 'C' . $row )->getValue()
                ];
                $startcount++;
            }

            foreach($data as $d) {
                $aa[] = $d['noseri'];
                $bb[] = $d['produk'];
            }

            $check = NoseriBarangJadi::whereIn('noseri', $aa)->get()->pluck('noseri');
            $check_rakit = JadwalRakitNoseri::whereIn('noseri', $aa)->get()->pluck('noseri');
            $seri = [];
            $seri_rakit = [];
            $sheet1 = $sheet->toArray(null, true, true, true);
            $numrow = 1;
            $html = "<input type='hidden' name='namafile' value='" . $filename . "'>";
            $html .= "<table class='table table-bordered table-striped table-hover tableImport'>
                    <thead>
                    <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Noseri</th>
                    </tr>
                    </thead>
                    <tbody class='overflowAuto'>";
            foreach($sheet1 as $key => $row) {
                $a = $row['A'];
                $b = $row['B'];
                $c = $row['C'];
                if($numrow > 1) {
                    $nis_td = (!empty($c)) ? "" : " style='background: #E07171;'";
                    $html .= "<tr>";
                    $html .= "<td" . $nis_td . ">" . $a . "</td>";
                    $html .= "<td" . $nis_td . ">" . $b . "</td>";
                    $html .= "<td" . $nis_td . ">" . $c . "</td>";
                    $html .= "</tr>";
                }
                $numrow++;
            }
            $html .= "</tbody></table>";

            if(count($check) > 0 || count($check_rakit) > 0) {
                foreach ($check as $item) {
                    array_push($seri, $item);
                }

                foreach ($check_rakit as $itemm) {
                    array_push($seri_rakit, $itemm);
                }

                if (count($check) > 0) {
                    # code...
                    return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri) . ' sudah terdaftar di gudang', 'error' => true, 'data' => $html, 'noseri' => implode(', ', $seri)]);
                }

                if (count($check_rakit) > 0) {
                    # code...
                    return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri_rakit) . ' sudah terdaftar di perakitan', 'error' => true, 'data' => $html, 'noseri' => implode(', ', $seri_rakit)]);
                }

            } else {
                return response()->json(['msg' => 'Noseri Sudah Bisa Diunggah', 'error' => false, 'data' => $html]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function import_noseri_to_db(Request $request)
    {
        try {
            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/noseri/'. $request->namafile));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range( 2, $row_limit );
            $column_range = range( 'C', $column_limit );
            $startcount = 2;
            $data = array();
            foreach ( $row_range as $row ) {
                $data[] = [
                    'no' =>$sheet->getCell( 'A' . $row )->getValue(),
                    'produk' => $sheet->getCell( 'B' . $row )->getValue(),
                    'noseri' => $sheet->getCell( 'C' . $row )->getValue()
                ];
                $startcount++;
            }

            foreach($data as $d) {
                $aa[] = $d['noseri'];
                $bb[] = $d['produk'];
            }

            // array_unique($bb);
            $check = GudangBarangJadi::select('gdg_barang_jadi.id', DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama) as produk"))
                ->whereIn(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $bb)
                ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                ->get()->pluck('id');
            // return ;
            foreach($aa as $key => $nn) {
                $dat_arr[] = [
                    'gdg_barang_jadi_id' => GudangBarangJadi::select('gdg_barang_jadi.id')
                    ->where(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $bb[$key])
                    ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                    ->first()->id,
                    'dari' => 13,
                    'noseri' => $nn,
                    'jenis' => 'MASUK',
                    'is_ready' => 0,
                    'is_aktif' => 1,
                    'created_by' => $request->userid,
                    'created_at' => Carbon::now(),
                ];
            }
            NoseriBarangJadi::insert($dat_arr);
            return response()->json(['msg' => 'Data Berhasil Diunggah', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getListSODone()
    {
        try {
            $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);
            return $data;
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    // store
    function storeNoseri(Request $request)
    {
        try {
            foreach ($request->noseri as $key => $value) {
                $detail = TFProduksiDetail::find($request->t_gbj_detail_id);
                $detail->status_id = 3;
                $detail->updated_at = Carbon::now();
                $detail->save();

                $header = TFProduksi::find($detail->t_gbj_id);
                $header->tgl_masuk = Carbon::now();
                $header->status_id = 3;
                $header->updated_at = Carbon::now();
                $header->save();

                $noserigbj = new NoseriBarangJadi();
                $noserigbj->gdg_barang_jadi_id = $detail->gdg_brg_jadi_id;
                $noserigbj->ke = $header->ke;
                $noserigbj->noseri = $value;
                $noserigbj->jenis = 'MASUK';
                $noserigbj->created_at = Carbon::now();
                $noserigbj->save();
            }

            $gdg = GudangBarangJadi::find($detail->gdg_brg_jadi_id);
            $gdg->stok = $gdg->stok + $detail->qty;
            $gdg->save();

            return response()->json(['msg', 'Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function StoreBarangJadi(Request $request)
    {
        try {
            $id = $request->id;
            if(($request->produk_id && $request->satuan_id) == null) {
                return response()->json(['msg' => 'Produk dan Satuan Harus Diisi', 'error' => true]);
            }else{
                if ($id) {
                    $brg_jadi = GudangBarangJadi::find($id);
                    $brg_his = new GudangBarangJadiHis();

                    if (empty($brg_jadi->id)) {
                        return response()->json(['msg' => 'Data not found']);
                    }

                    $brg_jadi->produk_id = $request->produk_id;
                    $brg_jadi->satuan_id = $request->satuan_id;
                    $brg_jadi->nama = $request->nama == null ? ' ' : $request->nama;
                    $brg_jadi->deskripsi = $request->deskripsi;
                    $image = $request->file('gambar');
                    if ($image) {
                        $path = 'upload/gbj/';
                        $nameImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($path, $nameImage);
                        $brg_jadi->gambar = $nameImage;
                    }
                    $brg_jadi->dim_p = $request->dim_p;
                    $brg_jadi->dim_l = $request->dim_l;
                    $brg_jadi->dim_t = $request->dim_t;
                    $brg_jadi->status = $request->status;
                    $brg_jadi->updated_at = Carbon::now();
                    $brg_jadi->updated_by = $request->userid;
                    $brg_jadi->save();

                    $brg_his->gdg_brg_jadi_id = $brg_jadi->id;
                    $brg_his->produk_id = $request->produk_id;
                    $brg_his->satuan_id = $request->satuan_id;
                    $brg_his->nama = $request->nama;
                    $brg_his->deskripsi = $request->deskripsi;
                    $brg_his->status = $request->status;
                    $brg_his->created_at = Carbon::now();
                    $brg_his->created_by = $request->userid;
                    $brg_his->save();
                } else {
                    $brg_jadi = new GudangBarangJadi();
                    $brg_jadi->produk_id = $request->produk_id;
                    $brg_jadi->satuan_id = $request->satuan_id;
                    $brg_jadi->nama = $request->nama == null ? ' ' : $request->nama;
                    $brg_jadi->stok = 0;
                    $brg_jadi->deskripsi = $request->deskripsi;
                    $image = $request->file('gambar');
                    if ($image) {
                        $path = 'upload/gbj/';
                        $nameImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($path, $nameImage);
                        $brg_jadi->gambar = $nameImage;
                    }
                    $brg_jadi->dim_p = $request->dim_p;
                    $brg_jadi->dim_l = $request->dim_l;
                    $brg_jadi->dim_t = $request->dim_t;
                    $brg_jadi->status = $request->status;
                    $brg_jadi->created_at = Carbon::now();
                    $brg_jadi->created_by = $request->userid;
                    $brg_jadi->save();

                    $brg_his = new GudangBarangJadiHis();
                    $brg_his->gdg_brg_jadi_id = $brg_jadi->id;
                    $brg_his->satuan_id = $request->satuan_id;
                    $brg_his->produk_id = $request->produk_id;
                    $brg_his->nama = $request->nama;
                    $brg_his->stok = 0;
                    $brg_his->deskripsi = $request->deskripsi;
                    $brg_his->status = $request->status;
                    $brg_his->created_at = Carbon::now();
                    $brg_his->created_by = $request->userid;
                    $brg_his->save();
                }
            return response()->json(['msg' => 'Successfully']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function storeDraftRancang(Request $request)
    {
        try {
            $h = new TFProduksi();
            $h->tgl_masuk = $request->tgl_masuk;
            $h->dari = $request->dari;
            $h->deskripsi = $request->deskripsi;
            $h->status_id = 1;
            $h->jenis = 'masuk';
            $h->created_at = Carbon::now();
            $h->created_by = $request->userid;
            $h->save();

            foreach ($request->data as $key => $value) {
                $d = new TFProduksiDetail();
                $d->t_gbj_id = $h->id;
                $d->gdg_brg_jadi_id = $key;
                $d->qty = $value['jumlah'];
                $d->status_id = 1;
                $d->jenis = 'masuk';
                $d->created_at = Carbon::now();
                $d->created_by = $request->userid;
                $d->save();

                foreach ($value['noseri'] as $key1 => $value1) {
                    $nn = new NoseriBarangJadi();
                    $nn->gdg_barang_jadi_id = $key;
                    $nn->dari = $request->dari;
                    $nn->noseri = $value1;
                    $nn->layout_id = $value['layout'][$key1];
                    $nn->jenis = 'MASUK';
                    $nn->is_aktif = 0;
                    $nn->created_by = $request->userid;
                    $nn->save();

                    $n = new NoseriTGbj();
                    $n->t_gbj_detail_id = $d->id;
                    $n->noseri_id = $nn->id;
                    $n->layout_id = $value['layout'][$key1];
                    $n->jenis = 'masuk';
                    $n->status_id = 1;
                    $n->state_id = 2;
                    $n->created_by = $request->userid;
                    $n->save();
                }
            }

            return response()->json(['msg' => 'Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function storeFinalRancang(Request $request)
    {
        try {
            $h = new TFProduksi();
            $h->tgl_masuk = $request->tgl_masuk;
            $h->dari = $request->dari;
            $h->deskripsi = $request->deskripsi;
            $h->status_id = 2;
            $h->jenis = 'masuk';
            $h->created_at = Carbon::now();
            $h->created_by = $request->userid;
            $h->save();

            foreach ($request->data as $key => $value) {
                $d = new TFProduksiDetail();
                $d->t_gbj_id = $h->id;
                $d->gdg_brg_jadi_id = $key;
                $d->qty = $value['jumlah'];
                $d->status_id = 2;
                $d->jenis = 'masuk';
                $d->created_at = Carbon::now();
                $d->created_by = $request->userid;
                $d->save();

                foreach ($value['noseri'] as $key1 => $value1) {
                    $nn = new NoseriBarangJadi();
                    $nn->gdg_barang_jadi_id = $key;
                    $nn->dari = $request->dari;
                    $nn->noseri = strtoupper($value1);
                    $nn->layout_id = $value['layout'][$key1];
                    $nn->jenis = 'MASUK';
                    $nn->is_aktif = 1;
                    $nn->created_by = $request->userid;
                    $nn->save();

                    $n = new NoseriTGbj();
                    $n->t_gbj_detail_id = $d->id;
                    $n->noseri_id = $nn->id;
                    $n->layout_id = $value['layout'][$key1];
                    $n->jenis = 'masuk';
                    $n->status_id = 2;
                    $n->state_id = 3;
                    $n->created_by = $request->userid;
                    $n->save();
                }

                $gdg = GudangBarangJadi::whereIn('id', [$key])->get()->toArray();

                foreach ($gdg as $vv) {
                    $vv['stok'] = $vv['stok'] + $value['jumlah'];

                    GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok'], 'updated_by' => $request->userid]);
                    GudangBarangJadiHis::create([
                        'gdg_brg_jadi_id' => $vv['id'],
                        'stok' => $value['jumlah'],
                        'tgl_masuk' => $request->tgl_masuk,
                        'jenis' => 'MASUK',
                        'created_by' => $request->userid,
                        'created_at' => Carbon::now(),
                        'dari' => $request->dari,
                        'tujuan' => $request->deskripsi,
                    ]);
                }
            }

            return response()->json(['msg' => 'Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function finalDraftRakit(Request $request)
    {
        try {
            foreach ($request->seri as $k => $v) {
                foreach ($v['data'] as $kk => $vv) {
                    NoseriTGbj::where('id', $vv['noseri'])->update(['layout_id' => $vv['layout'], 'status_id' => 2]);
                    $a = NoseriTGbj::where('id', $vv['noseri'])->get();
                    foreach ($a as $a) {
                        NoseriBarangJadi::where('id', $a->noseri_id)->update(['is_aktif' => 1, 'layout_id' => $vv['layout']]);
                        $b =  NoseriBarangJadi::where('id', $a->noseri_id)->get();
                        foreach ($b as $b) {
                            $c =  GudangBarangJadi::where('id', $b->gdg_barang_jadi_id)->first();
                            foreach ($c as $c) {
                                $stok = $c->stok +  $v['jumlah'];
                                // echo $stok;
                                GudangBarangJadi::where('id', $c['id'])->update(['stok' => $stok]);
                            }
                        }
                    }
                }
            }

            $header = TFProduksi::find($request->id);

            $header->status_id = 2;
            $header->updated_at = Carbon::now();
            $header->save();

            return response()->json(['msg' => 'Data Berhasil Diterima']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function storeCekSO(Request $request)
    {
        try {
            $h = Pesanan::find($request->pesanan_id);
            $dt = DetailPesanan::where('pesanan_id', $h->id)->get()->pluck('id')->toArray();
            foreach ($request->data as $key => $value) {
                DetailPesananProduk::whereIn('id', $value)->whereIn('gudang_barang_jadi_id', [$key])
                    ->update(['status_cek' => 4, 'checked_by' => $request->userid]);
            }

            $h->status_cek = 4;
            $h->checked_by = $request->userid;
            $h->log_id = 6;
            $h->save();

            return response()->json(['msg' => 'Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    // select
    function select_layout()
    {
        $data = Layout::where('jenis_id', 1)->get();
        return response()->json($data);
    }

    function select_product()
    {
        $data = Produk::with('product')->get();
        return response()->json($data);
    }

    function select_product_by_id($id)
    {
        $data = Produk::with('product')->find($id);
        return response()->json($data);
    }

    function select_satuan()
    {
        $data = Satuan::all();
        return response()->json($data);
    }

    function select_divisi()
    {
        $data = Divisi::all();
        return response()->json($data);
    }

    function select_gbj()
    {
        $data = GudangBarangJadi::with('produk')->get();
        return response()->json($data);
    }

    // dashboard

    function getNoseriTerima(Request $request, $id)
    {
        try {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($id) {
                $q->where('t_gbj_detail_id', $id);
            })->get();
            return datatables()->of($data)
                ->addColumn('noser', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('posisi', function ($d) {
                    if (isset($d->layout->ruang)) {
                        return $d->layout->ruang;
                    } else {
                        return '-';
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
    // produk
    function h1()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [10, 20])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h2()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [5, 9])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h3()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [1, 4])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h4()
    {

        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->subMonths(6), Carbon::now()->subMonths(3)]);
        })->get();

        return count($data);
    }

    function h5()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->subMonths(12), Carbon::now()->subMonths(6)]);
        })->get();

        return count($data);
    }

    function h6()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::now()->subYears(3), Carbon::now()->subYears(1)]);
        })->get();

        return count($data);
    }

    function h7()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->where('tgl_masuk', '<=', Carbon::now()->subYear(3));
        })->get();

        return count($data);
    }

    function getProdukstok1020()
    {
        try {
            $data = GudangBarangJadi::with('produk')->whereBetween('stok', [10, 20])->orderBy('stok', 'asc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function ($d) {
                return $d->produk->nama . ' ' . $d->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->stok . ' ' . $d->satuan->nama;
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

    function getProdukstok59()
    {
        try {
            $data = GudangBarangJadi::with('produk')->whereBetween('stok', [5, 9])->orderBy('stok', 'asc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function ($d) {
                return $d->produk->nama . ' ' . $d->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->stok . ' ' . $d->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal2" data-attr=""  data-id="' . $d->gdg_brg_jadi_id . '">
                            <button class="btn btn-outline-primary" type="button" >
                            <i class="fas fa-paper-plane"></i>
                            </button>
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

    function getProdukstok14()
    {
        try {
            $data = GudangBarangJadi::with('produk')->whereBetween('stok', [1, 4])->orderBy('stok', 'asc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prd', function ($d) {
                return $d->produk->nama . ' ' . $d->nama;
            })
            ->addColumn('jml', function ($d) {
                return $d->stok . ' ' . $d->satuan->nama;
            })
            ->rawColumns(['action', 'tgl_masuk'])
            ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getProdukIn36()
    {
        try {
            $data = TFProduksiDetail::whereHas('header', function ($q) {
                $q->whereBetween('tgl_masuk', [Carbon::now()->subMonths(6), Carbon::now()->subMonths(3)]);
            })->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {

                        $a = Carbon::now()->diffInDays($d->header->tgl_masuk);

                        if ($a == 1) {
                            return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                        } else if ($a == 2) {
                            return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                        } else if ($a >= 3) {
                            return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty . ' ' . $d->produk->satuan->nama;
                })
                ->rawColumns(['tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getProdukIn612()
    {
        try {
            $data = TFProduksiDetail::whereHas('header', function ($q) {
                $q->whereBetween('tgl_masuk', [Carbon::now()->subMonths(12), Carbon::now()->subMonths(6)]);
            })->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty . ' ' . $d->produk->satuan->nama;
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getProduk1236()
    {
        try {
            $data = TFProduksiDetail::whereHas('header', function ($q) {
                $q->whereBetween('tgl_masuk', [Carbon::now()->subYears(3), Carbon::now()->subYears(1)]);
            })->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty . ' ' . $d->produk->satuan->nama;
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getProduk36Plus()
    {
        try {
            $data = TFProduksiDetail::whereHas('header', function ($q) {
                $q->where('tgl_masuk', '<=', Carbon::now()->subYear(3));
            })->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty . ' ' . $d->produk->satuan->nama;
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getProdukByLayout(Request $request)
    {
        try {
            $data = NoseriBarangJadi::select('*', DB::raw('count(layout_id) as jumlah'))
            ->groupBy('gdg_barang_jadi_id')
            ->groupBy('layout_id')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('prd', function ($d) {
                    return $d->gudang->produk->nama . ' ' . $d->gudang->nama;
                })
                ->addColumn('jml', function ($d) {
                    return $d->jumlah . ' ' . $d->gudang->satuan->nama;
                })
                ->addColumn('layout', function ($d) {
                    if (isset($d->layout_id)) {
                        return $d->layout->ruang;
                    } else {
                        return '-';
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

    // penerimaan
    function hh1()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereBetween('tgl_masuk', [Carbon::yesterday(), Carbon::now()]);
        })->get();
        return count($data);
    }

    function hh2()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereDate('tgl_masuk', '=', Carbon::now()->subDays(2));
        })->get();
        return count($data);
    }

    function hh3()
    {
        $data = TFProduksiDetail::whereHas('header', function ($q) {
            $q->whereDate('tgl_masuk', '<=', Carbon::now()->subDays(3));
        })->get();
        return count($data);
    }

    function getPenerimaanProduk1(Request $request)
    {
        try {
            $data = TFProduksiDetail::whereHas('header', function ($q) {
                $q->whereBetween('tgl_masuk', [Carbon::yesterday(), Carbon::now()]);
            })->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty . ' ' . $d->produk->satuan->nama;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-brg="' . $d->produk->produk->nama . '" data-var="' . $d->produk->nama . '" data-id="' . $d->id . '">
                                <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                                </button>
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

    function getPenerimaanProduk2(Request $request)
    {
        try {
            $data = TFProduksiDetail::whereHas('header', function ($q) {
                $q->whereDate('tgl_masuk', '=', Carbon::now()->subDays(2));
            })->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty . ' ' . $d->produk->satuan->nama;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="' . $d->produk->produk->nama . '" data-var="' . $d->produk->nama . '" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                                </button>
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

    function getPenerimaanProduk3(Request $request)
    {
        try {
            $data = TFProduksiDetail::whereHas('header', function ($q) {
                $q->whereDate('tgl_masuk', '<=', Carbon::now()->subDays(3));
            })->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        $c = Carbon::now()->diffInDays($d->header->tgl_masuk);
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $c . ' Hari</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->produk->produk->nama . ' ' . $d->produk->nama;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty . ' ' . $d->produk->satuan->nama;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="' . $d->produk->produk->nama . '" data-var="' . $d->produk->nama . '" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function getPenerimaanAll()
    {
        try {
            $data = TFProduksiDetail::with('produk', 'header')->where('jenis', 'masuk')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_masuk', function ($d) {
                if ($d->header->tgl_masuk) {

                    $a = Carbon::now()->diffInDays($d->header->tgl_masuk);

                    if ($a == 1) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                    } else if ($a == 2) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                    } else if ($a >= 3) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                    }
                } else {
                    return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
                }
            })
            ->addColumn('product', function ($d) {
                return $d->produk->produk->nama . ' ' . $d->produk->nama;
            })
            ->addColumn('jumlah', function ($d) {
                return $d->qty . ' ' . $d->produk->satuan->nama;
            })
            ->addColumn('action', function ($d) {
                return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-brg="' . $d->produk->produk->nama . '" data-var="' . $d->produk->nama . '"  data-id="' . $d->id . '">
                            <button class="btn btn-outline-primary" type="button" >
                            <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
            })
            ->rawColumns(['action', 'tgl_masuk'])
            ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    // penjualan
    function he1()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
            $q->whereDate('tgl_kontrak', '=', Carbon::now()->subDays(1));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function he2()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
            $q->whereDate('tgl_kontrak', '=', Carbon::now()->subDays(2));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function he3()
    {
        $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
            $q->whereDate('tgl_kontrak', '<=', Carbon::now()->subDays(3));
        })->get());
        $Spa = collect(Pesanan::has('Spa')->get());
        $Spb = collect(Pesanan::has('Spb')->get());

        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return count($data);
    }

    function list_tf1()
    {
        try {
            $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
                $q->whereDate('tgl_kontrak', '=', Carbon::now()->subDays(1));
            })->get());
            $Spa = collect(Pesanan::has('Spa')->get());
            $Spb = collect(Pesanan::has('Spb')->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);

            return datatables()->of($data)
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
                        } else {
                        }
                    }

                    if (empty($data->so)) {
                        return $data->Ekatalog->Customer->nama;
                    }
                })
                ->addColumn('tgl_batas', function ($d) {
                    if (isset($d->Ekatalog->tgl_kontrak)) {
                        $a = Carbon::now()->diffInDays($d->Ekatalog->tgl_kontrak);
                        if ($d->Ekatalog->Provinsi->status == 1) {
                            return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(5)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }

                        if ($d->Ekatalog->Provinsi->status == 2) {
                            return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(4)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_penjualan', function ($data) {
                    if ($data->log_id) {
                        return '<span class="badge badge-light">' . $data->log->nama . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($d) {
                    $x = explode('/', $d->so);
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPA') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPB') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        }
                    }
                })
                ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function list_tf2()
    {
        try {
            $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
                $q->whereDate('tgl_kontrak', '=', Carbon::now()->subDays(2));
            })->get());
            $Spa = collect(Pesanan::has('Spa')->get());
            $Spb = collect(Pesanan::has('Spb')->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);

            return datatables()->of($data)
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
                        } else {
                        }
                    }

                    if (empty($data->so)) {
                        return $data->Ekatalog->Customer->nama;
                    }
                })
                ->addColumn('tgl_batas', function ($d) {
                    if (isset($d->Ekatalog->tgl_kontrak)) {
                        $a = Carbon::now()->diffInDays($d->Ekatalog->tgl_kontrak);
                        if ($d->Ekatalog->Provinsi->status == 1) {
                            return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(5)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }

                        if ($d->Ekatalog->Provinsi->status == 2) {
                            return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(4)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_penjualan', function ($data) {
                    if ($data->log_id) {
                        return '<span class="badge badge-light">' . $data->log->nama . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($d) {
                    $x = explode('/', $d->so);
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPA') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPB') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        }
                    }
                })
                ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function list_tf3()
    {
        try {
            $Ekatalog = collect(Pesanan::whereHas('Ekatalog', function ($q) {
                $q->whereDate('tgl_kontrak', '<=', Carbon::now()->subDays(3));
            })->get());
            $Spa = collect(Pesanan::has('Spa')->get());
            $Spb = collect(Pesanan::has('Spb')->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);

            return datatables()->of($data)
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
                        } else {
                        }
                    }

                    if (empty($data->so)) {
                        return $data->Ekatalog->Customer->nama;
                    }
                })
                ->addColumn('tgl_batas', function ($d) {
                    if (isset($d->Ekatalog->tgl_kontrak)) {
                        $a = Carbon::now()->diffInDays($d->Ekatalog->tgl_kontrak);
                        if ($d->Ekatalog->Provinsi->status == 1) {
                            return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(5)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }

                        if ($d->Ekatalog->Provinsi->status == 2) {
                            return Carbon::createFromFormat('Y-m-d', $d->Ekatalog->tgl_kontrak)->subWeeks(4)->isoFormat('D MMMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_penjualan', function ($data) {
                    if ($data->log_id) {
                        return '<span class="badge badge-light">' . $data->log->nama . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($d) {
                    $x = explode('/', $d->so);
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPA') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPB') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        }
                    }
                })
                ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function detailsale($id, $value)
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
                ->addColumn('nama_produk', function ($data) {
                    if (empty($data->gudangbarangjadi->nama)) {
                        return $data->gudangbarangjadi->produk->nama;
                    } else {
                        return $data->gudangbarangjadi->produk->nama . ' ' . $data->gudangbarangjadi->nama;
                    }
                })
                ->addColumn('jumlah', function ($data) {
                    return $data->detailpesanan->jumlah . ' ' . $data->gudangbarangjadi->satuan->nama;
                })
                ->addColumn('tipe', function ($data) {
                    return $data->gudangbarangjadi->produk->nama;
                })
                ->addColumn('merk', function ($data) {
                    return $data->gudangbarangjadi->produk->merk;
                })
                ->addColumn('button', function ($data) {
                    return '<a type="button" class="noserishow" data-id="' . $data->gudang_barang_jadi_id . '"><i class="fas fa-search"></i></a>';
                })
                ->rawColumns(['button'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function outSO()
    {
        try {
            $data = DB::table('view_dashboard_produk_tdk_so')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('produk', function ($d) {
                    return $d->prd;
                })
                ->addColumn('permintaan', function ($d) {
                    return $d->jumlah;
                })
                ->addColumn('current_stok', function ($d) {
                    return $d->stok;
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function updateSeriLayout(Request $request)
    {
        try {
            $data = NoseriBarangJadi::whereIn('id', $request->cekid)->get();
            foreach ($data as $d) {
                for ($i = 0; $i < count($request->layout); $i++) {
                    NoseriBarangJadi::where('id', $request->cekid[$i])->update(['layout_id' => json_decode($request->layout[$i], true)]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    function addSeri(Request $request)
    {
        try {
            $count = count($request->no_seri);
            for ($i = 0; $i < $count; $i++) {
                NoseriBarangJadi::create([
                    'noseri' => strtoupper($request->no_seri[$i]),
                    'layout_id' => $request->layout[$i],
                    'gdg_barang_jadi_id' => $request->id,
                    'dari' => $request->dari,
                    'created_by' => $request->created_by,
                    'jenis' => 'MASUK',
                    'is_aktif' => 1
                ]);
            }

            $a = GudangBarangJadi::where('id', $request->id)->first();
            $stok = $a->stok + $count;
            // return $stok;
            GudangBarangJadi::where('id', $request->id)->update(['stok' => $stok]);
            GudangBarangJadiHis::create([
                'gdg_brg_jadi_id' => $request->id,
                'stok' => $count,
                'tgl_masuk' => Carbon::now(),
                'jenis' => 'MASUK',
                'created_by' => $request->created_by,
                'created_at' => Carbon::now(),
                'dari' => $request->dari,
            ]);
            return response()->json(['success' => 'Sukses']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }

    }

    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '35';
        } else {
            $days = '28';
        }
        return Carbon::parse($value)->subDays($days);
    }

    function TfbySOFinal(Request $request)
    {
        try {
            $a = TFProduksi::where('pesanan_id', $request->pesanan_id)->first();
            if ($a) {
                foreach ($request->data as $key => $values) {
                    $c = TFProduksiDetail::where('t_gbj_id', $a->id)->where('gdg_brg_jadi_id', $values['prd'])->where('detail_pesanan_produk_id', $key)->first();
                    if ($c) {
                        // return 'aa';
                        foreach ($values['noseri'] as $k => $v) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $c->id,
                                'noseri_id' => $v,
                                'status_id' => 2,
                                'state_id' => 8,
                                'jenis' => 'keluar',
                                'created_at' => Carbon::now(),
                                'created_by' => $request->userid
                            ]);

                            NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                        }

                        $gdg = GudangBarangJadi::whereIn('id', [$values['prd']])->get()->toArray();
                        $i = 0;
                        foreach ($gdg as $vv) {
                            $vv['stok'] = $vv['stok'] - $values['jumlah'];
                            // print_r($vv['stok']);
                            $i++;
                            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                            GudangBarangJadiHis::create([
                                'gdg_brg_jadi_id' => $vv['id'],
                                'stok' => $values['jumlah'],
                                'tgl_masuk' => Carbon::now(),
                                'jenis' => 'KELUAR',
                                'created_by' => $request->userid,
                                'created_at' => Carbon::now(),
                                'ke' => 23,
                                'tujuan' => $request->deskripsi,
                            ]);
                        }
                    } else {
                        $dd = TFProduksiDetail::create([
                            't_gbj_id' => $a->id,
                            'detail_pesanan_produk_id' => $key,
                            'gdg_brg_jadi_id' => $values['prd'],
                            'qty' => $values['jumlah'],
                            'jenis' => 'keluar',
                            'status_id' => 2,
                            'state_id' => 8,
                            'created_at' => Carbon::now(),
                            'created_by' => $request->userid
                        ]);

                        $did = $dd->id;
                        $checked = $request->noseri_id;
                        foreach ($values['noseri'] as $k => $v) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $did,
                                'noseri_id' => $v,
                                'status_id' => 2,
                                'state_id' => 8,
                                'jenis' => 'keluar',
                                'created_at' => Carbon::now(),
                                'created_by' => $request->userid
                            ]);

                            NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                        }

                        $gdg = GudangBarangJadi::whereIn('id', [$values['prd']])->get()->toArray();
                        $i = 0;
                        foreach ($gdg as $vv) {
                            $vv['stok'] = $vv['stok'] - $values['jumlah'];
                            // print_r($vv['stok']);
                            $i++;
                            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                            GudangBarangJadiHis::create([
                                'gdg_brg_jadi_id' => $vv['id'],
                                'stok' => $values['jumlah'],
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
            } else {
                // return 'b';
                $d = TFProduksi::create([
                    'pesanan_id' => $request->pesanan_id,
                    'tgl_keluar' => Carbon::now(),
                    'ke' => 23,
                    'jenis' => 'keluar',
                    'status_id' => 2,
                    'state_id' => 8,
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                $hid = $d->id;
                foreach ($request->data as $key1 => $value1) {
                    $dd = TFProduksiDetail::create([
                        't_gbj_id' => $hid,
                        'detail_pesanan_produk_id' => $key1,
                        'gdg_brg_jadi_id' => $value1['prd'],
                        'qty' => $value1['jumlah'],
                        'jenis' => 'keluar',
                        'status_id' => 2,
                        'state_id' => 8,
                        'created_at' => Carbon::now(),
                        'created_by' => $request->userid
                    ]);

                    $did = $dd->id;
                    $checked = $request->noseri_id;
                    foreach ($value1['noseri'] as $k => $v) {
                        NoseriTGbj::create([
                            't_gbj_detail_id' => $did,
                            'noseri_id' => $v,
                            'status_id' => 2,
                            'state_id' => 8,
                            'jenis' => 'keluar',
                            'created_at' => Carbon::now(),
                            'created_by' => $request->userid
                        ]);

                        NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                    }

                    $gdg = GudangBarangJadi::whereIn('id', [$key1])->get()->toArray();
                    $i = 0;
                    foreach ($gdg as $vv) {
                        $vv['stok'] = $vv['stok'] - $value1['jumlah'];
                        // print_r($vv['stok']);
                        $i++;
                        GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                        GudangBarangJadiHis::create([
                            'gdg_brg_jadi_id' => $vv['id'],
                            'stok' => $value1['jumlah'],
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

            $po = Pesanan::find($request->pesanan_id);

            if ($po->getJumlahPesanan() == $po->cekJumlahkirim()) {
                Pesanan::find($request->pesanan_id)->update(['log_id' => 8]);
            } else {
                Pesanan::find($request->pesanan_id)->update(['log_id' => 6]);
            }
            return response()->json(['msg' => 'Data Terkirim ke QC']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }
}
