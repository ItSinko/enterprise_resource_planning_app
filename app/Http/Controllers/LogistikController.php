<?php

namespace App\Http\Controllers;

use App\Exports\LaporanLogistik;
use App\Exports\LaporanPenjualanAll;
use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;

use App\Models\DetailLogistikPart;
use App\Models\DetailPesananPart;
use App\Models\Ekatalog;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\Ekspedisi;
use App\Models\JadwalPerakitanRw;
use App\Models\Logistik;
use App\Models\LogistikDraft;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriDetailLogistik;
use App\Models\NoseriDetailPesanan;
use Illuminate\Http\Request;
use PDF;

use DomPDF\Options;
use App\Models\Pesanan;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use App\Models\NoseriTGbj;
use App\Models\OutgoingPesananPart;
use App\Models\Pengiriman;
use App\Models\PetiRw;
use App\Models\SeriDetailRw;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use stdClass;

use function PHPUnit\Framework\returnSelf;

class LogistikController extends Controller
{
    public function pdf_surat_jalan($id)
    {
        $data = Logistik::find($id);
        $items = array();
        $data_produk = "";
        if (isset($data->DetailLogistik[0]) && !isset($data->DetailLogistikPart)) {
            $data_prd = DetailLogistik::with(['NoseriDetailLogistik.NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi', 'DetailPesananProduk.GudangBarangJadi.Produk', 'DetailPesananProduk.DetailPesanan.PenjualanProduk'])->where('logistik_id', $id)->get();
            $maxJumlah = 0;
            foreach ($data_prd as $key => $prd) {
                $set_produk[$key] = array(
                    'id' => $prd->id,
                    'nama_alias' => $prd->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias,
                    'nama_produk' => $prd->DetailPesananProduk->GudangBarangJadi->Produk->nama,
                    'variasi' => $prd->DetailPesananProduk->GudangBarangJadi->nama,
                    'detail_pesanan_id' => $prd->DetailPesananProduk->detail_pesanan_id,
                    'detail_pesanan_produk_id' => $prd->detail_pesanan_produk_id,

                );
                foreach ($prd->NoseriDetailLogistik as $key_seri => $seri) {
                    $set_produk[$key]['noseri'][$key_seri] = array(
                        'seri' => $seri->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri
                    );
                }
            }


            foreach ($set_produk as $prd) {
                $jumlahs = intval($prd['jumlah_noseri']);
                $id = $prd["detail_pesanan_id"];
                $nama = $prd["nama_alias"];
                if (!isset($data_prds[$id])) {
                    $data_prds[$id] = array(
                        "id" => $id,
                        "nama" => $nama,
                        "jumlah" => max($maxJumlah, $jumlahs),
                        "detail" => array()
                    );
                }


                $data_prds[$id]["detail"][] = array(
                    "jenis" => 'produk',
                    "kode" => '',
                    "nama" => $prd['nama_produk'] . ' ' . $prd['variasi'],
                    "satuan" => 'UNIT',
                    "seri" => $prd['noseri']
                );
            }

            $items = $data_prds;
        } else if (!isset($data->DetailLogistik[0]) && isset($data->DetailLogistikPart)) {
            // $data_produk = DetailLogistikPart::where('logistik_id', $id)->get();
            $data_prt = DetailLogistikPart::with('DetailPesananPart.Sparepart')->where('logistik_id', $id)->get();
            //Part
            if (count($data_prt) > 0) {
                foreach ($data_prt as $key_p => $prt) {
                    $part[$key_p] = array(
                        "jenis" => 'sparepart',
                        "kode" => $prt->DetailPesananPart->Sparepart->kode,
                        "nama" => $prt->DetailPesananPart->Sparepart->nama,
                        "jumlah" => $prt->jumlah,
                        "satuan" => 'UNIT',
                    );
                }
                $items = $part;
            }
        } else {
            $data_prd = DetailLogistik::with(['NoseriDetailLogistik.NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi', 'DetailPesananProduk.GudangBarangJadi.Produk', 'DetailPesananProduk.DetailPesanan.PenjualanProduk'])->where('logistik_id', $id)->get();
            $data_prt = DetailLogistikPart::with('DetailPesananPart.Sparepart')->where('logistik_id', $id)->get();
            // $data_produk = $data_prd->merge($data_prt);

            //Part
            if (count($data_prt) > 0) {
                foreach ($data_prt as $key_p => $prt) {
                    $part[$key_p] = array(
                        "jenis" => 'sparepart',
                        "kode" => $prt->DetailPesananPart->Sparepart->kode,
                        "nama" => $prt->DetailPesananPart->Sparepart->nama,
                        "jumlah" => $prt->jumlah,
                        "satuan" => 'UNIT',
                    );
                }
                $items = $part;
            }

            //Produks
            if (count($data_prd) > 0) {
                $maxJumlah = 0;
                foreach ($data_prd as $key => $prd) {
                    $set_produk[$key] = array(
                        'id' => $prd->id,
                        'nama_alias' => $prd->DetailPesananProduk->DetailPesanan->PenjualanProduk->nama_alias,
                        'nama_produk' => $prd->DetailPesananProduk->GudangBarangJadi->Produk->nama,
                        'variasi' => $prd->DetailPesananProduk->GudangBarangJadi->nama,
                        'detail_pesanan_id' => $prd->DetailPesananProduk->detail_pesanan_id,
                        'detail_pesanan_produk_id' => $prd->detail_pesanan_produk_id,
                        'jumlah_noseri' => $prd->NoseriDetailLogistik->count()

                    );
                    foreach ($prd->NoseriDetailLogistik as $key_seri => $seri) {
                        $set_produk[$key]['noseri'][$key_seri] = array(
                            'seri' => $seri->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri
                        );
                    }
                }

                foreach ($set_produk as $prd) {
                    $id = $prd["detail_pesanan_id"];
                    $nama = $prd["nama_alias"];
                    $jumlahs = intval($prd['jumlah_noseri']);
                    if (!isset($data_prds[$id])) {
                        $data_prds[$id] = array(
                            "id" => $id,
                            "nama" => $nama,
                            "jumlah" => max($maxJumlah, $jumlahs),
                            "detail" => array()
                        );
                    }


                    $data_prds[$id]["detail"][] = array(
                        "jenis" => 'produk',
                        "kode" => '',
                        "nama" => $prd['nama_produk'] . ' ' . $prd['variasi'],
                        "satuan" => 'UNIT',
                        "seri" => $prd['noseri']
                    );
                }

                $items = array_merge($items, $data_prds);
            }
        }

        //    return response()->json($items);

        if (isset($data->DetailLogistik[0])) {
            $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
            $pesanan = $name[1];
        } else {
            $name = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
            $pesanan = $name[1];
        }

        if ($pesanan == "SPB") {
            return view('page.logistik.surat.surat_jalan_kirim_spb', ['data' => $data, 'data_produk' => $items]);
        } else {

            $customPaper = array(0, 0, 605.44, 788.031);
            $pdf = PDF::loadView('page.logistik.surat.surat_jalan_kirim', ['data' => $data, 'data_produk' => $items])->setPaper($customPaper);
            return $pdf->stream('');
        }
        $customPaper = array(0, 0, 605.44, 788.031);
        // $pdf = PDF::loadView('page.logistik.surat.surat_jalan_kirim', ['data' => $data, 'data_produk' => $data_produk])->setPaper($customPaper);
        // // $pdf = PDF::loadView('page.logistik.pengiriman.print_sj', ['data' => $data, 'data_produk' => $data_produk])->setPaper($customPaper);

    }
    public function cetak_surat_jalan($id)
    {
        $data = LogistikDraft::find($id);

        $log = json_decode($data->isi);
        // return response()->json($log);
        $name = explode('/', $log->so);


        // $page = array();
        // $mergedNoseri = [];


        //     $groupedSerialNumbers = [];

        //    // dd(json_decode($data->isi));
        //     foreach ($log->item as $key => $produk) {
        //         $nama = $key;
        //         // $nama = $item->key;
        //         foreach ($produk->noseri as $serial) {
        //             $groupedSerialNumbers[$serial] = $nama;
        //         }
        //     }


        //     $groupedSerialNumbersFinals = [];

        //     foreach ($groupedSerialNumbers as $serial => $nama) {
        //         $groupedSerialNumbersFinals[] = [
        //             'kode' => $nama,
        //             'serial' => $serial,

        //         ];

        //     }

        //     $chunkedGroups = array_chunk($groupedSerialNumbersFinals, 5);

        //     $data = new stdClass();
        //     $data->hal =  count($chunkedGroups);
        //     $data->pesanan_id = $log->pesanan_id;
        //     $data->customer = $log->customer;
        //     $data->alamat_customer = $log->alamat_customer;
        //     $data->tujuan_kirim = $log->tujuan_kirim;
        //     $data->alamat_kirim = $log->alamat_kirim;
        //     $data->so = $log->so;
        //     $data->no_po = $log->no_po;
        //     $data->tgl_po = $log->tgl_po;
        //     $data->no_sj = $log->nosj;
        //     $data->tgl_sj = $log->tgl_sj;
        //     $data->ekspedisi = $log->ekspedisi;
        //     $data->tgl_kirim = $log->tgl_kirim;
        //     $data->up = $log->up;
        //     $data->noseri = $chunkedGroups;

        // $data = array(
        //     'hal' => count($chunkedGroups),
        //     'pesanan_id' => $log->pesanan_id,
        //     'customer' => $log->customer,
        //     'alamat_customer' => $log->alamat_customer,
        //     'tujuan_kirim' => $log->tujuan_kirim,
        //     'alamat_kirim' => $log->alamat_kirim,
        //     'so' => $log->so,
        //     'no_po' => $log->no_po,
        //     'tgl_po' => $log->tgl_po,
        //     'no_sj' => $log->nosj,
        //     'tgl_sj' => $log->tgl_sj,
        //     'ekspedisi' => $log->ekspedisi,
        //     'tgl_kirim' => $log->tgl_kirim,
        //     'up' => $log->up,
        //     'noseri' => $chunkedGroups
        // );
        //dd($data);
        // ekat, spa

        if ($name[1] == 'SPB') {
            return view('page.logistik.surat.surat_jalan_draft_spb', ['data' => $log]);
        } else {
            $customPaper = array(0, 0, 605.44, 788.031);
            $options = [
                'isPhpEnabled' => true, // Allow PHP code in the view
                'isHtml5ParserEnabled' => true, // Enable HTML5 parser
                'isFontSubsettingEnabled' => true, // Enable font subsetting
            ];

            $pdf = PDF::loadView('page.logistik.surat.surat_jalan_draft', ['data' => $log])
                ->setPaper($customPaper)
                ->setOptions($options); // Use setOptions() to set PDF options
            $pdf->stream();

            // check amount of pages
            $pageAmount = $pdf->getDomPDF()->getCanvas()->get_page_count();

            if ($pageAmount > 1) {
                return view('page.logistik.surat.surat_jalan_draft_test', ['data' => $log]);
            } else {
                return $pdf->stream();
            }
        }

        //         foreach ($log->item as $key => $item) {

        //             $nama = $item->nama;
        //                     $noseri = $item->noseri;

        //                     if (!isset($mergedNoseri[$nama])) {
        //                         $mergedNoseri[$nama] = $noseri;
        //                     } else {
        //                         $mergedNoseri[$nama] = array_merge($mergedNoseri[$nama], $noseri);
        //                     }
        //    }


        //    $mergedNoseriFinal = array();

        // foreach ($mergedNoseri as $nama => $noseriArray) {
        //     $noseriChunks = array_chunk($noseriArray, 5);

        //     foreach ($noseriChunks as $chunkIndex => $chunk) {
        //         $result[] = array(
        //             "nama" => $nama,
        //             "noseri" => $chunk
        //         );
        //     }
        // }


        //    return response()->json(['data' => $data]);

    }
    // public function cetak_surat_jalan($id)
    // {
    //     $data = LogistikDraft::where('pesanan_id',5341)->first();
    //     $obj = json_decode($data->isi);

    //     $mergedNoseri = [];
    //     foreach ($obj->item as $key => $item) {
    //         $nama = $item->nama;
    //         $noseri = $item->noseri;

    //         if (!isset($mergedNoseri[$nama])) {
    //             $mergedNoseri[$nama] = $noseri;
    //         } else {
    //             $mergedNoseri[$nama] = array_merge($mergedNoseri[$nama], $noseri);
    //         }

    //         // $mergedNoseri = array_merge($mergedNoseri,  $item->noseri);
    //     }
    //     $chunkedNoseri = array();

    //     foreach ($mergedNoseri as $nama => $noseriArray) {
    //         $chunks = array_chunk($noseriArray, 5);
    //         $chunkedNoseri[$nama] = $chunks;
    //     }

    //   return response()->json(['data' => $chunkedNoseri]);
    //     $customPaper = array(0,0,605.44,788.031);
    //     $pdf = PDF::loadView('page.logistik.surat.surat_jalan',['data' => $obj])->setPaper($customPaper);
    //     return $pdf->stream('');
    // }

    public function edit_sj($id)
    {
        $data = LogistikDraft::find($id);

        return view('page.logistik.so.editsj', ['data' => $data]);
    }

    public function get_data_select_produk(Request $r, $jenis)
    {
        if ($jenis == 'EKAT') {
            $produk_id = $r->produk_id;
            $data = [];
            $i = 0;
            foreach ($produk_id as $x) {
                $data[$i]['id'] = $x['id'];
                $data[$i]['jenis'] = "produk";
                $data[$i]['jumlah_kirim'] = $x['jumlah_kirim'];
                $data[$i]['array_no_seri'] = $x['array_no_seri'];
                $i++;
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($data) {
                    $res = "";
                    $produk = DetailPesananProduk::find($data['id']);
                    if ($produk->GudangBarangJadi->nama == '') {
                        $res = $produk->GudangBarangJadi->produk->nama;
                    } else {
                        $res = $produk->GudangBarangJadi->produk->nama . ' - ' . $produk->GudangBarangJadi->nama;
                    }
                    $res .= ' <input type="text" class="hide" name="produk_id[]" value="' . $data['id'] . '"/>';
                    return $res;
                })
                ->addColumn('jumlah', function ($data) {
                    return $data['jumlah_kirim'];
                })
                ->addColumn('array', function ($data) {
                    return '<input type="text" name="produk_noseri_id[]" value="' . $data['array_no_seri'] . '"/>';
                })
                ->rawColumns(['nama_produk', 'jumlah', 'array'])
                ->make(true);
        } else {
            $data = [];
            $i = 0;

            $produk_id = $r->produk_id;
            foreach ($produk_id as $x) {
                if ($x['id']) {
                    $data[$i]['id'] = $x['id'];
                    $data[$i]['jenis'] = "produk";
                    $data[$i]['jumlah_kirim'] = $x['jumlah_kirim'];
                    $data[$i]['array_no_seri'] = $x['array_no_seri'];
                    $i++;
                } else {
                    break;
                }
            }

            $part_id = $r->part_id;
            foreach ($part_id as $x) {
                if ($x['id']) {
                    $data[$i]['id'] = $x['id'];
                    $data[$i]['jenis'] = "part";
                    $data[$i]['jumlah_kirim'] = $x['jumlah_kirim'];
                    $data[$i]['array_no_seri'] = '';
                    $i++;
                } else {
                    break;
                }
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($data) {
                    $res = '';
                    if ($data['jenis'] == "produk") {
                        $produk = DetailPesananProduk::find($data['id']);
                        if ($produk->GudangBarangJadi->nama == '') {
                            $res = $produk->GudangBarangJadi->produk->nama;
                        } else {
                            $res = $produk->GudangBarangJadi->produk->nama . ' - ' . $produk->GudangBarangJadi->nama;
                        }

                        $res .= '<input type="text" class="hide" name="produk_id[]" value="' . $data['id'] . '"/>';
                    } else {
                        $part = DetailPesananPart::find($data['id']);
                        $res = $part->Sparepart->nama;
                        $res .= '<input type="text" class="hide" name="part_id[]" value="' . $data['id'] . '"/>';
                    }
                    return $res;
                })
                ->addColumn('jumlah', function ($data) {
                    // $c = NoseriDetailPesanan::where(['detail_pesanan_produk_id' => $data->id, 'status' => 'ok'])->get()->count();
                    // return $data->jumlah_kirim;
                    $res = '';
                    $res = $data['jumlah_kirim'];
                    if ($data['jenis'] == "part") {
                        $res .= '<input type="text" name="part_jumlah[]" class="hide" value="' . $data['jumlah_kirim'] . '"/>';
                    }
                    return $res;
                })
                ->addColumn('array', function ($data) {
                    if ($data['jenis'] == "produk") {
                        return '<input type="text" name="produk_noseri_id[]" value="' . $data['array_no_seri'] . '"/>';
                    }
                })
                ->rawColumns(['nama_produk', 'jumlah', 'array'])
                ->make(true);
        }
    }


