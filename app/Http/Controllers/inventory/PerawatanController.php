<?php

namespace App\Http\Controllers\inventory;
use App\Http\Controllers\Controller;
use App\Models\inventory\Perawatan;
use Illuminate\Support\Facades\DB;
use App\Models\inventory\AlatSN;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PerawatanController extends Controller
{
    function index($id)
    {
        try {

            $data =
            DB::table(DB::raw('erp_kalibrasi.alatuji_sn al'))
            ->select(
                DB::raw('concat(a.kd_alatuji,"-",al.no_urut) as kode_alat'),
                'al.serial_number', 'al.status_pinjam_id', 'a.nm_alatuji', 'v.tgl_perawatan', 'a.gbr_alatuji'
            )
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'),'a.id_alatuji', '=', 'al.alatuji_id')
            ->leftJoin(DB::raw('erp_kalibrasi.perawatan v'), 'v.serial_number_id', '=', 'al.alatuji_id')
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

            $tgl = DB::table(DB::raw('erp_kalibrasi.perawatan v'))
            ->select('tgl_perawatan')
            ->where('v.serial_number_id', $id)
            ->orderByDesc('v.tgl_perawatan')
            ->first();

            if($tgl == null){
                $tgl = 'Alat belum di lakukan Perawatan';
                $data->tgl_perawatan = $tgl;
            }else{
                $data->tgl_perawatan = $tgl->tgl_perawatan;
            }

            $user = DB::table(DB::raw('erp_spa.users'))->select('*')->get();

            return view('page.lab.perawatan', [
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

    function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required',
            'operator' => 'required',
            'tgl_perawatan' => 'required',
            'cek_fungsi_txt' => 'required',
            'cek_kelengkapan' => 'required',
            'cekFungsi' => 'required',
            'cekFisik' => 'required',
            'tindak_lanjut' => 'required',
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

        $jadwal_perawatan = Carbon::parse($request->tgl_verifikasi)->addMonths(3)->format('Y-m-d');

        Perawatan::create([
            'serial_number_id'  => $request->serial_number,
            'kelengkapan'      => $request->cek_kelengkapan,
            'fungsi'            => $request->cek_fungsi_txt,
            'hasil_fisik'       => $request->cekFisik,
            'hasil_fungsi'      => $request->cekFungsi,
            'tindak_lanjut'     => $request->tindak_lanjut,
            'keterangan'        => $request->keterangan,
            'tgl_perawatan'     => $request->tgl_perawatan,
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
            'subjek' => 'Perawatan alat uji - '.$data->nm_alatuji,
            'response' => json_encode($obj),
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('alatuji.detail', ['id' => $request->serial_number])->with(['success' => '2','perawatanSuccess' => '1']);

    }
}
