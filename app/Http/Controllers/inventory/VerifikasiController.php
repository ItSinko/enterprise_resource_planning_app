<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

use App\Models\inventory\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\inventory\AlatSN;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class VerifikasiController extends Controller
{
    function index($id)
    {
        try{

            $data =
            DB::table(DB::raw('erp_kalibrasi.alatuji_sn al'))
            ->select(
                DB::raw('concat(a.kd_alatuji,"-",al.no_urut) as kode_alat'),
                'al.serial_number', 'al.status_pinjam_id', 'a.desk_alatuji', 'v.tgl_perawatan', 'a.gbr_alatuji'
            )
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'),'a.id_alatuji', '=', 'al.alatuji_id')
            ->leftJoin(DB::raw('erp_kalibrasi.verifikasi v'), 'v.serial_number_id', '=', 'al.alatuji_id')
            ->where('al.id_serial_number', '=', $id)
            ->first();

            if($data->status_pinjam_id == 16){
                $data->status_pinjam_id =
                '<span class="badge bc-success">
                    <span class="text-success">Tersedia</span>
                </span>';
            }elseif($data->status_pinjam_id == 15){
                $data->status_pinjam_id =
                '<span class="badge bc-primary">
                <span class="text-primary">Sedang Di Pinjam</span>
                </span>';
            }elseif($data->status_pinjam_id == 17){
                $data->status_pinjam_id =
                '<span class="badge bc-warning">
                <span class="text-warning">Pengajuan</span>
                </span>';
            }elseif($data->status_pinjam_id == 14){
                $data->status_pinjam_id =
                '<span class="badge bc-warning">
                <span class="text-warning">Eksternal</span>
                </span>';
            }else{
                $data->status_pinjam_id =
                '<span class="badge bc-danger">
                <span class="text-danger">Not Ok</span>
                </span>';
            }

            $tgl = DB::table(DB::raw('erp_kalibrasi.verifikasi v'))
            ->select('tgl_perawatan')
            ->where('v.serial_number_id', $id)
            ->orderByDesc('v.tgl_perawatan')
            ->first();

            if($tgl == null){
                $tgl = 'Alat belum di lakukan Verifikasi';
                $data->tgl_perawatan = $tgl;
            }else{
                $data->tgl_perawatan = $tgl->tgl_perawatan;
            }

            $user = DB::table(DB::raw('erp_spa.users'))->select('*')->get();

            return view('page.lab.verifikasi', [
                'data' => $data,
                'id' => $id,
                'user' => $user,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function store_verifikasi(Request $request)
    {
        // try {

            $request->validate([
                'operator' => 'required',
                'tgl_verifikasi' => 'required',
                'pelaksanaan_kendali' => 'required',
                'cekFungsi' => 'required',
                'cekFisik' => 'required',
                'keputusan' => 'required',
                'keterangan' => 'required',
            ],[
                'required' => 'kolom :attribute harus di isi'
            ]);

            $data = DB::table(DB::raw('erp_kalibrasi.alatuji_sn al'))->select(
                'al.serial_number', 'a.nm_alatuji'
            )
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', '=', 'al.alatuji_id')
            ->where('al.id_serial_number', $request->serial_number)
            ->first();
            $pj = DB::table('erp_spa.users')->where('id', $request->operator)->first();

            if($request->cekFisik == 10 or $request->cekFungsi == 10)
            {
                $request->validate([
                    'tindak_lanjut' => 'required'
                ],[
                    'required' => 'jika not ok, kolom :attribute harus di isi'
                ]);
            }

            $jadwal_perawatan = Carbon::parse($request->tgl_verifikasi)->addMonths(3)->format('Y-m-d');

            Verifikasi::create([
                'serial_number_id'  => $request->serial_number,
                'pengendalian'      => $request->pelaksanaan_kendali,
                'keputusan'         => $request->keputusan,
                'hasil_fisik'       => $request->cekFisik,
                'hasil_fungsi'      => $request->cekFungsi,
                'tgl_perawatan'     => $request->tgl_verifikasi,
                'keterangan'        => $request->keterangan,
                'tindak_lanjut'     => $request->tindak_lanjut,
                'jadwal_perawatan'  => $jadwal_perawatan,
                'created_by'        => auth()->user()->id,
            ]);

            // update tabel alat uji
            if($request->cekFungsi == '10' OR $request->cekFisik == '10'){
                AlatSN::find($request->serial_number)
                ->update([
                    'status_pinjam_id' => '10',
                    'kondisi_id' => '10',
                ]);
            }
            if($request->cekFungsi == '9' AND $request->cekFisik == '9'){
                AlatSN::find($request->serial_number)
                ->update([
                    'kondisi_id' => '9',
                    'status_pinjam_id' => '16'
                ]);
            }

            // user log
            $obj = [
                'alat_uji' => $data->nm_alatuji,
                'serial_number' => $data->serial_number,
                'tgl_perawatan' => $request->tgl_perawatan,
                'pj_dilakukan_oleh' => $pj->nama,
            ];

            DB::table('erp_spa.tbl_log')->insert([
                'tipe' => 'QC',
                'subjek' => 'Verifikasi alat uji - '.$data->nm_alatuji,
                'response' => json_encode($obj),
                'user_id' => auth()->user()->id,
            ]);

            return redirect()->route('alatuji.detail', ['id' => $request->serial_number])->with(['success' => '1','verifSuccess' => '2']);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'error' => true,
        //         'msg' => $e->getMessage()
        //     ]);
        // }
    }

    function verifikasi_hist($id)
    {
        try {

            $data =
            DB::table(DB::raw('erp_kalibrasi.verifikasi v'))
            ->select(
                'v.tgl_perawatan', 'v.pengendalian', 'v.hasil_fisik', 'v.hasil_fungsi', 'v.keputusan', 'v.keterangan', 'v.tindak_lanjut'
            )
            ->where('v.serial_number_id', '=', $id)
            ->get();

            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('keterangan', function($d){
                return $d->keterangan == null ? '-' : $d->keterangan;
            })
            ->editColumn('tindak_lanjut', function($d){
                return $d->tindak_lanjut == null ? '-' : $d->tindak_lanjut;
            })
            ->editColumn('hasil_fisik', function($d){
                return $d->hasil_fisik == 9 ?
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                </div>'
                :
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                <i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i>
                </div>'
                ;
            })
            ->editColumn('hasil_fungsi', function($d){
                return $d->hasil_fungsi == 9 ?
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                </div>'
                :
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                <i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i>
                </div>'
                ;
            })
            ->rawColumns(['hasil_fisik', 'hasil_fungsi'])
            ->make(true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