    public function get_data_detail_so($id)
    {
        $data = DetailPesanan::where('pesanan_id', $id)->with(['DetailLogistik.Logistik.Ekspedisi', 'PenjualanProduk'])->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    return '';
                } else {
                    return '  <div class="form-check">
                    <input class=" form-check-input yet detail_produk_id"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->pesanan->id . '" />
                    </div>';
                }
            })

            ->addColumn('no', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    return $data->DetailLogistik->Logistik->nosurat;
                } else {
                    return '';
                }
            })
            ->addColumn('tgl_kirim', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    return  Carbon::createFromFormat('Y-m-d', $data->DetailLogistik->Logistik->tgl_kirim)->format('d-m-Y');
                } else {
                    return '';
                }
            })
            ->addColumn('pengirim', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    if ($data->DetailLogistik->Logistik->nama_pengirim == "") {
                        return $data->DetailLogistik->Logistik->ekspedisi->nama;
                    } else {
                        return $data->DetailLogistik->Logistik->nama_pengirim;
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('status', function ($data) {
                if (isset($data->DetailLogistik->Logistik)) {
                    return '<span class="badge green-text">Sudah Dikirim</span>';
                } else {
                    return '<span class="badge red-text">Belum Dikirim</span>';
                }
            })
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('button', function () {
                return '<a type="button" class="noserishow btn btn-outline-primary btn-sm" data-id="3" ><i class="fas fa-eye"></i> Detail</a>';
            })
            ->rawColumns(['checkbox', 'button', 'status'])
            ->make(true);
    }

    public function get_noseri_so($id)
    {
        $data = NoseriDetailPesanan::has('DetailPesananProduk')->where('detail_pesanan_produk_id', $id)->with('NoseriTGbj.NoseriBarangJadi')->get();

        // $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $idtrf) {
        //     $q->where(['gdg_brg_jadi_id' => $id, 't_gbj_id' => $idtrf]);
        // });

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_seri', function ($data) {
                return $data->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->make(true);
    }
    public function get_data_detail_belum_kirim_so($id, $jenis)
    {

        // $x = explode(',', $id);
        // $data = DetailPesananProduk::WhereHas('noseridetailpesanan', function ($q) {
        //     $q->where('status', 'ok');
        // })->whereIN('detail_pesanan_id', $x)->get();
        if ($jenis == "EKAT") {
            $datas = DetailPesananProduk::WhereHas('NoSeriDetailPesanan', function ($q) {
                $q->whereIN('status', ['ok']);
            })->whereHas('DetailPesanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();

            $array_id = array();
            foreach ($datas as $i) {
                $id = $i->id;
                $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($id) {
                    $q->where('detail_pesanan_produk_id', $id);
                })->count();
                $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->count();
                $detail_pesanan = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                    $q->where('id', $id);
                })->get();
                $jumlahpesanan = 0;

                foreach ($detail_pesanan as $j) {
                    foreach ($j->PenjualanProduk->Produk as $k) {
                        if ($k->id == $i->GudangBarangJadi->produk_id) {
                            $jumlahpesanan = $jumlahpesanan + ($j->jumlah * $k->pivot->jumlah);
                        }
                    }
                }

                $jumlahsekarang = $jumlahsudahuji - $jumlahterkirim;
                if ($jumlahsekarang > 0) {
                    $array_id[] = $i->id;
                }
            }

            $data = DetailPesananProduk::whereIN('id', $array_id)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($data) {
                    return '  <div class="form-check col-form-label">
                        <input class=" form-check-input yet detail_produk_id check_detail"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->id . '" />
                        </div>';
                })
                ->addColumn('nama_produk', function ($data) {
                    if ($data->gudangbarangjadi->nama == '') {
                        return $data->gudangbarangjadi->produk->nama;
                    } else {
                        return $data->gudangbarangjadi->produk->nama . ' - ' . $data->gudangbarangjadi->nama;
                    }
                })
                ->addColumn('jumlah', function ($data) {
                    $id = $data->id;
                    $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($id) {
                        $q->where('detail_pesanan_produk_id', $id);
                    })->count();
                    $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->count();
                    $s = $jumlahsudahuji - $jumlahterkirim;
                    return '<div id="jumlah_transfer">' . $s . '</div>';
                })
                ->addColumn('dikirim', function ($data) {
                    $id = $data->id;
                    $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($id) {
                        $q->where('detail_pesanan_produk_id', $id);
                    })->count();
                    $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->count();
                    $s = $jumlahsudahuji - $jumlahterkirim;
                    return '<input type="number" class="form-control jumlah_kirim" max="' . $s . '" min="0" value="' . $s . '" style="width:100%;" readonly="true" name="jumlah_dikirim[]"/>';
                })
                ->addColumn('button', function ($data) {
                    return '<a class="noserishow" data-id="' . $data->id . '"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
                })
                ->addColumn('aksi', function ($data) {
                    return '<a data-toggle="modal" data-target="#noserimodal" class="noseri"  data-id="' . $data->id . '"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
                })
                ->addColumn('array_check', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        $id = $data->id;
                        $s = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->DoesntHave('NoseriDetailLogistik')->get();
                        return '<div name="array_check[]">' . $s->implode('id', ',') . '</div>';
                    }
                })
                ->rawColumns(['checkbox', 'button', 'status', 'dikirim', 'jumlah', 'array_check', 'aksi'])
                ->make(true);
        } else {
            $pesanan_id = $id;
            $datas = DetailPesananProduk::WhereHas('NoSeriDetailPesanan', function ($q) {
                $q->whereIN('status', ['ok']);
            })->whereHas('DetailPesanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();

            $array_id = array();
            foreach ($datas as $i) {
                $id = $i->id;
                $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($id) {
                    $q->where('detail_pesanan_produk_id', $id);
                })->count();
                $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->count();
                $detail_pesanan = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                    $q->where('id', $id);
                })->get();
                $jumlahpesanan = 0;

                foreach ($detail_pesanan as $j) {
                    foreach ($j->PenjualanProduk->Produk as $k) {
                        // echo $k->id . " dengan " . $i->GudangBarangJadi->produk_id . ". ";
                        if ($k->id == $i->GudangBarangJadi->produk_id) {
                            $jumlahpesanan = $jumlahpesanan + ($j->jumlah * $k->pivot->jumlah);
                        }
                    }
                }

                $jumlahsekarang = $jumlahsudahuji - $jumlahterkirim;
                if ($jumlahsekarang > 0) {
                    $array_id[] = $i->id;
                }
            }

            $datapart = DetailPesananPart::where('pesanan_id', $pesanan_id)->get();

            $datas_jasa = DetailPesananPart::where('pesanan_id', $pesanan_id)->whereHas('Sparepart', function ($q) {
                $q->where('kode', 'LIKE', '%JASA%');
            })->DoesntHave('DetailLogistikPart')->get();

            $pid = array();
            foreach ($datapart as $z) {
                $id = $z->id;
                $jumlahterkirim = DetailLogistikPart::where('detail_pesanan_part_id', $id)->sum('jumlah');
                $jumlahsudahuji = OutgoingPesananPart::where('detail_pesanan_part_id', $id)->sum('jumlah_ok');

                $jumlahsekarang = $jumlahsudahuji - $jumlahterkirim;
                if ($jumlahsekarang > 0) {
                    $pid[] = $z->id;
                }
            }

            $datas_part = DetailPesananPart::whereIN('id', $pid)->get();

            $c_prd = DetailPesanan::where('pesanan_id', $pesanan_id)->count();
            $c_prt = DetailPesananPart::where('pesanan_id', $pesanan_id)->count();

            if ($c_prd <= 0 && $c_prt > 0) {
                // $data = DetailPesananPart::DoesntHave('DetailLogistikPart')->where('pesanan_id', $pesanan_id)->get();
                $data = $datas_part->merge($datas_jasa);
            } else if ($c_prt <= 0 && $c_prd > 0) {
                $data = DetailPesananProduk::whereIN('id', $array_id)->get();
            } else if ($c_prd > 0 && $c_prd > 0) {
                $Produk = collect(DetailPesananProduk::whereIN('id', $array_id)->get());
                // $Part = collect(DetailPesananPart::DoesntHave('DetailLogistikPart')->where('pesanan_id', $pesanan_id)->get());
                $Part = $datas_part->merge($datas_jasa);
                $data = $Produk->merge($Part);
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        return '<div class="form-check col-form-label">
                        <input class="form-check-input yet detail_produk_id check_detail"  data-id="' . $data->id . '" type="checkbox"  data-value="' . $data->id . '" />
                        </div>';
                    } else {
                        return '  <div class="form-check col-form-label">
                        <input class=" form-check-input yet detail_part_id check_detail"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->id . '" />
                        </div>';
                    }
                })
                ->addColumn('nama_produk', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        if ($data->gudangbarangjadi->nama == '') {
                            return $data->gudangbarangjadi->produk->nama;
                        } else {
                            return $data->gudangbarangjadi->produk->nama . ' - ' . $data->gudangbarangjadi->nama;
                        }
                    } else {
                        return $data->Sparepart->nama;
                    }
                })
                ->addColumn('jumlah', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        $id = $data->id;
                        $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($id) {
                            $q->where('detail_pesanan_produk_id', $id);
                        })->count();
                        $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->count();
                        $s = $jumlahsudahuji - $jumlahterkirim;
                        return '<div id="jumlah_transfer">' . $s . '</div>';
                    } else {
                        $id = $data->id;
                        $jumlahterkirim = DetailLogistikPart::where('detail_pesanan_part_id', $id)->sum('jumlah');
                        $jumlahsudahuji = OutgoingPesananPart::where('detail_pesanan_part_id', $id)->sum('jumlah_ok');
                        $s = $jumlahsudahuji - $jumlahterkirim;
                        return '<div id="jumlah_transfer">' . $s . '</div>';
                    }
                })
                ->addColumn('dikirim', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        $id = $data->id;
                        $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik', function ($q) use ($id) {
                            $q->where('detail_pesanan_produk_id', $id);
                        })->count();
                        $jumlahsudahuji = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->count();
                        $s = $jumlahsudahuji - $jumlahterkirim;
                        return '<input type="number" max="' . $s . '" min="0" value="' . $s . '" name="jumlah_dikirim[]" style="width:100%;" readonly="true" class="form-control jumlah_kirim"/>';
                    } else {
                        $id = $data->id;
                        $jumlahterkirim = DetailLogistikPart::where('detail_pesanan_part_id', $id)->sum('jumlah');
                        $jumlahsudahuji = OutgoingPesananPart::where('detail_pesanan_part_id', $id)->sum('jumlah_ok');
                        $s = $jumlahsudahuji - $jumlahterkirim;
                        return '<input type="number" max="' . $s . '" min="0" value="' . $s . '" name="jumlah_dikirim[]" style="width:100%;" class="form-control jumlah_kirim"/>';
                    }
                })
                ->addColumn('array_check', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        $id = $data->id;
                        $s = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $id])->DoesntHave('NoseriDetailLogistik')->get();
                        return '<div name="array_check[]">' . $s->implode('id', ',') . '</div>';
                    }
                })
                ->addColumn('button', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        return '<a class="noserishow" data-id="' . $data->id . '"><button type="button" class="btn btn-outline-primary btn-sm" id="btnnoseri"><i class="fas fa-eye"></i> Detail</button></a>';
                    } else {
                        return '';
                    }
                })
                ->addColumn('aksi', function ($data) {
                    if (isset($data->gudangbarangjadi)) {
                        return '<a data-toggle="modal" data-target="#noserimodal" class="noseri"  data-id="' . $data->id . '"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a>';
                    } else {
                        return '-';
                    }
                })
                ->rawColumns(['checkbox', 'button', 'status', 'dikirim', 'jumlah', 'array_check', 'aksi'])
                ->make(true);
            // $datas = DetailPesananPart::where('pesanan_id', $id)->get();
            // $array_id = array();
            // foreach ($datas as $i) {
            //     if (!isset($i->DetailLogistikPart)) {
            //         $array_id[] = $i->id;
            //     }
            // }
            // $data = DetailPesananPart::whereIN('id', $array_id)->get();
            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('checkbox', function ($data) {
            //         return '  <div class="form-check">
            //             <input class=" form-check-input yet detail_produk_id"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->id . '" />
            //             </div>';
            //     })
            //     ->addColumn('nama_produk', function ($data) {
            //         return $data->Sparepart->nama;
            //     })
            //     ->addColumn('jumlah', function ($data) {
            //         return $data->jumlah;
            //     })
            //     ->addColumn('button', function ($data) {
            //         return '<a type="button" class="noserishow" data-id="' . $data->id . '"><i class="fas fa-eye"></i></a>';
            //     })
            //     ->rawColumns(['checkbox', 'button', 'status'])
            //     ->make(true);
        }
    }
    public function get_noseri_so_belum_kirim($id, $array)
    {
       // dd($id);
        $arr = explode(',', $array);
        // $data = NoseriDetailPesanan::where(['detail_pesanan_produk_id' => $id, 'status' => 'ok'])->doesntHave('NoseriDetailLogistik')->get();
        // $data = DB::table('noseri_barang_jadi')
        //             ->leftjoin('t_gbj_noseri','t_gbj_noseri.noseri_id','=','noseri_barang_jadi.id')
        //             ->rightjoin('noseri_detail_pesanan','noseri_detail_pesanan.t_tfbj_noseri_id','=','t_gbj_noseri.id')
        //             ->where(['noseri_detail_pesanan.detail_pesanan_produk_id'=> $id,'noseri_detail_pesanan.status' => 'ok'])
        //             ->select('noseri_detail_pesanan.id as noseri_detail_pesanan_id','noseri_barang_jadi.noseri as noseri_gbj')
        //             ->get();

        //             // SELECT * FROM `noseri_detail_pesanan` WHERE (`detail_pesanan_produk_id` = '644' and `status` = 'ok')
        //             // and not exists (SELECT * FROM `noseri_logistik` WHERE `noseri_detail_pesanan`.`id` = `noseri_logistik`.`noseri_detail_pesanan_id`)


        // return datatables()->of($data)
        //     ->addIndexColumn()
        //     ->addColumn('checkbox', function ($data) use($arr){
        //         $checked = "";
        //         if(in_array($data->noseri_detail_pesanan_id, $arr)) { $checked = "checked"; }
        //         return '<div class="form-check">
        //             <input class=" form-check-input yet noseri_checkbox check_noseri"  data-id="' . $data->noseri_detail_pesanan_id . '" type="checkbox" data-value="' . $data->noseri_detail_pesanan_id . '" '.$checked.'  />
        //         </div>';

        //     })
        //     ->addColumn('no_seri', function ($data) {
        //         return $data->noseri_gbj;
        //     })
        //     ->rawColumns(['checkbox'])
        //     ->make(true);

        // $data = NoseriDetailPesanan::where(['detail_pesanan_produk_id' => $id, 'status' => 'ok'])->doesntHave('NoseriDetailLogistik')->get();
        $data = NoseriBarangJadi::select('noseri_detail_pesanan.id as ndp_id', 'seri_detail_rw.created_at', 'seri_detail_rw.packer', 'seri_detail_rw.isi as isi', 'noseri_barang_jadi.noseri', 'noseri_detail_pesanan.tgl_uji', 'noseri_detail_pesanan.status', 'noseri_barang_jadi.gdg_barang_jadi_id', 'noseri_barang_jadi.id as id')
            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
            ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
            ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
            ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
            ->addSelect([
                'cek_rw' => function ($q) {
                    $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                        ->from('seri_detail_rw')
                        ->whereColumn('seri_detail_rw.noseri_id', 'noseri_barang_jadi.id');
                },
                'cek_logistik' => function ($q) use ($id) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                        ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->whereColumn('t_gbj_noseri.noseri_id', 'noseri_barang_jadi.id')
                        ->where('t_gbj_detail.detail_pesanan_produk_id', $id);
                }
            ])
            ->havingRaw('cek_logistik = 0')
            ->where('noseri_detail_pesanan.detail_pesanan_produk_id', $id)
            ->where('noseri_detail_pesanan.status', 'ok')
            ->get();


        // $data = NoseriDetailPesanan::
        //         where(['detail_pesanan_produk_id' => $id, 'status' => 'ok'])->doesntHave('NoseriDetailLogistik')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) use ($arr) {
                $checked = "";
                if (in_array($data->ndp_id, $arr)) {
                    $checked = "checked";
                }
                return '<div class="form-check">
                    <input class=" form-check-input yet noseri_checkbox check_noseri"  data-id="' . $data->ndp_id . '" type="checkbox" data-value="' . $data->ndp_id . '" ' . $checked . '  />
                </div>';
            })
            ->addColumn('item', function ($d) {
                if ($d->isi == null) {
                    return  array();
                } else {
                    return json_decode($d->isi);
                }
            })
            ->addColumn('no_seri', function ($data) {
                return $data->noseri;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    public function get_data_detail_selesai_kirim_so($id, $jenis)
    {
        if ($jenis == "EKAT") {
            $data = DetailLogistik::whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            // $data = DetailPesanan::where('pesanan_id', $id)->Has('DetailLogistik')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('no', function ($data) {
                    if (isset($data->Logistik)) {
                        return $data->Logistik->nosurat;
                    } else {
                        return '';
                    }
                })
                ->addColumn('tgl_kirim', function ($data) {
                    if (isset($data->Logistik)) {
                        return  Carbon::createFromFormat('Y-m-d', $data->Logistik->tgl_kirim)->format('d-m-Y');
                    } else {
                        return '';
                    }
                })
                ->addColumn('pengirim', function ($data) {
                    if (isset($data->Logistik)) {
                        if ($data->Logistik->Ekspedisi) {
                            return $data->Logistik->ekspedisi['nama'];
                        } else if ($data->Logistik->ekspedisi_id == "") {
                            return $data->Logistik->nama_pengirim;
                        } else {
                            return '-';
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('nama_produk', function ($data) {
                    if (empty($data->DetailPesananProduk->GudangBarangJadi->nama)) {
                        return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
                    } else {
                        return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama . ' - ' . $data->DetailPesananProduk->GudangBarangJadi->nama;
                    }
                })
                ->addColumn('jumlah', function ($data) {
                    $c = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->count();
                    return $c;
                })
                ->addColumn('button', function ($data) {
                    return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-id="' . $data->id . '">
                    <div><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></div>

            </a>';
                })
                ->rawColumns(['checkbox', 'button', 'status'])
                ->make(true);
        } else {
            $c_prd = DetailPesanan::where('pesanan_id', $id)->get()->count();
            $c_prt = DetailPesananPart::where('pesanan_id', $id)->get()->count();

            if ($c_prd <= 0 && $c_prt > 0) {
                $data = DetailLogistikPart::whereHas('DetailPesananPart', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
            } else if ($c_prt <= 0 && $c_prd > 0) {
                $data = DetailLogistik::whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
            } else if ($c_prd > 0 && $c_prd > 0) {
                $prd = DetailLogistik::whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
                $part = DetailLogistikPart::whereHas('DetailPesananPart', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
                $data = $prd->merge($part);
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('no', function ($data) {
                    if (isset($data->Logistik)) {
                        return $data->Logistik->nosurat;
                    } else {
                        return '';
                    }
                })
                ->addColumn('tgl_kirim', function ($data) {
                    if (isset($data->Logistik)) {
                        return  Carbon::createFromFormat('Y-m-d', $data->Logistik->tgl_kirim)->format('d-m-Y');
                    } else {
                        return '';
                    }
                })
                ->addColumn('pengirim', function ($data) {
                    if (isset($data->Logistik)) {
                        if ($data->Logistik->Ekspedisi) {
                            return $data->Logistik->ekspedisi['nama'];
                        } else if ($data->Logistik->nama_pengirim != "") {
                            return $data->Logistik->nama_pengirim;
                        } else {
                            return '-';
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('nama_produk', function ($data) {
                    if (isset($data->DetailPesananProduk)) {
                        if (empty($data->DetailPesananProduk->GudangBarangJadi->nama)) {
                            return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
                        } else {
                            return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama . ' - ' . $data->DetailPesananProduk->GudangBarangJadi->nama;
                        }
                    } else {
                        return $data->DetailPesananPart->Sparepart->nama;
                    }
                })
                ->addColumn('jumlah', function ($data) {

                    if (isset($data->DetailPesananProduk)) {
                        $c = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->count();
                        return $c;
                    } else {
                        return $data->jumlah;
                    }
                })
                ->addColumn('button', function ($data) {
                    if (isset($data->DetailPesananProduk)) {
                        return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-id="' . $data->id . '">
                            <button class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                        </a>';
                    }
                })
                ->rawColumns(['checkbox', 'button', 'status'])
                ->make(true);
        }
    }

    public function get_noseri_so_selesai_kirim($id)
    {
        $data = DetailLogistik::find($id);
        return view('page.logistik.so.noseri', ['id' => $id, 'res' => $data]);
    }


    public function get_noseri_so_selesai_kirim_data($id)
    {
        $data = NoseriDetailPesanan::whereHas('NoseriDetailLogistik', function ($q) use ($id) {
            $q->where('detail_logistik_id', $id);
        })->with('NoseriTGbj.NoseriBarangJadi')->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_seri', function ($data) {
                return $data->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->make(true);
    }

    public function get_data_no_seri($id)
    {
        // $data = NoseriDetailPesanan::where('detail_pesanan_produk', $id)->doesntHave('DetailLogistik')->get();
        // return datatables()->of($data)
        //     ->addIndexColumn()
        //     ->addColumn('checkbox', function ($data) {
        //         return '  <div class="form-check">
        //             <input class=" form-check-input yet detail_produk_id"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->pesanan->id . '" />
        //             </div>';
        //     })
        //     ->addColumn('nama_produk', function ($data) {
        //         return $data->penjualanproduk->nama;
        //     })
        //     ->addColumn('jumlah', function ($data) {
        //         return $data->jumlah;
        //     })
        //     ->addColumn('button', function ($data) {
        //         return '<a type="button" class="noserishow" data-id="' . $data->id . '"><i class="fas fa-eye"></i></a>';
        //     })
        //     ->rawColumns(['checkbox', 'button', 'status'])
        //     ->make(true);
    }

    //Get Data
    // public function get_data_so($value)
    // {
    //     $data = "";
    //     if ($value == "belum_kirim") {
    //         $data = Pesanan::addSelect([
    //             'tgl_kontrak' => function ($q) {
    //                 $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
    //                     ->from('ekatalog')
    //                     ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
    //                     ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             },
    //             'cqcprd' => function ($q) {
    //                 $q->selectRaw('count(noseri_detail_pesanan.id)')
    //                     ->from('noseri_detail_pesanan')
    //                     ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
    //                     ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
    //                     ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
    //             },
    //             'cqcpart' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
    //                     ->from('outgoing_pesanan_part')
    //                     ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
    //                     ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
    //             },
    //             'ctfjasa' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
    //                     ->from('detail_pesanan_part')
    //                     ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
    //             },
    //             'clogprd' => function ($q) {
    //                 $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
    //                     ->from('noseri_logistik')
    //                     ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
    //                     ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
    //                     ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
    //                     ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             },
    //             'clogpart' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
    //                     ->from('detail_logistik_part')
    //                     ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
    //                     ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             },
    //             'clogjasa' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
    //                     ->from('detail_logistik_part')
    //                     ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
    //                     ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             }
    //         ])->with(['Ekatalog.Customer', 'Spa.Customer', 'Spb.Customer'])
    //             ->whereNotIn('log_id', ['7', '10'])
    //             ->havingRaw('(clogjasa = 0 AND ctfjasa > 0) OR (clogprd = 0 AND cqcprd > 0) OR (clogpart = 0 AND cqcpart > 0)')
    //             ->orderBy('tgl_kontrak', 'asc')
    //             ->get();
    //     } else if ($value == "sebagian_kirim") {
    //         $data = Pesanan::addSelect([
    //             'tgl_kontrak' => function ($q) {
    //                 $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
    //                     ->from('ekatalog')
    //                     ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
    //                     ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             },
    //             'cqcprd' => function ($q) {
    //                 $q->selectRaw('count(noseri_detail_pesanan.id)')
    //                     ->from('noseri_detail_pesanan')
    //                     ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
    //                     ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
    //                     ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
    //             },
    //             'cqcpart' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
    //                     ->from('outgoing_pesanan_part')
    //                     ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
    //                     ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
    //             },
    //             'ctfjasa' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
    //                     ->from('detail_pesanan_part')
    //                     ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
    //             },
    //             'clogprd' => function ($q) {
    //                 $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
    //                     ->from('noseri_logistik')
    //                     ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
    //                     ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
    //                     ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
    //                     ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             },
    //             'clogpart' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
    //                     ->from('detail_logistik_part')
    //                     ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
    //                     ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             },
    //             'clogjasa' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
    //                     ->from('detail_logistik_part')
    //                     ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
    //                     ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             }
    //         ])->with(['Ekatalog.Customer', 'Spa.Customer', 'Spb.Customer'])
    //             ->whereNotIn('log_id', ['7', '10'])
    //             ->havingRaw('((clogjasa < ctfjasa AND clogjasa > 0) AND ctfjasa > 0) OR ((clogprd < cqcprd AND clogprd > 0) AND cqcprd > 0) OR ((clogpart < cqcpart AND clogpart > 0) AND cqcpart > 0)')
    //             ->orderBy('tgl_kontrak', 'asc')
    //             ->get();
    //     } else {
    //         $data = Pesanan::addSelect([
    //             'tgl_kontrak' => function ($q) {
    //                 $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
    //                     ->from('ekatalog')
    //                     ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
    //                     ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             },
    //             'cpoprd' => function ($q) {
    //                     $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
    //                     ->from('detail_pesanan')
    //                     ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
    //                     ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
    //                     ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
    //             },
    //             'ctfjasa' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
    //                     ->from('detail_pesanan_part')
    //                     ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
    //             },
    //             'cqcprd' => function ($q) {
    //                 $q->selectRaw('count(noseri_detail_pesanan.id)')
    //                     ->from('noseri_detail_pesanan')
    //                     ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
    //                     ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
    //                     ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
    //             },
    //             'cqcpart' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
    //                     ->from('outgoing_pesanan_part')
    //                     ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
    //                     ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
    //             },
    //             'ctfjasa' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
    //                     ->from('detail_pesanan_part')
    //                     ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
    //             },
    //             'clogprd' => function ($q) {
    //                 $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
    //                     ->from('noseri_logistik')
    //                     ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
    //                     ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
    //                     ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
    //                     ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             },
    //             'clogpart' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
    //                     ->from('detail_logistik_part')
    //                     ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
    //                     ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             },
    //             'clogjasa' => function ($q) {
    //                 $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
    //                     ->from('detail_logistik_part')
    //                     ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
    //                     ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //                     ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
    //                     ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
    //                     ->limit(1);
    //             }
    //         ])->with(['Ekatalog.Customer', 'Spa.Customer', 'Spb.Customer'])
    //             ->whereNotIn('log_id', ['7', '10'])
    //             ->havingRaw('(cpoprd < clogprd OR clogprd = 0)')
    //              ->havingRaw('(((clogjasa < ctfjasa AND clogjasa > 0) OR clogjasa = 0) AND ctfjasa > 0) OR (((clogprd < cqcprd AND clogprd > 0) OR clogprd = 0) AND cqcprd > 0) OR (((clogpart < cqcpart AND clogpart > 0) OR clogpart = 0) AND cqcpart > 0)')
    //             ->orderBy('tgl_kontrak', 'asc')
    //             ->get();
    //     }

    //     return datatables()->of($data)
    //         ->addIndexColumn()
    //         ->addColumn('so', function ($data) {
    //             return $data->so;
    //         })
    //         ->addColumn('po', function ($data) {
    //             return $data->no_po;
    //         })
    //         ->addColumn('nama_customer', function ($data) {
    //               if ($data->Ekatalog) {
    //                 return $data->Ekatalog->satuan;
    //             } elseif ($data->Spa) {
    //                 return $data->Spa->Customer->nama;
    //             } else if($data->Spb){
    //                 return $data->Spb->Customer->nama;
    //             }
    //             // $name = explode('/', $data->so);
    //             // if ($name[1] == 'EKAT') {
    //             //     return $data->Ekatalog->satuan;
    //             // } elseif ($name[1] == 'SPA') {
    //             //     return $data->Spa->Customer->nama;
    //             // } else {
    //             //     return $data->Spb->Customer->nama;
    //             // }
    //         })
    //         ->addColumn('alamat', function ($data) {
    //             if ($data->Ekatalog) {
    //                 return $data->Ekatalog->alamat;
    //             } elseif ($data->Spa) {
    //                 return $data->Spa->Customer->alamat;
    //             } else if($data->Spb){
    //                 return $data->Spb->Customer->alamat;
    //             }
    //             // $name = explode('/', $data->so);
    //             // if ($name[1] == 'EKAT') {
    //             //     return $data->Ekatalog->alamat;
    //             // } elseif ($name[1] == 'SPA') {
    //             //     return $data->Spa->Customer->alamat;
    //             // } else {
    //             //     return $data->Spb->Customer->alamat;
    //             // }
    //         })
    //         ->addColumn('telp', function ($data) {
    //             if ($data->Ekatalog) {
    //                 return $data->Ekatalog->Customer->telp;
    //             } elseif ($data->Spa) {
    //                 return $data->Spa->Customer->telp;
    //             } else if($data->Spb){
    //                 return $data->Spb->Customer->telp;
    //             }
    //             // $name = explode('/', $data->so);
    //             // if ($name[1] == 'EKAT') {
    //             //     return $data->Ekatalog->Customer->telp;
    //             // } elseif ($name[1] == 'SPA') {
    //             //     return $data->Spa->Customer->telp;
    //             // } else {
    //             //     return $data->Spb->Customer->telp;
    //             // }
    //         })
    //         ->addColumn('ket', function ($data) {
    //             return $data->ket;
    //         })
    //         ->addColumn('status', function ($data) {
    //             $datas = "";
    //             $res = $data->cqcprd + $data->cqcpart + $data->ctfjasa;
    //             $tes = $data->clogprd + $data->clogpart + $data->clogjasa;
    //             if ($res > 0) {
    //                 $hitung = floor(((($data->clogprd + $data->clogpart + $data->clogjasa) / ($data->cqcprd + $data->cqcpart + $data->ctfjasa)) * 100));
    //                 if ($hitung > 0) {
    //                     $datas = '<div class="progress">
    //                     <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
    //                 </div>
    //                 <small class="text-muted">Selesai</small>';
    //                 } else {
    //                     $datas = '<div class="progress">
    //                     <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
    //                 </div>
    //                 <small class="text-muted">Selesai</small>';
    //                 }
    //             } else {
    //                 $datas = '<div class="progress">
    //                     <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $tes . " " . $res . '%</div>
    //                 </div>
    //                 <small class="text-muted">Selesai</small>';
    //             }

    //             if ($data->Ekatalog) {
    //                 if ($data->Ekatalog->status == "batal") {
    //                     return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="EKAT" data-provinsi="">
    //                         <button type="button" class="btn btn-sm btn-outline-danger" type="button">
    //                             <i class="fas fa-times"></i>
    //                             Batal
    //                         </button>
    //                     </a>';
    //                 } else {
    //                     return $datas;
    //                 }
    //             } else if ($data->Spa) {
    //                 if ($data->Spa->log == "batal") {
    //                     return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="SPA" data-provinsi="">
    //                         <button type="button" class="btn btn-sm btn-outline-danger" type="button">
    //                             <i class="fas fa-times"></i>
    //                             Batal
    //                         </button>
    //                     </a>';
    //                 } else {
    //                     return $datas;
    //                 }
    //             } else if ($data->Spb) {
    //                 if ($data->Spb->log == "batal") {
    //                     return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="SPB" data-provinsi="">
    //                         <button type="button" class="btn btn-sm btn-outline-danger" type="button">
    //                             <i class="fas fa-times"></i>
    //                             Batal
    //                         </button>
    //                     </a>';
    //                 } else {
    //                     return $datas;
    //                 }
    //             }
    //         })
    //         ->addColumn('batas', function ($data) {
    //             if ($data->tgl_kontrak != "") {
    //                 if ($data->log_id != "10") {
    //                     $tgl_sekarang = Carbon::now();
    //                     $tgl_parameter = $data->tgl_kontrak;
    //                     $hari = $tgl_sekarang->diffInDays($tgl_parameter);
    //                     if ($tgl_sekarang->format('Y-m-d') <= $tgl_parameter) {
    //                         if ($hari > 7) {
    //                             return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                             <div><small><i class="fas fa-clock info"></i> ' . $hari . ' Hari Lagi</small></div>';
    //                         } else if ($hari > 0 && $hari <= 7) {
    //                             return  '<div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                             <div><small><i class="fas fa-exclamation-circle warning"></i> ' . $hari . ' Hari Lagi</small></div>';
    //                         } else {
    //                             return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                             <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
    //                         }
    //                     } else {
    //                         return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
    //                             <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lewat ' . $hari . ' Hari</small></div>';
    //                     }
    //                 } else {
    //                     return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
    //                 }
    //             }
    //          //   return '-';
    //         })
    //         ->addColumn('button', function ($data) {
    //             // $name = explode('/', $data->so);
    //             // $x = $name[1];
    //             // $y = "";
    //             // $pesanan = $data->id;
    //             if ($data->Ekatalog) {
    //                 $y = $data->Ekatalog->id;
    //                 $x = 'ekatalog';
    //             } elseif ($data->Spa) {
    //                 $y = $data->Spa->id;
    //                 $x = 'spa';
    //             } else {
    //                 $y = $data->Spb->id;
    //                 $x = 'spb';
    //             }
    //             $z = 'proses';
    //             return '
    //             <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
    //             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
    //                 <a href="' . route('logistik.so.detail', [$z, $y, $x]) . '">
    //                     <button class="dropdown-item" type="button">
    //                         <i class="fas fa-eye"></i> Detail
    //                     </button>
    //                 </a>
    //             <button class="dropdown-item cetaksj" type="button" data-x="' . $x . '" data-y="' . $data->id. '" data-z="' . $z . '">
    //                 <i class="fas fa-print"></i>
    //                 Cetak Surat Jalan
    //             </button>
    //             </div>
    //             ';
    //         })
    //         ->rawColumns(['status', 'button', 'batas'])
    //         ->setRowClass(function ($data) {
    //             if ($data->Ekatalog) {
    //                 if ($data->Ekatalog->status == 'batal') {
    //                     return 'text-danger font-weight-bold';
    //                 }
    //             } else if ($data->Spa) {
    //                 if ($data->Spa->log == 'batal') {
    //                     return 'text-danger font-weight-bold';
    //                 }
    //             } else {
    //                 if ($data->Spb->log == 'batal') {
    //                     return 'text-danger font-weight-bold';
    //                 }
    //             }
    //         })
    //         ->make(true);
    // }
    public function get_data_so($value, $years)
    {
        $data = "";
        if ($value == "belum_kirim") {
            $data = Pesanan::addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'ctfjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                //Baru
                'cpoprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
            ])->with(['Ekatalog.Customer', 'Spa.Customer', 'Spb.Customer'])
                ->whereNotIn('log_id', ['10', '20'])
                ->whereNotNull('no_po')
                ->whereYear('created_at',  $years)
                ->havingRaw('(((cqcprd > 0 AND clogprd < cqcprd) OR clogprd = 0  ) AND cpoprd > 0 ) OR (((cqcpart > 0 AND clogpart < cqcpart) OR clogpart = 0 ) AND cpopart > 0 ) OR  ((clogjasa < ctfjasa OR clogjasa = 0 ) AND ctfjasa > 0 )')
                ->orderBydesc('created_at')
                ->get();
        } else if ($value == "sebagian_kirim") {
            $data = Pesanan::addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'ctfjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->with(['Ekatalog.Customer', 'Spa.Customer', 'Spb.Customer'])
                ->whereNotIn('log_id', ['10', '20'])
                ->whereNotNull('no_po')
                ->whereYear('created_at',  $years)
                ->havingRaw('(((cqcprd > 0 AND clogprd < cqcprd) OR clogprd = 0 ) AND cpoprd > 0 ) OR (((cqcpart > 0 AND clogpart < cqcpart) OR clogpart = 0 ) AND cpopart > 0 ) OR  ((clogjasa < ctfjasa OR clogjasa = 0 ) AND ctfjasa > 0 )')
                ->orderBydesc('created_at')
                ->get();
        } else {
            $data = Pesanan::addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'ctfjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                //Baru
                'cpoprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cpopart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                }
            ])->with(['Ekatalog.Customer', 'Spa.Customer', 'Spb.Customer'])
                ->whereNotIn('log_id', ['10', '20'])
                ->whereNotNull('no_po')
                ->whereYear('created_at',  $years)
                ->havingRaw('(((cqcprd > 0 AND clogprd < cqcprd) OR clogprd = 0 OR (cpoprd > clogprd ) ) AND cpoprd > 0 ) OR (((cqcpart > 0 AND clogpart < cqcpart) OR clogpart = 0 ) AND cpopart > 0 ) OR  (((ctfjasa > 0 AND clogjasa < ctfjasa )OR clogjasa = 0 ) AND ctfjasa > 0 )')
                ->orderBydesc('created_at')
                ->get();
        }
        //     foreach($data as $key => $d){
        //         $datas[$key] = array(
        //             'po' => $d->no_po,
        //             'jasa' => array(
        //                 'po' => $d->ctfjasa,
        //                 'qc' => $d->ctfjasa,
        //                 'log' => $d->clogjasa
        //             ),
        //             'part' => array(
        //                 'po' => $d->cpopart,
        //                 'qc' => $d->cqcpart,
        //                 'log' => $d->clogpart
        //             ),
        //             'prd' => array(
        //                 'po' => $d->cpoprd,
        //                 'qc' => $d->cqcprd,
        //                 'log' => $d->clogprd
        //             )
        //         );
        //     }

        // return response()->json(['jumlah' => count($data),'data' => $datas]);

        // return response()->json([
        //     'value' => $value,
        //     'years' => $data
        // ]);

        // return response()->json([
        //     'value' => $value,
        //     'years' => $data
        // ]);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('po', function ($data) {
                return $data->no_po;
            })
            ->addColumn('nama_customer', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->satuan;
                } elseif ($data->Spa) {
                    return $data->Spa->Customer->nama;
                } else {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->alamat;
                } elseif ($data->Spa) {
                    return $data->Spa->Customer->alamat;
                } else {
                    return $data->Spb->Customer->alamat;
                }
            })
            ->addColumn('telp', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->Customer->telp;
                } elseif ($data->Spa) {
                    return $data->Spa->Customer->telp;
                } else {
                    return $data->Spb->Customer->telp;
                }
            })
            ->addColumn('ket', function ($data) {
                return $data->ket;
            })
            ->addColumn('tfqc', function ($data) {
                return  $data->cqcprd + $data->cqcpart + $data->ctfjasa;
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                $res = $data->cqcprd + $data->cqcpart + $data->ctfjasa;
                $tes = $data->clogprd + $data->clogpart + $data->clogjasa;
                if ($res > 0) {
                    $hitung = floor(((($data->clogprd + $data->clogpart + $data->clogjasa) / ($data->cqcprd + $data->cqcpart + $data->ctfjasa)) * 100));
                    if ($hitung > 0) {
                        $datas = '<div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                    } else {
                        $datas = '<div class="progress">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                    }
                } else {
                    $datas = '<div class="progress">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                }

                // if ($data->Ekatalog) {
                //     if ($data->Ekatalog->status == "batal") {
                //         return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="EKAT" data-provinsi="">
                //             <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                //                 <i class="fas fa-times"></i>
                //                 Batal
                //             </button>
                //         </a>';
                //     } else {
                //         return $datas;
                //     }
                // } else if ($data->Spa) {
                //     if ($data->Spa->log == "batal") {
                //         return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="SPA" data-provinsi="">
                //             <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                //                 <i class="fas fa-times"></i>
                //                 Batal
                //             </button>
                //         </a>';
                //     } else {
                //         return $datas;
                //     }
                // } else if ($data->Spb) {
                //     if ($data->Spb->log == "batal") {
                //         return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="SPB" data-provinsi="">
                //             <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                //                 <i class="fas fa-times"></i>
                //                 Batal
                //             </button>
                //         </a>';
                //     } else {
                //         return $datas;
                //     }
                // }
                return $datas;
            })
            ->addColumn('batas', function ($data) {
                if ($data->tgl_kontrak != "") {
                    if ($data->log_id != "10") {
                        $tgl_sekarang = Carbon::now();
                        $tgl_parameter = $data->tgl_kontrak;
                        $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                        if ($tgl_sekarang->format('Y-m-d') <= $tgl_parameter) {
                            if ($hari > 7) {
                                return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-clock info"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return  '<div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-exclamation-circle warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            }
                        } else {
                            return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lewat ' . $hari . ' Hari</small></div>';
                        }
                    } else {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                    }
                }
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->so);
                $x = $name[1];
                $y = "";
                $pesanan = $data->id;
                if ($x == 'EKAT') {
                    $y = $data->Ekatalog->id;
                } elseif ($x == 'SPA') {
                    $y = $data->Spa->id;
                } else {
                    $y = $data->Spb->id;
                }
                $z = 'proses';
                $res = $data->cqcprd + $data->cqcpart + $data->ctfjasa;

                if ($res > 0) {
                    return '
                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                        <a href="' . route('logistik.so.detail', [$z, $y, $x]) . '">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </a>
                        <button class="dropdown-item cetaksj" type="button" data-x="' . $x . '" data-y="' . $pesanan . '" data-z="' . $z . '">
                            <i class="fas fa-print"></i>
                            Cetak Surat Jalan
                        </button>
                    </div>
                    ';
                } else {
                    return '
                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                            <button class="dropdown-item cetaksj" type="button" data-x="' . $x . '" data-y="' . $pesanan . '" data-z="' . $z . '">
                            <i class="fas fa-print"></i>
                            Cetak Surat Jalan
                        </button>
                    </div>
                  ';
                }
            })
            ->rawColumns(['status', 'button', 'batas'])
            ->setRowClass(function ($data) {
                if ($data->Ekatalog) {
                    if ($data->Ekatalog->status == 'batal') {
                        return 'text-danger font-weight-bold';
                    }
                } else if ($data->Spa) {
                    if ($data->Spa->log == 'batal') {
                        return 'text-danger font-weight-bold';
                    }
                } else {
                    if ($data->Spb->log == 'batal') {
                        return 'text-danger font-weight-bold';
                    }
                }
            })
            ->make(true);
    }

    public function get_data_selesai_so($years)
    {
        // return response()->json([
        //     'years' => $years,
        // ]);
        $prd = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->groupBy('pesanan.id')
                ->havingRaw('count(noseri_detail_pesanan.id) <= (select count(noseri_logistik.id)
            from noseri_logistik
            left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
            left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
            left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
            where detail_pesanan.pesanan_id = pesanan.id
            having count(noseri_logistik.id) > 0)');
        })->addSelect(['tgl_kirim_min' => function ($q) {
            $q->selectRaw('MIN(logistik.tgl_kirim)')
                ->from('logistik')
                ->leftjoin('detail_logistik', 'detail_logistik.logistik_id', '=', 'logistik.id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
                ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                ->limit(1);
        }, 'tgl_kirim_max' => function ($q) {
            $q->selectRaw('MAX(logistik.tgl_kirim)')
                ->from('logistik')
                ->leftjoin('detail_logistik', 'detail_logistik.logistik_id', '=', 'logistik.id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
                ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                ->limit(1);
        }])->with(['Ekatalog.Customer', 'Spa.Customer', 'Spb.Customer', 'DetailPesanan.DetailPesananProduk.DetailLogistik.Logistik'])->whereYear('created_at',  $years)->whereNotIn('log_id', ['7'])->orderByDesc('created_at');

        $part = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('outgoing_pesanan_part', 'outgoing_pesanan_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                ->havingRaw("sum(outgoing_pesanan_part.jumlah_ok) <= (
                    select sum(detail_pesanan_part.jumlah)
                    from detail_pesanan_part
                    left join detail_logistik_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                    left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                    where detail_pesanan_part.pesanan_id = pesanan.id)")
                ->groupBy('pesanan.id');
        })->addSelect(['tgl_kirim_min' => function ($q) {
            $q->selectRaw('MIN(logistik.tgl_kirim)')
                ->from('logistik')
                ->leftjoin('detail_logistik_part', 'detail_logistik_part.logistik_id', '=', 'logistik.id')
                ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                ->limit(1);
        }, 'tgl_kirim_max' => function ($q) {
            $q->selectRaw('MAX(logistik.tgl_kirim)')
                ->from('logistik')
                ->leftjoin('detail_logistik_part', 'detail_logistik_part.logistik_id', '=', 'logistik.id')
                ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                ->limit(1);
        }])->with(['Spa.Customer', 'Spb.Customer', 'DetailPesananPart.DetailLogistikPart.Logistik'])->whereYear('created_at',  $years)->whereNotIn('log_id', ['7'])->orderByDesc('created_at');


        $partjasa = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->where('m_sparepart.kode', 'LIKE', '%JASA%')
                ->havingRaw("sum(detail_pesanan_part.jumlah) <= (
                        select sum(detail_pesanan_part.jumlah)
                        from detail_pesanan_part
                        left join detail_logistik_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id)")
                ->groupBy('pesanan.id');
        })->addSelect(['tgl_kirim_min' => function ($q) {
            $q->selectRaw('MIN(logistik.tgl_kirim)')
                ->from('logistik')
                ->leftjoin('detail_logistik_part', 'detail_logistik_part.logistik_id', '=', 'logistik.id')
                ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                ->limit(1);
        }, 'tgl_kirim_max' => function ($q) {
            $q->selectRaw('MAX(logistik.tgl_kirim)')
                ->from('logistik')
                ->leftjoin('detail_logistik_part', 'detail_logistik_part.logistik_id', '=', 'logistik.id')
                ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                ->limit(1);
        }])->with(['Spa.Customer', 'Spb.Customer', 'DetailPesananPart.DetailLogistikPart.Logistik'])->whereYear('created_at',  $years)->whereNotIn('log_id', ['7'])->orderByDesc('created_at')->union($prd)->union($part)->get();

        $data = $partjasa;
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
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->satuan;
                } elseif ($name[1] == 'SPA') {
                    return $data->Spa->Customer->nama;
                } else {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->alamat;
                } elseif ($name[1] == 'SPA') {
                    return $data->Spa->Customer->alamat;
                } else {
                    return $data->Spb->Customer->alamat;
                }
            })
            ->addColumn('telp', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->Customer->telp;
                } elseif ($name[1] == 'SPA') {
                    return $data->Spa->Customer->telp;
                } else {
                    return $data->Spb->Customer->telp;
                }
            })
            ->addColumn('ket', function ($data) {
                return $data->ket;
            })
            ->addColumn('tgl_awal', function ($data) {
                $tgl = date('d-m-Y', strtotime($data->tgl_kirim_min));
                return $tgl;
            })
            ->addColumn('tgl_akhir', function ($data) {
                $tgl = date('d-m-Y', strtotime($data->tgl_kirim_max));
                return $tgl;
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->so);
                $x = $name[1];
                $y = "";
                if ($x == 'EKAT') {
                    $y = $data->Ekatalog->id;
                } elseif ($x == 'SPA') {
                    $y = $data->Spa->id;
                } else {
                    $y = $data->Spb->id;
                }
                $z = 'selesai';
                return '<a href="' . route('logistik.so.detail', [$z, $y, $x]) . '" type="button" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye"></i> Detail
                    </a>';
            })
            ->rawColumns(['status', 'button', 'batas'])
            ->setRowClass(function ($data) {
                if ($data->Ekatalog) {
                    if ($data->Ekatalog->status == 'batal') {
                        return 'text-danger font-weight-bold';
                    }
                } else if ($data->Spa) {
                    if ($data->Spa->log == 'batal') {
                        return 'text-danger font-weight-bold';
                    }
                } else {
                    if ($data->Spb->log == 'batal') {
                        return 'text-danger font-weight-bold';
                    }
                }
            })
            ->make(true);
    }

    public function get_data_pesanan_sj($id)
    {
        $data = Logistik::orWhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->orWhereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('noresi', function ($data) {
                if (!empty($data->noresi)) {
                    return $data->noresi;
                } else {
                    return '-';
                }
            })->editColumn('tgl_kirim', function ($data) {
                if (!empty($data->tgl_kirim)) {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_kirim)->format('d-m-Y');
                } else {
                    return '-';
                }
            })->editColumn('status_id', function ($data) {
                if ($data->status_id == "10") {
                    return '<span class="badge green-text">Selesai</span>';
                } else if ($data->status_id == "11") {
                    return '<span class="badge red-text">Belum Kirim</span>';
                }
            })->editColumn('ekspedisi_id', function ($data) {
                if (!empty($data->ekspedisi_id)) {
                    return $data->Ekspedisi->nama;
                } else {
                    return $data->nama_pengirim;
                }
            })->addColumn('aksi', function ($data) {
                return '<a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
                    <i class="fas fa-file"></i>
                </a>';
            })
            ->addColumn('btn', function ($data) use ($id) {
                return '<a id="detail" class="detail" data-id="' . $data->id . '" data-parent=' . $id . '>
                            <i class="fas fa-eye"></i>
                        </a>';
            })
            ->rawColumns(['status_id', 'aksi', 'btn'])
            ->make(true);;
    }



    public function get_data_pesanan_filter_sj($id, Request $r)
    {
        $pengiriman = $r->pengiriman;
        $eks = $r->ekspedisi;
        $tgl_awal = $r->tgl_awal;
        $tgl_akhir = $r->tgl_akhir;
        if ($pengiriman == "ekspedisi") {
            if ($eks != '0') {
                $prd = Logistik::where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
                $part = Logistik::where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
            } else {
                $prd = Logistik::whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
                $part = Logistik::whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
            }
        } else if ($pengiriman == "nonekspedisi") {
            $prd = Logistik::whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $part = Logistik::whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
        } else {
            $prd = Logistik::whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir])->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
            $part = Logistik::whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir])->whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                $q->where('pesanan_id', $id);
            })->get();
        }
        $data = $prd->merge($part);

        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('noresi', function ($data) {
                if (!empty($data->noresi)) {
                    return $data->noresi;
                } else {
                    return '-';
                }
            })->editColumn('tgl_kirim', function ($data) {
                if (!empty($data->tgl_kirim)) {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_kirim)->format('d-m-Y');
                } else {
                    return '-';
                }
            })->editColumn('status_id', function ($data) {
                if ($data->status_id == "10") {
                    return '<span class="badge green-text">Selesai</span>';
                } else if ($data->status_id == "11") {
                    return '<span class="badge red-text">Belum Kirim</span>';
                }
            })->editColumn('ekspedisi_id', function ($data) {
                if (!empty($data->ekspedisi_id)) {
                    return $data->Ekspedisi->nama;
                } else {
                    return $data->nama_pengirim;
                }
            })->addColumn('aksi', function ($data) {
                return '<a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
                    <i class="fas fa-file"></i>
                </a>';
            })
            ->addColumn('btn', function ($data) use ($id) {
                return '<a id="detail" class="detail" data-id="' . $data->id . '" data-parent=' . $id . '>
                            <i class="fas fa-eye"></i>
                        </a>';
            })
            ->rawColumns(['status_id', 'aksi', 'btn'])
            ->make(true);;
    }
    public function get_data_pengiriman($pengiriman, $provinsi, $jenis_penjualan)
    {
        $x = explode(',', $pengiriman);
        $y = explode(',', $provinsi);
        $z = explode(',', $jenis_penjualan);
        $data = "";
        if ($pengiriman == "semua" && $provinsi == "semua" && $jenis_penjualan == "semua") {
            $dataeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->with([
                'DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Customer.Provinsi',
                'DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi',
                'DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi',
                'DetailLogistikPart.DetailPesananPart.Pesanan.Ekatalog.Customer.Provinsi',
                'DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi',
                'DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi',
                'Ekspedisi'
            ]);
            $data = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')
                ->with([
                    'DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Customer.Provinsi',
                    'DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi',
                    'DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi',
                    'DetailLogistikPart.DetailPesananPart.Pesanan.Ekatalog.Customer.Provinsi',
                    'DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi',
                    'DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi',
                    'Ekspedisi'
                ])
                ->union($dataeks)
                ->get();
        } else if ($pengiriman != "semua" && $provinsi == "semua" && $jenis_penjualan == "semua") {
            $dataeks = "";
            $datanoneks = "";

            if (in_array('ekspedisi', $x)) {
                $dataeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->get();
            }
            if (in_array('nonekspedisi', $x)) {
                $datanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->get();
            }

            if ($dataeks != "" && $datanoneks != "") {
                $data = $dataeks->merge($datanoneks);
            } else if ($dataeks != "" && $datanoneks == "") {
                $data = $dataeks;
            } else if ($dataeks == "" && $datanoneks != "") {
                $data = $datanoneks;
            }
        } else if ($pengiriman == "semua" && $provinsi != "semua" && $jenis_penjualan == "semua") {
            $ekatalogeks = "";
            $spaeks = "";
            $spbeks = "";

            $ekatalognoneks = "";
            $spanoneks = "";
            $spbnoneks = "";

            $ekatalogeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spaeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spbeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->orWhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $ekatalognoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            $spbnoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->orWhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->get();

            if (in_array('ekspedisi', $x)) {
                $dataeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->get();
            }
            if (in_array('nonekspedisi', $x)) {
                $datanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->get();
            }

            $data = $ekatalogeks->merge($ekatalognoneks)->merge($spaeks)->merge($spanoneks)->merge($spbeks)->merge($spbnoneks);
        } else if ($pengiriman == "semua" && $provinsi == "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $ekatalogeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                $ekatalognoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                $Ekatalog = $ekatalogeks->merge($ekatalognoneks);
            }

            if (in_array('spa', $z)) {
                $spaeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa')->get();
                $spanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa')->get();
                $Spa = $spaeks->merge($spanoneks);
            }

            if (in_array('spb', $z)) {
                $spbeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->get();
                $spbnoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->get();
                $Spb = $spbeks->merge($spbnoneks);
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman != "semua" && $provinsi != "semua" && $jenis_penjualan == "semua") {
            $eks = "";
            $noneks = "";
            if (in_array('ekspedisi', $x)) {
                $ekatalogeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spaeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spbeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $eks = $ekatalogeks->merge($spaeks)->merge($spbeks);
            }
            if (in_array('nonekspedisi', $x)) {
                $ekatalognoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orWhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orWhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $spbnoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orWhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orWhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = $ekatalognoneks->merge($spanoneks)->merge($spbnoneks);
            }

            if ($eks != "" && $noneks != "") {
                $data = $eks->merge($noneks);
            } else if ($eks != "" && $noneks == "") {
                $data = $eks;
            } else if ($eks == "" && $noneks != "") {
                $data = $noneks;
            }
        } else if ($pengiriman != "semua" && $provinsi == "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";
            if (in_array('ekat', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Ekatalog = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Ekatalog = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Ekatalog = $noneks;
                }
            }
            if (in_array('spa', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa')->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa')->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spa = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spa = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spa = $noneks;
                }
            }
            if (in_array('spb', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb')->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb')->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spb = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spb = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spb = $noneks;
                }
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman == "semua" && $provinsi != "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $Ekatalog = $eks->merge($noneks);
            }

            if (in_array('spa', $z)) {
                $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $Spa = $eks->merge($noneks);
            }

            if (in_array('spb', $z)) {
                $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->get();

                $Spb = $eks->merge($noneks);
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman != "semua" && $provinsi != "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Ekatalog = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Ekatalog = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Ekatalog = $noneks;
                }
            }

            if (in_array('spa', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spa = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spa = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spa = $noneks;
                }
            }

            if (in_array('spb', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spb = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spb = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spb = $noneks;
                }
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        }

        // $dataeks = Logistik::whereNull('noresi')->whereNotNull('ekspedisi_id')->get();
        // $datanoneks = Logistik::where('status_id', '11')->whereNotNull('nama_pengirim')->get();
        // $data = $dataeks->merge($datanoneks);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if (isset($data->DetailLogistik[0])) {
                    return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so;
                } else if (isset($data->DetailLogistikPart)) {
                    return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so;
                }
            })
            ->addColumn('sj', function ($data) {
                return $data->nosurat;
            })
            ->addColumn('ekspedisi', function ($data) {
                if (!empty($data->ekspedisi_id)) {
                    return $data->Ekspedisi->nama;
                } else {
                    return $data->nama_pengirim;
                }
            })
            ->addColumn('no_resi', function ($data) {
                return "-";
            })
            ->addColumn('tgl_kirim', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->tgl_kirim)->format('d-m-Y');
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->DetailLogistik[0])) {
                    $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                    }
                } else if (isset($data->DetailLogistikPart)) {
                    $name = explode('/',  $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                    if ($name[1] == 'SPA') {
                        return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->nama;
                    } else if ($name[1] == 'SPB') {
                        return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->nama;
                    }
                }
            })
            ->addColumn('provinsi', function ($data) {
                if (isset($data->DetailLogistik[0])) {
                    $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
                    }
                } else if (isset($data->DetailLogistikPart)) {
                    $name = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                    if ($name[1] == 'SPA') {
                        return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->Provinsi->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama;
                    }
                }
            })
            ->addColumn('status', function ($data) {
                if ($data->status_id  == "10") {
                    return '<span class="badge blue-text">' . $data->State->nama . '</span>';
                } else if ($data->status_id  == "11") {
                    if (auth()->user()->divisi_id == "15") {
                        return '<a id="ubahstatus" data-id="' . $data->id . '" data-status="10"><button class="btn btn-outline-info btn-sm btn-circle"><i class="fas fa-paper-plane"></i></button><div><small>Kirim</small></div></a>';
                    } else if (auth()->user()->divisi_id == "2") {
                        return '<span class="badge red-text">' . $data->State->nama . '</span>';
                    }
                }
            })
            ->addColumn('button', function ($data) {
                $string = "";
                $name = "";
                $provinsi = "";
                if (isset($data->DetailLogistik[0])) {
                    $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    if ($name[1] == 'EKAT') {
                        $provinsi =  $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->id;
                    } elseif ($name[1] == 'SPA') {
                        $provinsi =  $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->id;
                    } elseif ($name[1] == 'SPB') {
                        $provinsi =  $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->id;
                    }
                } else if (isset($data->DetailLogistikPart)) {
                    $name = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                    if ($name[1] == 'SPA') {
                        $provinsi = $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->Provinsi->id;
                    } elseif ($name[1] == 'SPB') {
                        $provinsi = $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->id;
                    }
                }


                $string .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="' . route('logistik.pengiriman.detail', ['id' => $data->id, 'jenis' => $name[1]]) . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </a>';
                if (auth()->user()->divisi_id == "15") {
                    $string .= '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-href="' . route('logistik.pengiriman.edit', [$data->id, $name[1]]) . '" data-id="' . $data->id . '" data-attr="' . $name[1] . '" data-provinsi="' . $provinsi . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                        </button>
                    </a>';
                }
                $string .= '<a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Surat Jalan
                        </button>
                    </a>
                </div>';
                return $string;
            })
            ->rawColumns(['status', 'button'])
            ->make(true);
    }

    public function get_data_riwayat_pengiriman($pengiriman, $provinsi, $jenis_penjualan, $years)
    {



        $x = explode(',', $pengiriman);
        $y = explode(',', $provinsi);
        $z = explode(',', $jenis_penjualan);
        $data = "";
        if ($pengiriman == "semua" && $provinsi == "semua" && $jenis_penjualan == "semua") {
            $dataeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereYear('tgl_kirim', $years)->orderByDesc('tgl_kirim')->get();
            $datanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereYear('tgl_kirim', $years)->orderByDesc('tgl_kirim')->get();
            $data = $dataeks->merge($datanoneks);
        } else if ($pengiriman != "semua" && $provinsi == "semua" && $jenis_penjualan == "semua") {
            $dataeks = "";
            $datanoneks = "";

            if (in_array('ekspedisi', $x)) {
                $dataeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereYear('tgl_kirim', $years)->get();
            }
            if (in_array('nonekspedisi', $x)) {
                $datanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereYear('tgl_kirim', $years)->get();
            }

            if ($dataeks != "" && $datanoneks != "") {
                $data = $dataeks->merge($datanoneks);
            } else if ($dataeks != "" && $datanoneks == "") {
                $data = $dataeks;
            } else if ($dataeks == "" && $datanoneks != "") {
                $data = $datanoneks;
            }
        } else if ($pengiriman == "semua" && $provinsi != "semua" && $jenis_penjualan == "semua") {
            $ekatalogeks = "";
            $spaeks = "";
            $spbeks = "";

            $ekatalognoneks = "";
            $spanoneks = "";
            $spbnoneks = "";

            $ekatalogeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->whereYear('tgl_kirim', $years)->get();

            $spaeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->whereYear('tgl_kirim', $years)->get();

            $spbeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->whereYear('tgl_kirim', $years)->get();

            $ekatalognoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->whereYear('tgl_kirim', $years)->get();

            $spanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->whereYear('tgl_kirim', $years)->get();

            $spbnoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orWhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->orWhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                $q->whereIN('status', $y);
            })->whereYear('tgl_kirim', $years)->get();

            if (in_array('ekspedisi', $x)) {
                $dataeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->get();
            }
            if (in_array('nonekspedisi', $x)) {
                $datanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->get();
            }

            $data = $ekatalogeks->merge($ekatalognoneks)->merge($spaeks)->merge($spanoneks)->merge($spbeks)->merge($spbnoneks);
        } else if ($pengiriman == "semua" && $provinsi == "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $ekatalogeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->whereYear('tgl_kirim', $years)->get();
                $ekatalognoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->whereYear('tgl_kirim', $years)->get();
                $Ekatalog = $ekatalogeks->merge($ekatalognoneks);
            }

            if (in_array('spa', $z)) {
                $spaeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa')->whereYear('tgl_kirim', $years)->get();
                $spanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa')->whereYear('tgl_kirim', $years)->get();
                $Spa = $spaeks->merge($spanoneks);
            }

            if (in_array('spb', $z)) {
                $spbeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->whereYear('tgl_kirim', $years)->get();
                $spbnoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->whereYear('tgl_kirim', $years)->get();
                $Spb = $spbeks->merge($spbnoneks);
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman != "semua" && $provinsi != "semua" && $jenis_penjualan == "semua") {
            $eks = "";
            $noneks = "";
            if (in_array('ekspedisi', $x)) {
                $ekatalogeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $spaeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $spbeks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $eks = $ekatalogeks->merge($spaeks)->merge($spbeks);
            }
            if (in_array('nonekspedisi', $x)) {
                $ekatalognoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $spanoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $spbnoneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $noneks = $ekatalognoneks->merge($spanoneks)->merge($spbnoneks);
            }

            if ($eks != "" && $noneks != "") {
                $data = $eks->merge($noneks);
            } else if ($eks != "" && $noneks == "") {
                $data = $eks;
            } else if ($eks == "" && $noneks != "") {
                $data = $noneks;
            }
        } else if ($pengiriman != "semua" && $provinsi == "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";
            if (in_array('ekat', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->whereYear('tgl_kirim', $years)->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->Has('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog')->whereYear('tgl_kirim', $years)->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Ekatalog = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Ekatalog = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Ekatalog = $noneks;
                }
            }
            if (in_array('spa', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa')->whereYear('tgl_kirim', $years)->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa')->whereYear('tgl_kirim', $years)->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spa = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spa = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spa = $noneks;
                }
            }
            if (in_array('spb', $z)) {
                $eks = "";
                $noneks = "";

                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->whereYear('tgl_kirim', $years)->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb')->orHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb')->whereYear('tgl_kirim', $years)->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spb = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spb = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spb = $noneks;
                }
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman == "semua" && $provinsi != "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $Ekatalog = $eks->merge($noneks);
            }

            if (in_array('spa', $z)) {
                $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $Spa = $eks->merge($noneks);
            }

            if (in_array('spb', $z)) {
                $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                    $q->whereIN('status', $y);
                })->whereYear('tgl_kirim', $years)->get();

                $Spb = $eks->merge($noneks);
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($pengiriman != "semua" && $provinsi != "semua" && $jenis_penjualan != "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";

            if (in_array('ekat', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->whereYear('tgl_kirim', $years)->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->whereYear('tgl_kirim', $years)->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Ekatalog = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Ekatalog = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Ekatalog = $noneks;
                }
            }

            if (in_array('spa', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->whereYear('tgl_kirim', $years)->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spa.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->whereYear('tgl_kirim', $years)->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spa = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spa = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spa = $noneks;
                }
            }

            if (in_array('spb', $z)) {
                $eks = "";
                $noneks = "";
                if (in_array('ekspedisi', $x)) {
                    $eks = Logistik::whereNotNull('noresi')->whereNotNull('ekspedisi_id')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->whereYear('tgl_kirim', $years)->get();
                }
                if (in_array('nonekspedisi', $x)) {
                    $noneks = Logistik::where('status_id', '10')->whereNotNull('nama_pengirim')->orwhereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->orwhereHas('DetailLogistikPart.DetailPesananPart.Pesanan.Spb.Customer.Provinsi', function ($q) use ($y) {
                        $q->whereIN('status', $y);
                    })->whereYear('tgl_kirim', $years)->get();
                }

                if ($eks != "" && $noneks != "") {
                    $Spb = $eks->merge($noneks);
                } else if ($eks != "" && $noneks == "") {
                    $Spb = $eks;
                } else if ($eks == "" && $noneks != "") {
                    $Spb = $noneks;
                }
            }

            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        }


        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if (isset($data->DetailLogistik[0])) {
                    return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so;
                } else {
                    return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so;
                }
            })
            ->addColumn('sj', function ($data) {
                return $data->nosurat;
            })
            ->addColumn('ekspedisi', function ($data) {
                if (!empty($data->ekspedisi_id)) {
                    return $data->ekspedisi->nama;
                } else {
                    return $data->nama_pengirim;
                }
            })
            ->addColumn('no_resi', function ($data) {
                if ($data->noresi == "") {
                    return '-';
                } else {
                    return $data->noresi;
                }
            })
            ->addColumn('tgl_kirim', function ($data) {
                return  Carbon::createFromFormat('Y-m-d', $data->tgl_kirim)->format('d-m-Y');
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->DetailLogistik[0])) {
                    $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                    } elseif ($name[1] == 'SPB') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                    }
                } else {
                    $name = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                    if ($name[1] == 'SPA') {
                        return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->nama;
                    } else if ($name[1] == 'SPB') {
                        return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->nama;
                    }
                }
            })
            ->addColumn('provinsi', function ($data) {
                if (isset($data->DetailLogistik[0])) {
                    $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                    } else if ($name[1] == 'SPA') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                    } else if ($name[1] == 'SPB') {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
                    }
                } else {
                    $name = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                    if ($name[1] == 'SPA') {
                        return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->Provinsi->nama;
                    } else if ($name[1] == 'SPB') {
                        return $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama;
                    }
                }
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge green-text">Selesai</span>';
            })
            ->addColumn('button', function ($data) {
                $name = "";
                if (isset($data->DetailLogistik[0])) {
                    $names = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    $name = $names[1];
                } else {
                    $names = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                    $name = $names[1];
                }
                return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="' . route('logistik.pengiriman.detail', ['id' => $data->id, 'jenis' => $name]) . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </a>
                    <a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Surat Jalan
                        </button>
                    </a>
                </div>';
            })
            ->rawColumns(['status', 'button'])
            ->make(true);
    }

    public function get_pengiriman_detail_data($id, $jenis)
    {
        $l = Logistik::find($id);
        return view('page.logistik.pengiriman.detail', ['id' => $id, 'l' => $l, 'jenis' => $jenis]);
    }

    public function get_produk_detail_pengiriman($id, $jenis)
    {
        $lprd = DetailLogistik::where('logistik_id', $id)->get();
        $lprt = DetailLogistikPart::where('logistik_id', $id)->get();
        $l = $lprd->merge($lprt);
        return datatables()->of($l)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama . ' ' . $data->DetailPesananProduk->GudangBarangJadi->nama;
                } else {
                    return $data->DetailPesananPart->Sparepart->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    return $data->NoseriDetailLogistik->count();
                } else {
                    return $data->jumlah;
                }
            })
            ->addColumn('no_seri', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    $array = array();
                    foreach ($data->NoseriDetailLogistik as $i) {
                        $array[] = $i->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri;
                    }
                    return implode(", ", $array);
                } else {
                    return '-';
                }
            })
            ->addColumn('keterangan', function ($data) {
                return "-";
            })
            ->addColumn('aksi', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-id="' . $data->id . '">
                    <div><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></div>
            </a>';
                } else {
                    return '-';
                }
            })

            ->rawColumns(['aksi'])
            ->make(true);
    }

    //Edit
    public function update_modal_surat_jalan($id, $jenis)
    {
        $data = Logistik::find($id);
        return view('page.logistik.pengiriman.edit', ['id' => $id, 'data' => $data, 'jenis' => $jenis]);
    }

    public function update_pengiriman(Request $request, $id)
    {
        $bool = true;
        $datass = "";
        $l = Logistik::find($id);
        if ($l->status_id == "11") {
            if ($request->nama_pengirim == NULL) {
                $l->nosurat = $request->no_invoice;
                $l->ekspedisi_id = $request->ekspedisi_id;
                $l->nama_pengirim = NULL;
                $r = $l->save();
                if (!$r) {
                    $bool = false;
                }
            } else {
                $l->nosurat = $request->no_invoice;
                $l->ekspedisi_id = NULL;
                $l->nama_pengirim = $request->nama_pengirim;
                $r = $l->save();
                if (!$r) {
                    $bool = false;
                }
            }
        } else if ($l->status_id == "10") {
            $l->noresi = $request->no_resi;
            $r = $l->save();
            if (!$r) {
                $bool = false;
            }
        }
        if ($bool == true) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }

    public function update_status_pengiriman($id, $status)
    {
        $l = Logistik::find($id);
        $l->status_id = $status;
        $l->save();
        if ($l) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }

    public function update_so($proses, $id, $value)
    {
        if ($value == 'EKAT') {
            $data = Ekatalog::find($id);

            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                $q->where('ekatalog.id', $id);
            })->get();

            $detail_id[] = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $pesanan_id = $data->pesanan_id;

            $ds = Pesanan::where('id', $pesanan_id)->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'ctfjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->first();

            $status = "";
            $res = $ds->cqcprd + $ds->cqcpart + $ds->ctfjasa;
            if ($res > 0) {
                $hitung = floor(((($ds->clogprd + $ds->clogpart + $ds->clogjasa) / ($ds->cqcprd + $ds->cqcpart + $ds->ctfjasa)) * 100));
                if ($hitung > 0) {
                    $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                } else {
                    $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                }
            } else {
                $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $res . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
            }

            $param = "";

            $tgl_sekarang = Carbon::now()->format('Y-m-d');
            $tgl_parameter = $ds->tgl_kontrak;

            if ($tgl_sekarang <= $tgl_parameter) {
                $to = Carbon::now();
                $from = $tgl_parameter;
                $hari = $to->diffInDays($from);

                if ($hari > 7) {
                    $param = ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas Sisa ' . $hari . ' Hari</small>';
                } else if ($hari > 0 && $hari <= 7) {
                    $param = ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                } else {
                    $param = '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                }
            } else {
                $to = Carbon::now();
                $from = $tgl_parameter;
                $hari = $to->diffInDays($from);
                $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
            }


            return view('page.logistik.so.detail_ekatalog', ['proses' => $proses, 'data' => $data, 'detail_id' => $detail_id, 'value' => $value, 'status' => $status, 'tgl_pengiriman' => $param]);
        } else if ($value == 'SPA') {
            $data = Spa::find($id);

            $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $detail_id[] = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $pesanan_id = $data->pesanan_id;

            $ds = Pesanan::where('id', $pesanan_id)->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'ctfjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->first();

            $status = "";
            $res = $ds->cqcprd + $ds->cqcpart + $ds->ctfjasa;
            if ($res > 0) {
                $hitung = floor(((($ds->clogprd + $ds->clogpart + $ds->clogjasa) / ($ds->cqcprd + $ds->cqcpart + $ds->ctfjasa)) * 100));
                if ($hitung > 0) {
                    $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                } else {
                    $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                }
            } else {
                $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $res . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
            }

            return view('page.logistik.so.detail_ekatalog', ['proses' => $proses, 'status' => $status, 'data' => $data, 'detail_id' => $detail_id, 'value' => $value, 'status' => $status]);
        } else {
            $data = Spb::find($id);

            $detail_pesanan  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();

            $detail_id[] = array();
            foreach ($detail_pesanan as $d) {
                $detail_id[] = $d->id;
            }

            $pesanan_id = $data->pesanan_id;

            // $jumlahterkirim = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
            //     $q->where('pesanan_id', $pesanan_id);
            // })->count();

            // $jumlahsudahuji = NoseriDetailPesanan::where('status', 'ok')->whereHas('DetailPesananProduk.DetailPesanan', function ($q) use ($pesanan_id) {
            //     $q->where('pesanan_id', $pesanan_id);
            // })->count();

            // if ($jumlahsudahuji == $jumlahterkirim) {
            //     $status =   '<span class="badge green-text">Sudah Dikirim</span>';
            // } else {
            //     if ($jumlahterkirim == 0) {
            //         $status =  ' <span class="badge red-text">Belum Dikirim</span>';
            //     } else {
            //         $status =   '<span class="badge yellow-text">Sebagian Dikirim</span>';
            //     }
            // }

            $ds = Pesanan::where('id', $pesanan_id)->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'cqcprd' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                        ->from('outgoing_pesanan_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'ctfjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                        ->from('detail_pesanan_part')
                        ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogjasa' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->whereRaw('m_sparepart.kode LIKE "%JASA%"')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->first();

            $status = "";
            $res = $ds->cqcprd + $ds->cqcpart + $ds->ctfjasa;
            if ($res > 0) {
                $hitung = floor(((($ds->clogprd + $ds->clogpart + $ds->clogjasa) / ($ds->cqcprd + $ds->cqcpart + $ds->ctfjasa)) * 100));
                if ($hitung > 0) {
                    $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                } else {
                    $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                }
            } else {
                $status = '<div class="progress progresscust">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $res . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
            }
            return view('page.logistik.so.detail_ekatalog', ['proses' => $proses, 'status' => $status, 'data' => $data, 'detail_id' => $detail_id, 'value' => $value, 'status' => $status]);
        }
    }

    public function cancel_so($id)
    {
        $p = Pesanan::where('id', $id)->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->first();

        return view('page.logistik.so.cancel', ['id' => $id, 'p' => $p]);
    }

    public function create_logistik_view($jenis)
    {
        return view('page.logistik.so.create', ['jenis' => $jenis]);
    }

    public function create_logistik(Request $request, $jenis)
    {
        // dd($request->all());
        $ids = "";
        $iddp = "";
        $poid = "";
        $kodesj = $request->jenis_sj;

        $bool = true;
        $Logistik = "";

        if ($request->no_sj_exist == 'baru') {

            if ($request->perusahaan_pengiriman == NULL || $request->alamat_pengiriman == NULL || $request->kemasan == NULL || $request->keterangan_pengiriman == NULL) {
                return response()->json(['data' => 'error']);
            }
            if ($request->pengiriman == 'ekspedisi') {
                $Logistik = Logistik::create([
                    'ekspedisi_id' => $request->ekspedisi_id,
                    'nosurat' => $kodesj . $request->no_invoice,
                    'tgl_kirim' => $request->tgl_kirim,
                    'nama_pengirim' => $request->nama_pengirim,
                    'nama_up' => $request->nama_pic,
                    'telp_up' => $request->telp_pic,
                    'ekspedisi_terusan' => $request->ekspedisi_terusan,
                    'dimensi' => $request->dimensi,
                    'tujuan_pengiriman' => $request->perusahaan_pengiriman,
                    'alamat_pengiriman' => $request->alamat_pengiriman,
                    'kemasan' => $request->kemasan,
                    'ket' => $request->keterangan_pengiriman,
                    'status_id' => '11'
                ]);
            } else {
                $Logistik = Logistik::create([
                    'nosurat' => $kodesj . $request->no_invoice,
                    'tgl_kirim' => $request->tgl_kirim,
                    'nama_pengirim' => $request->nama_pengirim,
                    'nama_up' => $request->nama_pic,
                    'telp_up' => $request->telp_pic,
                    'ekspedisi_terusan' => $request->ekspedisi_terusan,
                    'dimensi' => $request->dimensi,
                    'tujuan_pengiriman' => $request->perusahaan_pengiriman,
                    'alamat_pengiriman' => $request->alamat_pengiriman,
                    'kemasan' => $request->kemasan,
                    'ket' => $request->keterangan_pengiriman,
                    'status_id' => '11'
                ]);
            }
            $noseri = array();

            if ($jenis == "EKAT") {
                if ($Logistik) {
                    for ($i = 0; $i < count($request->produk_id); $i++) {
                        $c = DetailLogistik::create([
                            'logistik_id' => $Logistik->id,
                            'detail_pesanan_produk_id' => $request->produk_id[$i],
                        ]);

                        $noseri = explode(',', $request->produk_no_seri[$i]);
                        if ($c) {
                            for ($y = 0; $y < count($noseri); $y++) {
                                $b = NoseriDetailLogistik::create([
                                    'detail_logistik_id' => $c->id,
                                    'noseri_detail_pesanan_id' => $noseri[$y],
                                ]);
                            }
                            if (!$b) {
                                $bool = false;
                            }
                        } else {
                            $bool = false;
                        }
                    }
                } else {
                    $bool = false;
                }
                if ($ids) {
                    $iddp = DetailPesananProduk::find($ids);
                    $poid = $iddp->DetailPesanan->pesanan_id;
                }
            } else {
                if (isset($request->produk_id) && !isset($request->part_id)) {
                    if ($Logistik) {
                        for ($i = 0; $i < count($request->produk_id); $i++) {
                            $c = DetailLogistik::create([
                                'logistik_id' => $Logistik->id,
                                'detail_pesanan_produk_id' => $request->produk_id[$i],
                            ]);

                            $noseri = explode(',', $request->produk_no_seri[$i]);
                            if ($c) {
                                for ($y = 0; $y < count($noseri); $y++) {
                                    $b = NoseriDetailLogistik::create([
                                        'detail_logistik_id' => $c->id,
                                        'noseri_detail_pesanan_id' => $noseri[$y],
                                    ]);
                                }
                                if (!$b) {
                                    $bool = false;
                                }
                            } else {
                                $bool = false;
                            }
                        }
                    } else {
                        $bool = false;
                    }
                    if ($ids) {
                        $iddp = DetailPesananProduk::find($ids);
                        $poid = $iddp->DetailPesanan->pesanan_id;
                    }
                } else if (!isset($request->produk_id) && isset($request->part_id)) {
                    if ($Logistik) {
                        for ($i = 0; $i < count($request->part_id); $i++) {
                            $c = DetailLogistikPart::create([
                                'logistik_id' => $Logistik->id,
                                'detail_pesanan_part_id' => $request->part_id[$i],
                                'jumlah' => $request->part_jumlah[$i]
                            ]);

                            if (!$c) {
                                $bool = false;
                            }
                        }
                    } else {
                        return response()->json(['data' =>  $Logistik]);
                    }
                } else if (isset($request->produk_id) && isset($request->part_id)) {
                    if ($Logistik) {
                        for ($i = 0; $i < count($request->produk_id); $i++) {
                            $c = DetailLogistik::create([
                                'logistik_id' => $Logistik->id,
                                'detail_pesanan_produk_id' => $request->produk_id[$i],
                            ]);

                            $noseri = explode(',', $request->produk_no_seri[$i]);
                            if ($c) {
                                for ($y = 0; $y < count($noseri); $y++) {
                                    $b = NoseriDetailLogistik::create([
                                        'detail_logistik_id' => $c->id,
                                        'noseri_detail_pesanan_id' => $noseri[$y],
                                    ]);
                                }
                                if (!$b) {
                                    $bool = false;
                                }
                            } else {
                                $bool = false;
                            }
                        }
                    } else {
                        return response()->json(['data' =>  $Logistik]);
                    }

                    if ($Logistik) {
                        for ($i = 0; $i < count($request->part_id); $i++) {
                            $c = DetailLogistikPart::create([
                                'logistik_id' => $Logistik->id,
                                'detail_pesanan_part_id' => $request->part_id[$i],
                                'jumlah' => $request->part_jumlah[$i]
                            ]);

                            if (!$c) {
                                $bool = false;
                            }
                        }
                    } else {
                        return response()->json(['data' =>  $Logistik]);
                    }

                    if ($ids) {
                        $iddp = DetailPesananPart::find($ids);
                        $poid = $iddp->pesanan_id;
                    }
                }
            }
        } else {
            $Logistik = Logistik::find($request->sj_lama);
            if ($jenis == "EKAT") {
                if ($Logistik) {
                    for ($i = 0; $i < count($request->produk_id); $i++) {
                        $c = DetailLogistik::create([
                            'logistik_id' => $Logistik->id,
                            'detail_pesanan_produk_id' => $request->produk_id[$i],
                        ]);

                        $noseri = explode(',', $request->produk_no_seri[$i]);
                        if ($c) {
                            for ($y = 0; $y < count($noseri); $y++) {
                                $b = NoseriDetailLogistik::create([
                                    'detail_logistik_id' => $c->id,
                                    'noseri_detail_pesanan_id' => $noseri[$y],
                                ]);
                            }
                            if (!$b) {
                                $bool = false;
                            }
                        } else {
                            $bool = false;
                        }
                    }
                } else {
                    return response()->json(['data' =>  $Logistik]);
                }

                if ($ids) {
                    $iddp = DetailPesananProduk::find($ids);
                    $poid = $iddp->DetailPesanan->pesanan_id;
                }
            } else {

                if (isset($request->produk_id) && !isset($request->part_id)) {
                    if ($Logistik) {
                        for ($i = 0; $i < count($request->produk_id); $i++) {
                            $c = DetailLogistik::create([
                                'logistik_id' => $Logistik->id,
                                'detail_pesanan_produk_id' => $request->produk_id[$i],
                            ]);

                            $noseri = explode(',', $request->produk_no_seri[$i]);
                            if ($c) {
                                for ($y = 0; $y < count($noseri); $y++) {
                                    $b = NoseriDetailLogistik::create([
                                        'detail_logistik_id' => $c->id,
                                        'noseri_detail_pesanan_id' => $noseri[$y],
                                    ]);
                                }
                                if (!$b) {
                                    $bool = false;
                                }
                            } else {
                                $bool = false;
                            }
                        }
                    } else {
                        return response()->json(['data' =>  $Logistik]);
                    }
                    if ($ids) {
                        $iddp = DetailPesananProduk::find($ids);
                        $poid = $iddp->DetailPesanan->pesanan_id;
                    }
                }
                if (!isset($request->produk_id) && isset($request->part_id)) {
                    if ($Logistik) {
                        for ($i = 0; $i < count($request->part_id); $i++) {
                            $c = DetailLogistikPart::create([
                                'logistik_id' => $Logistik->id,
                                'detail_pesanan_part_id' => $request->part_id[$i],
                                'jumlah' => $request->part_jumlah[$i]
                            ]);

                            if (!$c) {
                                $bool = false;
                            }
                        }
                    } else {
                        return response()->json(['data' =>  $Logistik]);
                    }
                }
                if (isset($request->produk_id) && isset($request->part_id)) {
                    if ($Logistik) {
                        for ($i = 0; $i < count($request->produk_id); $i++) {
                            $c = DetailLogistik::create([
                                'logistik_id' => $Logistik->id,
                                'detail_pesanan_produk_id' => $request->produk_id[$i],
                            ]);

                            $noseri = explode(',', $request->produk_no_seri[$i]);
                            if ($c) {
                                for ($y = 0; $y < count($noseri); $y++) {
                                    $b = NoseriDetailLogistik::create([
                                        'detail_logistik_id' => $c->id,
                                        'noseri_detail_pesanan_id' => $noseri[$y],
                                    ]);
                                }
                                if (!$b) {
                                    $bool = false;
                                }
                            } else {
                                $bool = false;
                            }
                        }
                    } else {
                        return response()->json(['data' =>  $Logistik]);
                    }

                    if ($Logistik) {
                        for ($i = 0; $i < count($request->part_id); $i++) {
                            $c = DetailLogistikPart::create([
                                'logistik_id' => $Logistik->id,
                                'detail_pesanan_part_id' => $request->part_id[$i],
                                'jumlah' => $request->part_jumlah[$i]
                            ]);

                            if (!$c) {
                                $bool = false;
                            }
                        }
                    } else {
                        return response()->json(['data' =>  $Logistik]);
                    }

                    if ($ids) {
                        $iddp = DetailPesananPart::find($ids);
                        $poid = $iddp->pesanan_id;
                    }
                }
            }
        }

        $po = Pesanan::find($poid);
        if ($po) {
            if (count($po->DetailPesanan) > 0 && count($po->DetailPesananPart) <= 0) {
                if ($po->log_id == "10" || $po->log_id == "11" || $po->log_id == "13") {
                    if ($po->getJumlahKirim() == 0) {
                        $po->log_id = '11';
                        $po->save();
                    } else {
                        if ($po->getJumlahKirim() >= $po->getJumlahPesanan()) {
                            $po->log_id = '10';
                            $po->save();
                        } else {
                            $po->log_id = '13';
                            $po->save();
                        }
                    }
                }
            } else if (count($po->DetailPesanan) <= 0 && count($po->DetailPesananPart) > 0) {
                if ($po->log_id == "10" || $po->log_id == "11" || $po->log_id == "13") {
                    if ($po->getJumlahKirimPart() == 0) {
                        $po->log_id = '11';
                        $po->save();
                    } else {
                        if ($po->getJumlahKirimPart() >= $po->getJumlahPesananPart()) {
                            $po->log_id = '10';
                            $po->save();
                        } else {
                            $po->log_id = '13';
                            $po->save();
                        }
                    }
                }
            } else if (count($po->DetailPesanan) > 0 && count($po->DetailPesananPart) > 0) {
                if ($po->log_id == "10" || $po->log_id == "11" || $po->log_id == "13") {
                    if ($po->getJumlahKirim() == 0 && $po->getJumlahKirimPart() == 0) {
                        $po->log_id = '11';
                        $po->save();
                    } else if ($po->getJumlahKirim() > 0 || $po->getJumlahKirimPart() > 0) {
                        if ($po->getJumlahKirim() >= $po->getJumlahPesanan() && $po->getJumlahKirimPart() >= $po->getJumlahPesananPart()) {
                            $po->log_id = '10';
                            $po->save();
                        } else {
                            $po->log_id = '13';
                            $po->save();
                        }
                    }
                }
            }
        }

        if ($bool == true) {
            return response()->json(['data' => "success"]);
        } else if ($bool == false) {
            return response()->json(['data' => 'error']);
        }
    }

    //Dashboard
    public function dashboard()
    {
        // $terbaruprd = Pesanan::Has('TFProduksi')->WhereHas('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan', function ($q) {
        //     $q->where('tgl_uji', '>=', Carbon::now()->subdays(7));
        // })->orderby('id', 'desc')->get();
        // $terbarupart = Pesanan::whereHas('DetailPesananPart')->where('tgl_po', '>=', Carbon::now()->subdays(7))->orderby('id', 'desc')->get();
        // $terbaru = count($terbaruprd->merge($terbarupart));

        $terbaruprd = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->where('noseri_detail_pesanan.tgl_uji', '>=', Carbon::now()->subdays(7))
                ->groupBy('pesanan.id')
                ->havingRaw('count(noseri_detail_pesanan.id) > (select count(noseri_logistik.id)
            from noseri_logistik
            left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
            left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
            left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
            where detail_pesanan.pesanan_id = pesanan.id)');
        })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '9', '10']);

        $terbarupart = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('outgoing_pesanan_part', 'outgoing_pesanan_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->where('outgoing_pesanan_part.tanggal_uji', '>=', Carbon::now()->subdays(7))
                ->groupBy('pesanan.id')
                ->havingRaw("(sum(outgoing_pesanan_part.jumlah_ok) > (
                    select sum(detail_pesanan_part.jumlah)
                    from detail_pesanan_part
                    left join detail_logistik_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                    left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                    where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS
                       (select * from detail_logistik_part
                        left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id)) AND SUM(outgoing_pesanan_part.jumlah_ok) > 0");
        })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '10']);

        $terbaru = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->where('m_sparepart.kode', 'LIKE', '%JASA%')
                ->havingRaw("sum(detail_pesanan_part.jumlah) > (
                        select sum(detail_pesanan_part.jumlah)
                        from detail_pesanan_part
                        left join detail_logistik_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS(
                            select * from detail_logistik_part
                            left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                            left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                            where detail_pesanan_part.pesanan_id = pesanan.id)")
                ->groupBy('pesanan.id');
        })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '10'])->where('tgl_po', '>=', Carbon::now()->subdays(7))->union($terbaruprd)->union($terbarupart)->orderBy('id', 'desc')->count();

        // $belum_dikirimprd = Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan')->DoesntHave('DetailPesanan.DetailPesananProduk.DetailLogistik')->get();
        // $belum_dikirimpart = Pesanan::Has('DetailPesananPart')->doesntHave('DetailPesananPart.DetailLogistikPart')->get();
        // $belum_dikirim = count($belum_dikirimprd->merge($belum_dikirimpart));
        $belum_dikirimprd = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->groupBy('pesanan.id')
                ->havingRaw('count(noseri_detail_pesanan.id) > 0 AND NOT EXISTS (select *
            from noseri_logistik
            left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
            left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
            left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
            where detail_pesanan.pesanan_id = pesanan.id)');
        })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '9', '10']);

        $belum_dikirimpart = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('outgoing_pesanan_part', 'outgoing_pesanan_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->groupBy('pesanan.id')
                ->havingRaw("sum(outgoing_pesanan_part.jumlah_ok) > 0 AND NOT EXISTS
                       (select * from detail_logistik_part
                        left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id)");
        })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '10']);

        $belum_dikirim = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->where('m_sparepart.kode', 'LIKE', '%JASA%')
                ->havingRaw("sum(detail_pesanan_part.jumlah) > 0 AND NOT EXISTS(
                            select * from detail_logistik_part
                            left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                            left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                            where detail_pesanan_part.pesanan_id = pesanan.id)")
                ->groupBy('pesanan.id');
        })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '10'])->union($belum_dikirimprd)->union($belum_dikirimpart)->orderBy('id', 'desc')->count();

        $lewat_batas = Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan')->whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->groupBy('pesanan.id')
                ->havingRaw('count(noseri_detail_pesanan.id) > (
                SELECT count(noseri_logistik.id)
                from noseri_logistik
                left join noseri_detail_pesanan on noseri_logistik.noseri_detail_pesanan_id = noseri_detail_pesanan.id
                left join detail_pesanan_produk on noseri_detail_pesanan.detail_pesanan_produk_id = detail_pesanan_produk.id
                left join detail_pesanan on detail_pesanan_produk.detail_pesanan_id = detail_pesanan.id
                where detail_pesanan.pesanan_id = pesanan.id)');
        })->whereHas('Ekatalog.Provinsi', function ($q) {
            $q->whereRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY) < CURDATE(), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY) < CURDATE())');
        })->count();

        $cpo = Pesanan::addSelect(['cjumlahprd' => function ($q) {
            $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
        }, 'cjumlahpart' => function ($q) {
            $q->selectRaw('sum(detail_pesanan_part.jumlah)')
                ->from('detail_pesanan_part')
                ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
        }, 'clogprd' => function ($q) {
            $q->selectRaw('count(noseri_logistik.id)')
                ->from('noseri_logistik')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
        }, 'clogpart' => function ($q) {
            $q->selectRaw('sum(detail_logistik_part.jumlah)')
                ->from('detail_logistik_part')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
        }])
            ->whereIn('log_id', ['9'])
            ->havingRaw('clogprd < cjumlahprd OR clogpart < cjumlahpart')
            ->count();

        $cgudang = Pesanan::addSelect(['jumlah_produk' => function ($q) {
            $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
        }, 'jumlah_gudang' => function ($q) {
            $q->selectRaw('count(t_gbj_noseri.id)')
                ->from('t_gbj_noseri')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
        }])->whereNotIn('log_id', ['7'])->havingRaw('jumlah_produk > jumlah_gudang')->count();

        $cqc = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect([
            'tgl_kontrak' => function ($q) {
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                    ->from('ekatalog')
                    ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                    ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                    ->limit(1);
            },
            'ctfprd' => function ($q) {
                $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                    ->from('t_gbj_noseri')
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                    ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
            },
            'cqcprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                    ->from('noseri_detail_pesanan')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('noseri_detail_pesanan.status', 'ok')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
            'ctfpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                    ->from('detail_pesanan_part')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },
            'cqcpart' => function ($q) {
                $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                    ->from('outgoing_pesanan_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->where('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },
            'clogprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                    ->from('noseri_logistik')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->limit(1);
            },
            'clogpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                    ->from('detail_logistik_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                    ->limit(1);
            }
        ])
            ->with(['ekatalog.customer.provinsi', 'spa.customer.provinsi', 'spb.customer.provinsi'])
            ->havingRaw('(ctfprd > cqcprd AND ctfprd > 0) OR (ctfpart > cqcpart AND ctfpart > 0)')
            ->orderBy('tgl_kontrak', 'asc')
            ->count();

        return view('page.logistik.dashboard', ['terbaru' => $terbaru, 'belum_dikirim' => $belum_dikirim, 'lewat_batas' => $lewat_batas, 'po' => $cpo, 'gudang' => $cgudang, 'qc' => $cqc]);
    }
    public function dashboard_data($value)
    {
        if ($value == 'terbaru') {
            $prd = Pesanan::whereIn('id', function ($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                    ->where('noseri_detail_pesanan.tgl_uji', '>=', Carbon::now()->subdays(7))
                    ->groupBy('pesanan.id')
                    ->havingRaw('count(noseri_detail_pesanan.id) > (select count(noseri_logistik.id)
                from noseri_logistik
                left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                where detail_pesanan.pesanan_id = pesanan.id)');
            })->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }, 'clogprd' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('sum(detail_logistik_part.jumlah)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '9', '10']);

            $part = Pesanan::whereIn('id', function ($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('outgoing_pesanan_part', 'outgoing_pesanan_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->where('outgoing_pesanan_part.tanggal_uji', '>=', Carbon::now()->subdays(7))
                    ->groupBy('pesanan.id')
                    ->havingRaw("(sum(outgoing_pesanan_part.jumlah_ok) > (
                        select sum(detail_pesanan_part.jumlah)
                        from detail_pesanan_part
                        left join detail_logistik_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS
                           (select * from detail_logistik_part
                            left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                            left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                            where detail_pesanan_part.pesanan_id = pesanan.id)) AND SUM(outgoing_pesanan_part.jumlah_ok) > 0");
            })->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }, 'clogprd' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('sum(detail_logistik_part.jumlah)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '10']);

            $data = Pesanan::whereIn('id', function ($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'LIKE', '%JASA%')
                    ->havingRaw("sum(detail_pesanan_part.jumlah) > (
                            select sum(detail_pesanan_part.jumlah)
                            from detail_pesanan_part
                            left join detail_logistik_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                            left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                            where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS(
                                select * from detail_logistik_part
                                left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                                left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                                where detail_pesanan_part.pesanan_id = pesanan.id)")
                    ->groupBy('pesanan.id');
            })->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }, 'clogprd' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('sum(detail_logistik_part.jumlah)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '10'])->where('tgl_po', '>=', Carbon::now()->subdays(7))->union($prd)->union($part)->orderBy('tgl_kontrak', 'asc')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('batas', function ($data) {
                    if ($data->tgl_kontrak != "") {
                        if ($data->log_id != "10") {
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') <= $tgl_parameter) {
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div id="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else {
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lewat ' . $hari . ' Hari</small></div>';
                            }
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                        }
                    }
                })
                ->addColumn('status', function ($data) {

                    $cdata = $data->clogprd + $data->clogpart;
                    if ($cdata <= 0) {
                        return '<span class="badge red-text">Belum Dikirim</span>';
                    } else {
                        return '<span class="badge yellow-text">Sebagian Dikirim</span>';
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);
                    $x = $name[1];
                    if ($x == 'EKAT') {
                        $y = $data->ekatalog->id;
                    } elseif ($x == 'SPA') {
                        $y = $data->spa->id;
                    } else {
                        $y = $data->spb->id;
                    }

                    $z = "";
                    // if ($data->getJumlahCek() == $data->getJumlahPesanan()) {
                    //     $z = "selesai";
                    // } else {
                    $z = "proses";
                    // }
                    return '
                        <a href="' . route('logistik.so.detail', [$z, $y, $x]) . '" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Detail
                        </a>';
                })
                ->rawColumns(['batas', 'status', 'button'])
                ->make(true);
        } else if ($value == 'belum_dikirim') {
            $belum_dikirimprd = Pesanan::whereIn('id', function ($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                    ->groupBy('pesanan.id')
                    ->havingRaw('count(noseri_detail_pesanan.id) > 0 AND NOT EXISTS (select *
                from noseri_logistik
                left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                where detail_pesanan.pesanan_id = pesanan.id)');
            })->addSelect(['tgl_kontrak' => function ($q) {
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                    ->from('ekatalog')
                    ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                    ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                    ->limit(1);
            }])->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '9', '10']);

            $belum_dikirimpart = Pesanan::whereIn('id', function ($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('outgoing_pesanan_part', 'outgoing_pesanan_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->groupBy('pesanan.id')
                    ->havingRaw("sum(outgoing_pesanan_part.jumlah_ok) > 0 AND NOT EXISTS
                           (select * from detail_logistik_part
                            left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                            left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                            where detail_pesanan_part.pesanan_id = pesanan.id)");
            })->addSelect(['tgl_kontrak' => function ($q) {
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                    ->from('ekatalog')
                    ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                    ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                    ->limit(1);
            }])->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '10']);

            $data = Pesanan::whereIn('id', function ($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'LIKE', '%JASA%')
                    ->havingRaw("sum(detail_pesanan_part.jumlah) > 0 AND NOT EXISTS(
                                select * from detail_logistik_part
                                left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                                left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                                where detail_pesanan_part.pesanan_id = pesanan.id)")
                    ->groupBy('pesanan.id');
            })->addSelect(['tgl_kontrak' => function ($q) {
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                    ->from('ekatalog')
                    ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                    ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                    ->limit(1);
            }])->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '10'])->union($belum_dikirimprd)->union($belum_dikirimpart)->orderBy('tgl_kontrak', 'asc')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('batas', function ($data) {
                    if ($data->tgl_kontrak != "") {
                        if ($data->log_id != "10") {
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') <= $tgl_parameter) {
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div id="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else {
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lewat ' . $hari . ' Hari</small></div>';
                            }
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                        }
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);
                    $x = $name[1];
                    if ($x == 'EKAT') {
                        $y = $data->ekatalog->id;
                    } elseif ($x == 'SPA') {
                        $y = $data->spa->id;
                    } else {
                        $y = $data->spb->id;
                    }
                    $z = "proses";
                    return '<a href="' . route('logistik.so.detail', [$z, $y, $x]) . '" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                            </a>';
                })
                ->rawColumns(['batas', 'button'])
                ->make(true);
        } else {

            $data = Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan')->whereIn('id', function ($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                    ->groupBy('pesanan.id')
                    ->havingRaw('count(noseri_detail_pesanan.id) > (
                            SELECT count(noseri_logistik.id)
                            from noseri_logistik
                            left join noseri_detail_pesanan on noseri_logistik.noseri_detail_pesanan_id = noseri_detail_pesanan.id
                            left join detail_pesanan_produk on noseri_detail_pesanan.detail_pesanan_produk_id = detail_pesanan_produk.id
                            left join detail_pesanan on detail_pesanan_produk.detail_pesanan_id = detail_pesanan.id
                            where detail_pesanan.pesanan_id = pesanan.id)');
            })->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }, 'clogprd' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'clogpart' => function ($q) {
                    $q->selectRaw('sum(detail_logistik_part.jumlah)')
                        ->from('detail_logistik_part')
                        ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])->havingRaw('tgl_kontrak < CURDATE()')->with('Ekatalog.Customer.Provinsi')->orderBy('tgl_kontrak', 'asc')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('batas', function ($data) {
                    if ($data->tgl_kontrak != "") {
                        if ($data->log_id != "10") {
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') <= $tgl_parameter) {
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div id="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else {
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lewat ' . $hari . ' Hari</small></div>';
                            }
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                        }
                    }
                })
                ->addColumn('status', function ($data) {
                    $cdata = $data->clogprd + $data->clogpart;
                    if ($cdata <= 0) {
                        return '<span class="badge red-text">Belum Dikirim</span>';
                    } else {
                        return '<span class="badge yellow-text">Sebagian Dikirim</span>';
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);
                    $x = $name[1];
                    if ($x == 'EKAT') {
                        $y = $data->ekatalog->id;
                    } elseif ($x == 'SPA') {
                        $y = $data->spa->id;
                    } else {
                        $y = $data->spb->id;
                    }
                    $z = "proses";
                    return '
                        <a href="' . route('logistik.so.detail', [$z, $y, $x]) . '" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Detail
                        </a>';
                })
                ->rawColumns(['batas', 'status', 'button'])
                ->make(true);
        }
    }

    public function update_pengiriman_retur(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'no_surat_jalan' => ['required'],
            'tgl_pengiriman' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => 'error', 'msg' => 'Periksa Kembali Form Anda']);
        } else {
            $u = Pengiriman::find($r->id);
            $u->no_pengiriman = $r->no_surat_jalan;
            $u->tanggal = $r->tgl_pengiriman;
            $u->ekspedisi_id = $r->ekspedisi_id;
            $u->pengirim = $r->non_ekspedisi;
            $u->biaya_kirim = str_replace('.', '', $r->biaya_kirim);
            $up = $u->save();


            if ($up) {
                return response()->json(['data' => "success", 'msg' => 'Berhasil mengubah Detail Pengiriman']);
            } else {
                return response()->json(['data' => 'error', 'msg' => 'Gagal mengubah Detail Pengiriman']);
            }
        }
    }

    public function dashboard_so()
    {
        $data = Pesanan::addSelect([
            'cjumlahprd' => function ($q) {
                $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            }, 'cjumlahpart' => function ($q) {
                $q->selectRaw('sum(detail_pesanan_part.jumlah)')
                    ->from('detail_pesanan_part')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            }, 'clogprd' => function ($q) {
                $q->selectRaw('count(noseri_logistik.id)')
                    ->from('noseri_logistik')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->limit(1);
            },
            'clogpart' => function ($q) {
                $q->selectRaw('sum(detail_logistik_part.jumlah)')
                    ->from('detail_logistik_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                    ->limit(1);
            }
        ])
            ->whereNotIn('log_id', ['7', '20'])
            ->havingRaw('clogprd < cjumlahprd OR clogpart < cjumlahpart')
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('customer', function ($data) {
                if ($data->Ekatalog) {
                    return '<div>' . $data->Ekatalog->Customer->nama . '</div><small>' . $data->Ekatalog->instansi . '</small>';
                } else if ($data->Spa) {
                    return $data->Spa->Customer->nama;
                } else if ($data->Spb) {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                $hitung = floor(((($data->clogprd + $data->clogpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
                if ($data->log_id == "9") {
                    $datas = '<span class="badge purple-text">' . $data->State->nama . '</span>';
                } else {
                    if ($hitung > 0) {
                        $datas = '<div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted">Selesai</small>';
                    } else {
                        $datas = '<div class="progress">
                            <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted">Selesai</small>';
                    }
                }
                return $datas;
            })
            ->addColumn('aksi', function ($data) {
                $id = "";
                $jenis = "";
                if ($data->Ekatalog) {
                    $id = $data->Ekatalog->id;
                    $jenis = "ekatalog";
                } else if ($data->Spa) {
                    $id = $data->Spa->id;
                    $jenis = "spa";
                } else if ($data->Spb) {
                    $id = $data->Spb->id;
                    $jenis = "spb";
                }
                return  '<a data-toggle="modal" data-target="' . $jenis . '" class="somodal" data-attr="' . route('penjualan.penjualan.detail.' . $jenis,  $id) . '"  data-id="' . $id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
            })
            ->rawColumns(['customer', 'status', 'aksi'])
            ->make(true);
    }

    //Other
    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '14';
        } else {
            $days = '21';
        }
        return Carbon::parse($value)->subDays($days);
    }

    static function getHariBatasKontrak1($value, $limit)
    {
        if ($limit == 2) {
            $days = '28';
        } else {
            $days = '35';
        }
        return Carbon::parse($value)->subDays($days);
    }

    public function get_data_noseri_array($produk_id, $jumlah_kirim)
    {
        $data = NoseriDetailPesanan::where(['status' => 'ok', 'detail_pesanan_produk_id' => $produk_id])->DoesntHave('NoseriDetailLogistik')->skip(0)->take($jumlah_kirim)->pluck('id');
        echo json_encode($data);
    }

    public function get_surat_jalan_belum_kirim($po)
    {
        $nopo = str_replace("!", "/", $po);
        $dataprd = Logistik::where('status_id', '=', '11')->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($nopo) {
            $q->where('Pesanan.no_po', $nopo);
        })->get();

        $datapart = Logistik::where('status_id', '=', '11')->whereHas('DetailLogistikPart.DetailPesananPart.Pesanan', function ($q) use ($nopo) {
            $q->where('Pesanan.no_po', $nopo);
        })->get();

        $data = $dataprd->merge($datapart);

        echo json_encode($data);
    }

    public function get_data_laporan_logistik($pengiriman, $ekspedisi, $tgl_awal, $tgl_akhir)
    {
        $s = "";
        if ($pengiriman == "ekspedisi") {

            if ($ekspedisi != '0') {
                $prd = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.DetailLogistik.Logistik', function ($q) use ($ekspedisi, $tgl_awal, $tgl_akhir) {
                    $q->where('ekspedisi_id', $ekspedisi)->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
                })->get();
                $prt = Pesanan::whereHas('DetailPesananPart.DetailLogistikPart.Logistik', function ($q) use ($ekspedisi, $tgl_awal, $tgl_akhir) {
                    $q->where('ekspedisi_id', $ekspedisi)->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
                })->get();
            } else {
                $prd = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.DetailLogistik.Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                    $q->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir])->whereNotNull('ekspedisi_id');
                })->get();
                $prt = Pesanan::whereHas('DetailPesananPart.DetailLogistikPart.Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                    $q->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir])->whereNotNull('ekspedisi_id');
                })->get();
            }

            $s = $prd->merge($prt);
        } else if ($pengiriman == "nonekspedisi") {
            $prd = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.DetailLogistik.Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
            $prt = Pesanan::whereHas('DetailPesananPart.DetailLogistikPart.Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
            $s = $prd->merge($prt);
        } else {
            $prd = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.DetailLogistik.Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
            $prt = Pesanan::whereHas('DetailPesananPart.DetailLogistikPart.Logistik', function ($q) use ($tgl_awal, $tgl_akhir) {
                $q->whereBetween('tgl_kirim', [$tgl_awal, $tgl_akhir]);
            })->get();
            $s = $prd->merge($prt);
        }

        return datatables()->of($s)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('no_paket', function ($data) {
                if (isset($data->Ekatalog)) {
                    return $data->Ekatalog->no_paket;
                } else {
                    return '-';
                }
            })
            ->addColumn('po', function ($data) {
                return $data->no_po;
            })
            ->addColumn('tgl_po', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y');
            })
            ->addColumn('customer', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->instansi;
                } else if ($data->Spa) {
                    return $data->Spa->Customer->nama;
                } else if ($data->Spb) {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->alamat;
                } else if ($data->Spa) {
                    return $data->Spa->Customer->alamat;
                } else if ($data->Spb) {
                    return $data->Spb->Customer->alamat;
                }
            })
            ->addColumn('provinsi', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->Provinsi->nama;
                } else if ($data->Spa) {
                    return $data->Spa->Customer->Provinsi->nama;
                } else if ($data->Spb) {
                    return $data->Spb->Customer->Provinsi->nama;
                }
            })
            // ->addColumn('telp', function ($data) {
            //     if (isset($data->DetailPesananProduk)) {
            //         $name = explode('/', $data->DetailPesananProduk->DetailPesanan->pesanan->so);
            //         if ($name[1] == 'EKAT') {
            //             return $data->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->telp;
            //         } elseif ($name[1] == 'SPA') {
            //             return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->telp;
            //         } elseif ($name[1] == 'SPB') {
            //             return $data->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->telp;
            //         }
            //     } else {
            //         $name = explode('/', $data->DetailPesananPart->Pesanan->so);
            //         if ($name[1] == 'SPA') {
            //             return $data->DetailPesananPart->Pesanan->Spa->Customer->telp;
            //         } else if ($name[1] == 'SPB') {
            //             return $data->DetailPesananPart->Pesanan->Spb->Customer->telp;
            //         }
            //     }
            // })
            // ->addColumn('ekspedisi', function ($data) {
            //     if (!empty($data->Logistik->ekspedisi_id)) {
            //         return $data->Logistik->Ekspedisi->nama;
            //     } else {
            //         return $data->Logistik->nama_pengirim;
            //     }
            // })
            // ->addColumn('tgl_kirim', function ($data) {
            //     return Carbon::createFromFormat('Y-m-d', $data->Logistik->tgl_kirim)->format('d-m-Y');
            // })

            // ->addColumn('produk', function ($data) {
            //     if (isset($data->DetailPesananProduk)) {
            //         $datas = $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
            //         if ($data->DetailPesananProduk->GudangBarangJadi->nama != '') {
            //             $datas .= "<div class=text-primary><small>" . $data->DetailPesananProduk->GudangBarangJadi->nama . "</small></div>";
            //         }
            //         return $datas;
            //     } else {
            //         return $data->DetailPesananPart->Sparepart->nama;
            //     }
            // })
            // ->addColumn('jumlah', function ($data) {
            //     if (isset($data->NoseriDetailLogistik)) {
            //         return $data->NoseriDetailLogistik->count();
            //     } else {
            //         return $data->DetailPesananPart->jumlah;
            //     }
            // })

            // ->addColumn('status', function ($data) {
            //     return $data->Logistik->State->nama;
            // })
            ->rawColumns(['status', 'produk'])
            ->make(true);
    }

    public function tgl_footer($value)
    {
        $footer = Carbon::createFromFormat('Y-m-d', $value)->isoFormat('D MMMM Y');
        return $footer;
    }
    public function get_surat_jalan_detail($id)
    {
        $l = Logistik::with('Ekspedisi')->where('id', $id)->get();
        return json_encode($l);
    }
    public function check_no_sj($id, $val)
    {
        $year = Carbon::now()->format('Y');
        $count = "";
        if ($id != "0") {
            $count_sj_penjualan = Logistik::where([['id', '!=', $id], ['nosurat', '=', $val]])->whereYear('tgl_kirim', $year)->count();
            $count_sj_retur = Pengiriman::where([['id', '!=', $id], ['no_pengiriman', '=', $val]])->whereYear('tanggal', $year)->count();
            $count = $count_sj_penjualan + $count_sj_retur;
        } else {
            $count_sj_penjualan = Logistik::where('nosurat', $val)->whereYear('tgl_kirim', $year)->count();
            $count_sj_retur = Pengiriman::where('no_pengiriman', $val)->whereYear('tanggal', $year)->count();
            $count = $count_sj_penjualan + $count_sj_retur;
        }
        return $count;
    }

    public function check_no_resi($val)
    {
        $vals = str_replace("_", "/", $val);
        $e = Logistik::where([['noresi', '!=', '-'], ['noresi', '=', $vals]])->whereYear('created_at', $this->getYear())->count();
        return $e;
    }

    public function export_laporan($jenis, $ekspedisi, $tgl_awal, $tgl_akhir)
    {
        $waktu = Carbon::now();

        if ($jenis == "ekspedisi") {
            return Excel::download(new LaporanLogistik($jenis, $ekspedisi, $tgl_awal, $tgl_akhir), 'Laporan Pengiriman Ekspedisi ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($jenis == "nonekspedisi") {
            return Excel::download(new LaporanLogistik($jenis, $ekspedisi, $tgl_awal, $tgl_akhir), 'Laporan Pengiriman Non Ekspedisi ' . $waktu->toDateTimeString() . '.xlsx');
        } else {
            return Excel::download(new LaporanLogistik($jenis, $ekspedisi, $tgl_awal, $tgl_akhir), 'Laporan Pengiriman Ekspedisi dan Non Ekspedisi ' . $waktu->toDateTimeString() . '.xlsx');
        }
    }

    public function get_data_pesanan_sj_draft($id)
    {
        $log = LogistikDraft::where('pesanan_id', $id)->get();
        foreach ($log as $key => $l) {
            $data[$key] = array(
                'id' => $l->id,
                'sj' => $l->sj
            );
        }

        if (isset($data)) {
            return response()->json(['data' => $data]);
        } else {
            return response()->json(['data' => []]);
        }
    }
    public function get_data_pesanan_sj_draft_detail($id)
    {
        $logistik = LogistikDraft::find($id);
        $log = json_decode($logistik->isi);
        return response()->json(['data' => $log]);
    }
    public function update_logistik_draft(Request $request)
    {
        if ($request->sj == "" || $request->tgl_sj == "" || $request->id == "") {
            return response()->json([
                'status' => 404,
                'message' => 'Gagal',
            ], 200);
        } else {
            $data = LogistikDraft::find($request->id);

            $getData = json_decode($data->isi, true);
            $getData['nosj'] = $request->sj;
            $getData['tgl_sj'] = $request->tgl_sj;
            $saveData = json_encode($getData);

            $data->sj = $request->sj;
            $data->isi = $saveData;
            $data->save();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
                'pesanan_id' => $data->pesanan_id,
            ], 200);
        }
    }

    public function create_logistik_draft(Request $request)
    {
        $items = array();

        if (isset($request->part)) {
            foreach ($request->part as $key_p => $i) {
                $part[$key_p] = array(
                    "jenis" => 'part',
                    "kode" => $i['kode'],
                    "nama" => $i['nama'],
                    "jumlah" => $i['jumlah'],
                    "satuan" => '-',
                );
            }
            $items = $part;
        }

        // $parts[]= array(
        //     "jenis"=> 'part',
        //     "no"=> 1,
        //     "kode"=> 'SP012333',
        //     "nama"=> 'YUUDDDDF FDFDF',
        //     "jumlah"=> 2,
        //     "satuan"=> 'UNIT',
        // );

        // $items = $parts;
        if (isset($request->produk)) {
            // dd($request->produk);
            // foreach($request->produk as $key_pr => $i){
            //     $produk[$key_pr]= array(
            //         "jenis"=> 'produk',
            //         "kode"=> $i['kode'] ?? "",
            //         "nama"=> $i['nama_alias'] ?? $i['nama'],
            //         "jumlah"=> $i['jumlah'],
            //         "jumlah_noseri"=> $i['jumlah_noseri'],
            //         "satuan"=> 'Unit',
            //         "noseri"=> $i['noseri_selected']
            //     );
            // }
            $maxJumlah = 0;
            $tas = false;
            $adaptor = false;
            foreach ($request->produk as $item) {
                $id = $item["detail_pesanan_id"];
                $nama_paket = $item["nama_alias"];
                if ($item["jumlah_noseri"] == 0) {
                    $item['noseri_selected'] = [];
                    $jumlahs = intval($item['jumlah']);
                } else {
                    $jumlahs = intval($item['jumlah_noseri']);
                }


                if (!isset($produk[$id])) {
                    $produk[$id] = array(
                        "id" => $id,
                        "penjualan_produk_id" => $item["penjualan_produk_id"],
                        "jenis" => 'produk',
                        "nama" => $nama_paket,
                        "jumlah" =>  max($maxJumlah, $jumlahs),
                        "detail" => array()
                    );

                    if ($item["penjualan_produk_id"] == 183) {
                        $adaptor = true;
                    }
                    if ($item["penjualan_produk_id"] == 5 || $item["penjualan_produk_id"] == 29 || $item["penjualan_produk_id"] == 114 || $item["penjualan_produk_id"] == 284 || $item["penjualan_produk_id"] == 376 || $item["penjualan_produk_id"] == 363) {
                        $tas = true;
                    }
                    // if( $item["penjualan_produk_id"] == 183 ){
                    //     $produk[$id]["detail"][] = array(
                    //         "kode"=> "-",
                    //         "nama"=>  "POWER ADAPTOR",
                    //         "jumlah"=> $item['jumlah'],
                    //         "jumlah_noseri" =>  $item['jumlah_noseri'],
                    //         "satuan" => 'Unit',
                    //         "noseri"=> array('-')
                    //     );
                    // }

                    // if( $item["penjualan_produk_id"] == 5 || $item["penjualan_produk_id"] == 29 || $item["penjualan_produk_id"] == 114 || $item["penjualan_produk_id"] == 284 || $item["penjualan_produk_id"] == 376 || $item["penjualan_produk_id"] == 363){
                    //     $produk[$id]["detail"][$maxJumlah+1] = array(
                    //         "kode"=> "-",
                    //         "nama"=>  "TAS ANTROPOMETRI KIT",
                    //         "jumlah"=> $item['jumlah'],
                    //         "jumlah_noseri" =>  $item['jumlah_noseri'],
                    //         "satuan" => 'Unit',
                    //         "noseri"=> array('-')
                    //     );
                    // }
                }

                if ($item["penjualan_produk_id"] == 5 || $item["penjualan_produk_id"] == 29 || $item["penjualan_produk_id"] == 114 || $item["penjualan_produk_id"] == 284 || $item["penjualan_produk_id"] == 376 || $item["penjualan_produk_id"] == 363) {
                    $produk[$id]["detail"][0] = array(
                        "kode" => $item['kode'] ?? "",
                        "nama" =>  $item['nama'],
                        "jumlah" => $item['jumlah'],
                        "jumlah_noseri" => $item['jumlah_noseri'],
                        "satuan" => 'Unit',
                        "noseri" => $item['noseri_selected']
                    );
                } else {
                    $produk[$id]["detail"][] = array(
                        "kode" => $item['kode'] ?? "",
                        "nama" =>  $item['nama'],
                        "jumlah" => $item['jumlah'],
                        "jumlah_noseri" => $item['jumlah_noseri'],
                        "satuan" => 'Unit',
                        "noseri" => $item['noseri_selected']
                    );
                }
            }

            if ($tas) {
                $itemIndex = array();
                foreach ($produk as $index => $item) {
                    if ($item['penjualan_produk_id'] === "5" || $item["penjualan_produk_id"] == 29 || $item["penjualan_produk_id"] == 114 || $item["penjualan_produk_id"] == 284 || $item["penjualan_produk_id"] == 376 || $item["penjualan_produk_id"] == 363) {
                        $itemIndex[] = $index;
                        // break;
                    }
                }

                if (count($itemIndex) > 0) {
                    for ($i = 0; $i < count($itemIndex); $i++) {
                        $newDetail = [
                            "kode" => "-",
                            "nama" =>  "TAS ANTROPOMETRI KIT",
                            "jumlah" =>   $produk[$itemIndex[$i]]['jumlah'],
                            "jumlah_noseri" =>  0,
                            "satuan" => 'Unit',
                            "noseri" => array('-')
                        ];
                        $produk[$itemIndex[$i]]["detail"][] = $newDetail;
                    }
                }
            }



            if ($adaptor) {
                $itemIndex = array();
                foreach ($produk as $index => $item) {
                    if ($item['penjualan_produk_id'] === "183") {
                        $itemIndex[] = $index;
                    }
                }

                if (count($itemIndex) > 0) {
                    for ($i = 0; $i < count($itemIndex); $i++) {
                        $newDetail = [
                            "kode" => "-",
                            "nama" =>  "POWER ADAPTOR",
                            "jumlah" => $item['jumlah'],
                            "jumlah_noseri" =>  $produk[$itemIndex[$i]]['jumlah'],
                            "satuan" => 'Unit',
                            "noseri" => array('-')
                        ];
                        $produk[$itemIndex[$i]]["detail"][] = $newDetail;
                    }
                }
            }
            $items = array_merge($items, $produk);
        }
        //dd($items);

        $p = Pesanan::find($request->dataform['pesanan_id']);
        if ($p->Ekatalog) {
            $paket = $p->Ekatalog->no_paket;
            $ket = $p->Ekatalog->instansi;
        } else {
            $paket = 'OFFLINE';
            $ket = '';
        }
        $isi = array(
            "pesanan_id" => $request->dataform['pesanan_id'],
            "customer" => $request->dataform['nama_customer'],
            "alamat_customer" => $request->dataform['alamat_customer'],
            "tujuan_kirim" =>  $request->dataform['perusahaan_pengiriman'],
            "alamat_kirim" => $request->dataform['alamat_pengiriman'],
            "nosj" => $request->dataform['jenis_sj'] . $request->dataform['no_invoice'],
            "tgl_sj" => $request->dataform['tgl_kirim'],
            "no_po" => $request->dataform['no_po'],
            "so" => $request->dataform['so'],
            "tgl_po" => $request->dataform['tgl_po'],
            "ekspedisi" => $request->dataform['pengiriman_surat_jalan'] == 'ekspedisi' ? $request->dataform['ekspedisi']['nama'] : $request->dataform['nama_pengirim'],
            "keterangan_pengiriman" => $request->dataform['keterangan_pengiriman'],
            "up" => (isset($request->dataform['nama_pic']) ? $request->dataform['nama_pic'] : ""
            ) . (isset($request->dataform['telp_pic']) ? (isset($request->dataform['nama_pic']) ? " - telp. " : "telp. "
            ) . $request->dataform['telp_pic'] : ""
            ),
            "ket" => $ket,
            "paket" => $paket,
            "ekspedisi_terusan" => isset($request->dataform['ekspedisi_terusan']) == true ? $request->dataform['ekspedisi_terusan'] : "",
            "dimensi" => isset($request->dataform['dimensi']) == true ? $request->dataform['dimensi'] : "",
            "item" => $items
        );

        $data = json_encode($isi);

        //    dd($isi);

        $save =  LogistikDraft::create([
            'pesanan_id' => $request->dataform['pesanan_id'],
            'sj' => $request->dataform['jenis_sj'] . $request->dataform['no_invoice'],
            'isi' => $data

        ]);

        if ($save) {
            return response()->json(['messages' => 'berhasil', 'id' => $save->id]);
        } else {
            return response()->json(['messages' => 'gagal']);
        }
    }

    public function get_data_detail_item($id)
    {
        $data_prd = DetailPesananProduk::with(['GudangBarangJadi.Produk', 'DetailPesanan'])->whereHas('DetailPesanan', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->whereNotIn('gudang_barang_jadi_id', [190, 149, 139])->get();
        $data_part = DetailPesananPart::with(['Sparepart'])->where('pesanan_id', $id)->get();
        $pesanan = Pesanan::find($id);
        if (count($data_part) > 0) {
            foreach ($data_part as $key => $d) {
                $part[$key] = array(
                    'id' => $d->id,
                    'kode' => $d->Sparepart->kode,
                    'nama' => $d->Sparepart->nama,
                    'jumlah' => $d->jumlah
                );
            }
        } else {
            $part = array();
        }
        if (count($data_prd) > 0) {
            foreach ($data_prd as $key => $d) {
                if ($d->GudangBarangJadi->id == 380) {
                    $v = 'BLUETOOTH';
                } else {
                    $v = '';
                }
                $prd[$key] = array(
                    'id' => $d->id,
                    'penjualan_produk_id' =>  $d->DetailPesanan->PenjualanProduk->id,
                    'detail_pesanan_id' => $d->detail_pesanan_id,
                    'nama_alias' => $d->DetailPesanan->PenjualanProduk->nama_alias != NULL ? $d->DetailPesanan->PenjualanProduk->nama_alias . ' ' . $v : $d->GudangBarangJadi->Produk->nama,
                    'nama' => $d->GudangBarangJadi->Produk->nama . ' ' . $d->GudangBarangJadi->nama,
                    'jumlah' => $d->DetailPesanan->jumlah
                );
            }
        } else {
            $prd = array();
        }

        if ($pesanan->Ekatalog) {
            $provinsi = array();
            $jenis_pesanan = '';

            if ($pesanan->Ekatalog->provinsi_id != NULL) {
                $instansi = array(
                    'id' => $pesanan->Ekatalog->provinsi_id,
                    'nama' => $pesanan->Ekatalog->Provinsi->nama
                );
                $provinsi['instansi'] = $instansi;
            }

            if ($pesanan->Ekatalog->Customer->id_provinsi != NULL) {
                $dsb = array(
                    'id' => $pesanan->Ekatalog->Customer->id_provinsi,
                    'nama' => $pesanan->Ekatalog->Customer->Provinsi->nama,
                    'customer' => $pesanan->Ekatalog->Customer->nama
                );
                $provinsi['dsb'] = $dsb;
            }
            $dsb_nama =  array(
                'nama' => $pesanan->Ekatalog->Customer->nama,
                'alamat' => $pesanan->Ekatalog->Customer->alamat
            );

            if ($pesanan->tujuan_kirim != NULL) {
                $tujuan_kirim = $pesanan->tujuan_kirim;
                $alamat_kirim = $pesanan->alamat_kirim;
            } else {
                $tujuan_kirim =  $pesanan->Ekatalog->instansi;
                $alamat_kirim =  $pesanan->Ekatalog->alamat;
            }
            $jenis_pesanan = 'ekatalog';
        } elseif ($pesanan->Spa) {
            $provinsi =  array(
                'id' => $pesanan->Spa->Customer->id_provinsi,
                'nama' => $pesanan->Spa->Customer->Provinsi->nama
            );
            $dsb_nama =  array(
                'nama' => $pesanan->Spa->Customer->nama,
                'alamat' => $pesanan->Spa->Customer->alamat
            );

            if ($pesanan->tujuan_kirim != NULL) {
                $tujuan_kirim = $pesanan->tujuan_kirim;
                $alamat_kirim = $pesanan->alamat_kirim;
            } else {
                $tujuan_kirim =   $pesanan->Spa->Customer->nama;
                $alamat_kirim =    $pesanan->Spa->Customer->alamat;
            }

            $jenis_pesanan = 'spa';
        } else {
            $provinsi =  array(
                'id' => $pesanan->Spb->Customer->id_provinsi,
                'nama' => $pesanan->Spb->Customer->Provinsi->nama,
            );
            $dsb_nama =  array(
                'nama' => $pesanan->Spb->Customer->nama,
                'alamat' => $pesanan->Spb->Customer->alamat
            );

            if ($pesanan->tujuan_kirim != NULL) {
                $tujuan_kirim = $pesanan->tujuan_kirim;
                $alamat_kirim = $pesanan->alamat_kirim;
            } else {
                $tujuan_kirim =   $pesanan->Spb->Customer->nama;
                $alamat_kirim =    $pesanan->Spb->Customer->alamat;
            }


            $jenis_pesanan = 'spb';
        }


        if ($pesanan->ekspedisi_id != NULL) {
            $ekspedisi =  array(
                'id' => $pesanan->ekspedisi_id,
                'nama' => $pesanan->Ekspedisi->nama
            );
        } else {
            $ekspedisi = null;
        }

        $data = array(
            'header' => array(
                'jenis_pesanan' => $jenis_pesanan, // 'ekatalog', 'spa', 'spb'
                'pesanan_id' =>   $pesanan->id,
                'so' =>   $pesanan->so,
                'no_po' =>   $pesanan->no_po,
                'tgl_po' =>   $pesanan->tgl_po,
                'provinsi' =>   $provinsi,
                'ekspedisi' => $ekspedisi,
                'customer' => $dsb_nama,
                'cek_alamat' => $pesanan->tujuan_kirim != NULL ? 1 : 0,
                'perusahaan_pengiriman' => $tujuan_kirim,
                'alamat_pengiriman' => $alamat_kirim,
            ),
            'item' => array(
                'produk' => $prd,
                'part' => $part
            )
        );


        return response()->json($data);
    }

    public function peti_reworks_show()
    {
        $data = DB::select('SELECT k.nama, pr.no_urut, MAX(pr.updated_at) as updates, SUM(subquery.count_id) AS total_count, pr.created_at ,pr.packer,pr.updated_at
        FROM peti_rw pr
        JOIN (
            SELECT no_urut, COUNT(DISTINCT updated_at) AS count_id
            FROM peti_rw
            GROUP BY no_urut, updated_at
        ) AS subquery ON pr.no_urut = subquery.no_urut
        left join users on users.id = pr.packer
        left join erp_kesehatan.karyawans as k on users.karyawan_id = k.id
        GROUP BY pr.no_urut');

        if (count($data) <= 0) {
            $obj = array();
        } else {
            foreach($data as $d){
                $obj[] = array(
                    'id' => $d->no_urut,
                    'no_urut' => $d->no_urut,
                    'tgl_buat' => $d->created_at,
                    'tgl_ubah' => $d->total_count > 3 ? $d->updates : NULL ,
                    'ket' => $d->total_count > 3 ? true : false,
                    'packer' => $d->nama,
                );
            }
        }

        return response()->json($obj);
    }
    public function peti_reworks_detail($urut)
    {
        $data = PetiRw::where('no_urut',$urut)->get();

        if ($data->isempty()) {
            $obj = array();
        } else {
            foreach($data as $d){
                $obj[] = array(
                    'id' => $d->no_urut,
                    'noseri' => $d->noseri,
                );
            }
        }
        return $obj;
    }
    public function reworks_show()
    {
        $data = JadwalPerakitanRw::addSelect([
            'cpeti' => function ($q) {
                $q->selectRaw('coalesce(count(peti_rw.id), 0)')
                    ->from('peti_rw')
                    ->whereColumn('peti_rw.jadwal_perakitan_rw_id', 'jadwal_perakitan_rw.urutan');

            },
            'csiap' => function ($q) {
                $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                    ->from('seri_detail_rw')
                    ->whereColumn('seri_detail_rw.urutan', 'jadwal_perakitan_rw.urutan');
            },
        ])
            ->where('state', 18)
            ->where('status_tf', 16)
            ->groupBy('urutan')->get();

        if ($data->isempty()) {
            $obj = array();
        } else {
            foreach ($data as $d) {
                switch ($d->status_tf) {
                    case "11":
                        $status =  "Belum Dikirim";
                        break;
                    case "16":
                        $status = "Proses";
                        break;
                    default:
                        $status = "Error";
                }
                $y =  $d->csiap - $d->cpeti ;
                if ($y % 3 !== 0) {
                    $remainder = $y % 3;
                    $y += (3 - $remainder);
                }

                $obj[] = array(
                    'id' => $d->urutan,
                    'urutan' => 'PRD-'.$d->urutan,
                     'sudah' => $d->cpeti,
                    'belum' =>$y,
                    'nama' => $d->ProdukRw->nama,
                );
            }
        }

        return response()->json($obj);
    }

    public function peti_reworks_store(Request $request,$urutan)
    {
        DB::beginTransaction();
        try {
            //code...
            $obj =  json_decode(json_encode($request->all()), FALSE);
        $seriValues = collect($obj->noseri)->pluck('seri')->unique()->values()->all();

        $max = PetiRw::whereYear('created_at', (Carbon::now()->format('Y')))->max('no_urut');
        $urut = $max+1;
        $cekSeri = SeriDetailRw::whereIn('noseri',$seriValues)->get();
        $cekPeti = PetiRw::whereIn('noseri',$seriValues)->count();

        if(count($seriValues) == count($cekSeri)){
            if($cekPeti > 0){
                $getUsed = PetiRw::whereIn('noseri',$seriValues)->pluck('noseri')->toArray();
                DB::rollBack();
                return response()->json([
                    'message' =>  'Noseri Sudah Terdaftar',
                    'values' => $getUsed,
                ], 500);
            }else{
                foreach($seriValues as $n){
                    $id = NoseriBarangJadi::where('noseri',$n)->first();
                    PetiRw::create([
                        'no_urut' => $urut,
                        'noseri_id' => $id->id,
                        'noseri' => $n,
                        'packer' => auth()->user()->id,
                        'jadwal_perakitan_rw_id' => $urutan
                    ]);
                }
                DB::commit();
                return response()->json([
                    'message' =>  'Berhasil Di tambahkan',
                    'no_urut' => $urut,
                    'values' => [],
                ], 200);
            }
           }else{

            $getNotFound = array_diff($seriValues, $cekSeri->pluck('noseri')->toArray());
            DB::rollBack();
            return response()->json([
                    'message' =>  'No Seri Tidak Terdaftar',
                    'values' => array_values($getNotFound)
                ], 500);
        }
        } catch (\Throwable $th) {
            $getNotFound = array_diff($seriValues, $cekSeri->pluck('noseri')->toArray());
            DB::rollBack();
            return response()->json([
                'message' =>  'Transaksi Gagal',
                'error' => $th->getMessage(),
                'values' => array_values($seriValues)
            ], 500);
        }
    }
    public function peti_reworks_update(Request $request,$urut)
    {
        // $obj =  json_decode(json_encode($request->all()), FALSE);
        // $seriValues = collect($obj->noseri)->pluck('seri')->unique()->values()->all();
        // $data = PetiRw::where('no_urut',$urut)->pluck('noseri')->toArray();
        // $newId = array_values(array_diff($seriValues, $data));
        // $currentId = array_values(array_diff($data, $seriValues));
        // dd($currentId);
         DB::beginTransaction();
        try {
            //code...
            $obj =  json_decode(json_encode($request->all()), FALSE);
        $seriValues = collect($obj->noseri)->pluck('seri')->unique()->values()->all();
        $data = PetiRw::where('no_urut',$urut)->pluck('noseri')->toArray();
        $newId = array_values(array_diff($seriValues, $data));

        $currentId = array_values(array_diff($data, $seriValues));
        // if(count($currentId) > 0){
        //     $ids = PetiRw::where('noseri',$currentId[0])->first();
        // }


        if($newId){
            $cekSeri = SeriDetailRw::whereIn('noseri',$newId)->get();
            $cekPeti = PetiRw::whereIn('noseri',$newId)->get();
            if(count($cekSeri) == count($newId)){
            if(count($cekPeti) > 0){
                DB::rollBack();
                return response()->json([
                    'message' => 'No Seri Sudah Digunakan',
                    'values' => $cekPeti->pluck('noseri')->toArray()
                ], 500);
            }else{
                // PetiRw::whereIn('noseri',$currentId)->delete();
                for ($j = 0; $j < count($newId); $j++) {

                    $nbj = NoseriBarangJadi::where('noseri',$currentId[$j])->first();
                    $nbj_new = NoseriBarangJadi::where('noseri',$newId[$j])->first();

                    $npeti = PetiRw::where('noseri_id',$nbj->id)->first();
                    $npeti->noseri = $nbj_new->noseri;
                    $npeti->noseri_id = $nbj_new->id;
                    $npeti->save();

                    }

                // foreach($newId as $n){
                //     $id = NoseriBarangJadi::where('noseri',$n)->first();

                //     PetiRw::create([
                //         'no_urut'=> $urut,
                //         'noseri_id'=> $id->id,
                //         'noseri'=> $n,
                //         'packer' => auth()->user()->id,
                //         'jadwal_perakitan_rw_id' => $ids->jadwal_perakitan_rw_id
                //     ]);
                // }
                DB::commit();
                return response()->json([
                    'message' =>  'Berhasil Di Ubah',
                    'values' => [],
                    'no_urut' => $urut
                ], 200);
            }
            }else{
                $getNotFound = array_diff($newId, $cekSeri->pluck('noseri')->toArray());
               DB::rollBack();
                    return response()->json([
                            'message' => 'No Seri Tidak Terdaftar',
                            'values' => array_values($getNotFound)
                        ], 500);
            }
        }else{
            DB::rollBack();
            return response()->json([
                'message' =>  'No Seri Tidak Ada Perubahan',
                'values' => []
            ], 500);
        }
        } catch (\Throwable $th) {
           // throw $th;
                 DB::rollBack();
                return response()->json([
                    'message' =>  $th->getMessage(),
                    'values' => []
                ], 500);
        }
            //code...


        // $max = PetiRw::whereYear('created_at', (Carbon::now()->format('Y')))->max('no_urut');

        // $cekPeti = PetiRw::whereIn('noseri',$seriValues)->count();

        // if(count($seriValues) == count($cekSeri)){
        //     if($cekPeti > 0){
        //         $getUsed = PetiRw::whereIn('noseri',$seriValues)->pluck('noseri')->toArray();
        //         // DB::rollBack();
        //         return response()->json([
        //             'message' =>  'Noseri Pernah Dimasukkan',
        //             'values' => $getUsed,
        //         ], 500);
        //     }else{
        //         // foreach($seriValues as $n){
        //         //     $id = NoseriBarangJadi::where('noseri',$n)->first();

        //         //     PetiRw::create([
        //         //         'no_urut' => $max+1,
        //         //         'noseri_id' => $id->id,
        //         //         'noseri' => $n,
        //         //         'packer' => 1,
        //         //     ]);
        //         // }
        //         // DB::commit();
        //         return response()->json([
        //             'message' =>  'Berhasil Di tambahkan',
        //             'values' => [],
        //         ], 200);
        //     }
        //    }else{

        //     $getNotFound = array_diff($seriValues, $cekSeri->pluck('noseri')->toArray());
        //     // DB::rollBack();
        //     return response()->json([
        //             'message' =>  'No Seri Tidak Terdaftar',
        //             'values' => array_values($getNotFound)
        //         ], 500);
        // }
        // // } catch (\Throwable $th) {
        //     $getNotFound = array_diff($seriValues, $cekSeri->pluck('noseri')->toArray());
        //     // DB::rollBack();
        //     return response()->json([
        //         'message' =>  'Transaksi Gagal',
        //         'values' => array_values($seriValues)
        //     ], 500);
        // // }
    }

    //MANAGER
    public function manager_logistik_show()
    {
        return view('manager.logistik.so.show');
    }

    function getYear()
    {
        return  Carbon::now()->format('Y');
    }

    public function view_peti($id) {
        // set paper A5 landscape
        $loadView = $this->peti_reworks_detail($id);
        return view('page.produksi.printreworks.viewpeti', compact('loadView'));
    }

    public function cetak_peti($id) {
        $loadView = $this->peti_reworks_detail($id);
        $pdf = PDF::loadView('page.produksi.printreworks.cetakpeti', compact('loadView'))->setPaper('a5', 'landscape');
        return $pdf->stream('');
    }
}
