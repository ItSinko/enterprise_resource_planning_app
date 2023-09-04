<?php

namespace App\Http\Controllers\kesehatan;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\kesehatan\Detail_obat;
use App\Models\kesehatan\Karyawan;
use App\Models\kesehatan\Karyawan_sakit;
use App\Models\kesehatan\Obat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    //
    public function dashboard()
    {
        $karyawan = Divisi::all();
        return view('page.kesehatan.dashboard');
    }
    public function karyawan_show()
    {
        $karyawan = Divisi::all();
        return view('page.karyawan.karyawan', ['karyawan' => $karyawan]);
    }
    public function karyawan_tambah()
    {
        $divisi = Divisi::all();
        return view('page.karyawan.karyawan_tambah', ['divisi' => $divisi]);
    }

    public function karyawan_data()
    {
        $data = Karyawan::with(['Divisi'])->orderBy('nama', 'ASC');
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('x', function ($data) {
                if ($data->Divisi) {
                    return $data->Divisi->nama;
                }
            })
            ->addColumn('jabatans', function ($data) {
                if ($data->jabatan) {
                    return ucfirst($data->jabatan);
                }
            })
            ->addColumn('umur', function ($data) {
                if ($data->tgllahir) {
                    $tgl  = $data->tgllahir;
                    $age = Carbon::parse($tgl)->diff(Carbon::now())->y;
                    return $age . " Thn";
                }
            })
            ->editColumn('tgl_kerja', function ($data) {
                if ($data->tgl_kerja) {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_kerja)->format('d-m-Y');
                }
            })
            ->addColumn('button', function ($data) {
                $btn = ' <button type="button" id="edit" class="btn btn-sm btn-warning edit_karyawan" data-karyawan ="' . $data->is_aktif . '"  ><i class="fas fa-edit"></i> Ubah</button>
                <button type="button" id="delete" class="btn btn-sm btn-danger" data-id="' . $data->id . '"><i class="fas fa-trash"></i> Hapus</button>';
                return $btn;
            })
            ->rawColumns(['button'])
            ->setRowClass(function ($data) {
                if ($data->is_aktif == 0) {
                    return 'text-danger font-weight-bold line-through';
                }
            })
            ->make(true);
    }

    public function karyawan_cekdata($nama)
    {
        $data = Karyawan::where('nama', $nama)->get();
        echo json_encode($data);
    }

    public function get_karyawan_all(Request $r)
    {
        $data = Karyawan::where('nama', 'LIKE', '%' . $r->input('term', '') . '%')->with('Divisi')->get();
        return response()->json($data);
    }

    public function karyawan_aksi_tambah(Request $request)
    {
        $this->validate(
            $request,
            [
                'nama' => 'required|unique:kesehatan.karyawans',
                'divisi_id' => 'required',
                'tgllahir' => 'required',
                'tgl_kerja' => 'required',
                'jenis' => 'required',
                'jabatan' => 'required',
            ],
            [
                'nama.required' => 'Nama harus di isi',
                'divisi_id.required' => 'Divisi harus di isi',
                'tgllahir.required' => 'Tgl Lahir harus di isi',
                'tgl_kerja.required' => 'Tgl Kerja harus di isi',
                'jenis.required' => 'Jenis Kelamin  harus di isi',
                'jabatan.required' => 'Jabatan harus di isi',
            ]
        );
        $karyawan = Karyawan::create([
            'nama' => $request->nama,
            'divisi_id' => $request->divisi_id,
            'tgllahir' => $request->tgllahir,
            'tgl_kerja' => $request->tgl_kerja,
            'kelamin' => $request->jenis,
            'jabatan' => $request->jabatan,
        ]);
        if ($karyawan) {
            return redirect()->back()->with('success', "Berhasil menambahkan data");
        } else {
            return redirect()->back()->with('error', "Gagal menambahkan data");
        }
    }

    public function karyawan_aksi_ubah(Request $request)
    {
        $id = $request->id;
        $karyawan = Karyawan::find($id);
        $karyawan->tgllahir = $request->tgllahir;
        $divisi_id = $request->divisi;
        $karyawan->jabatan = $request->jabatan;
        $karyawan->is_aktif = $request->status_karyawan;
        $karyawan->kelamin = $request->jenis;
        $karyawan->pemeriksa_rapid = $request->pemeriksa_rapid;
        $karyawan->save();
        if ($karyawan) {
            return redirect()->back()->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }

    public function karyawan_aksi_hapus(Request $request)
    {
        $b = Karyawan::find($request->id);
        $delete = $b->delete();
        if ($delete) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di hapus']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }
}
