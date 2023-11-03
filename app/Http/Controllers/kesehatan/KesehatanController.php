<?php

namespace App\Http\Controllers\kesehatan;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\kesehatan\Berat_karyawan;
use App\Models\kesehatan\Detail_obat;
use App\Models\kesehatan\Gcu_karyawan;
use App\Models\kesehatan\Karyawan;
use App\Models\kesehatan\Karyawan_masuk;
use App\Models\kesehatan\Karyawan_sakit;
use App\Models\kesehatan\Kesehatan_awal;
use App\Models\kesehatan\Kesehatan_mingguan_rapid;
use App\Models\kesehatan\Kesehatan_mingguan_tensi;
use App\Models\kesehatan\Kesehatan_tahunan;
use App\Models\kesehatan\Obat;
use App\Models\kesehatan\Riwayat_penyakit;
use App\Models\kesehatan\Riwayat_stok_obat;
use App\Models\kesehatan\Vaksin_karyawan;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KesehatanController extends Controller
{
    public function get_data_karyawan_pemeriksa()
    {
        return  Karyawan::orderby('nama')->where('is_aktif', 1)->where('divisi_id', '28')->get();
    }

    public function get_data_karyawan_aktif()
    {
        return  Karyawan::orderby('nama')->where('is_aktif', 1)->get();
    }

    public function kesehatan()
    {
        $karyawan = Karyawan::orderBy('nama', 'ASC')
            ->has('Kesehatan_awal')
            ->get();
        return view('page.kesehatan.kesehatan', ['karyawan' => $karyawan]);
    }

    public function klinik_diagnosa_detail()
    {
        return view('page.kesehatan.diagnosa_detail');
    }

    public function klinik_obat_detail()
    {
        return view('page.kesehatan.obat_detail');
    }

    public function klinik_sakit_detail()
    {
        return view('page.kesehatan.sakit_detail');
    }

    public function kesehatan_data()
    {
        $data = Kesehatan_awal::with(['Karyawan.Vaksin_karyawan', 'Karyawan.Berat_karyawan', 'Karyawan.Divisi'])->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('divisi', function ($data) {
                return $data->Karyawan->Divisi->nama;
            })
            ->addColumn('nama', function ($data) {
                return $data->Karyawan->nama;
            })
            ->addColumn('berat_kg', function ($data) {
                if ($data->Karyawan->Berat_karyawan->last()) {
                    return $data->Karyawan->Berat_karyawan->last()->berat . ' Kg';
                } else {
                    return '-';
                }
            })
            ->addColumn('tinggi_cm', function ($data) {
                return $data->tinggi . ' Cm';
            })
            ->addColumn('bmi', function ($data) {
                return $data->berat / (($data->tinggi / 100) * ($data->tinggi / 100));
            })
            ->addColumn('suhu_k', function ($data) {
                return $data->suhu . ' °C';
            })
            ->addColumn('sp', function ($data) {
                return $data->spo2 . ' %';
            })
            ->addColumn('pr', function ($data) {
                return $data->pr . ' bpm';
            })
            ->addColumn('umur', function ($data) {
                $tgl  = $data->Karyawan->tgllahir;
                $age = Carbon::parse($tgl)->diff(Carbon::now())->y;
                return $age . " Thn";
            })
            ->addColumn('vaksin_detail', function ($data) {
                if ($data->Karyawan->Vaksin_karyawan->isEmpty()) {
                    $status = '<span class="badge red-text">Belum Vaksin</span>';
                } else {
                    $status = '<span class="badge green-text">Sudah Vaksin</span>';
                }
                // $btn = '' . $status . '<br><div class="inline-flex"><button type="button" id="vaksin_detail" class="btn btn-block btn-primary karyawan-img-small" style="border-radius:50%;" ><i class="fa fa-eye" aria-hidden="true"></i></button></div>';
                return $status;
            })
            ->editColumn('status_mata', function ($data) {
                if ($data->status_mata == "Defisensi") {
                    $status = '<span class="badge red-text">' . $data->status_mata . '</span>';
                } else if ($data->status_mata == "Abnormal") {
                    $status = '<span class="badge yellow-text">' . $data->status_mata . '</span>';
                } else {
                    $status = '<span class="badge green-text">' . $data->status_mata . '</span>';
                }
                // $btn = '' . $status . '<br><div class="inline-flex"><button type="button" id="vaksin_detail" class="btn btn-block btn-primary karyawan-img-small" style="border-radius:50%;" ><i class="fa fa-eye" aria-hidden="true"></i></button></div>';
                return $status;
            })
            ->addColumn('detail', function ($s) {
                $btn = '<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  title="Klik untuk melihat detail Kesehatan">';
                $btn .= '<i class="fa fa-ellipsis-v" aria-hidden="true"></i> </a>';

                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
                $btn .= '<button class="btn btn-block dropdown-item" type="button" id="penyakit" ><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Riwayat Penyakit</span></button>';
                $btn .= '<button class="btn btn-block dropdown-item" type="button" id="vaksin_detail" ><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Riwayat Vaksin</span></button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['berat_kg', 'vaksin_detail', 'detail', 'status_mata'])
            ->make(true);
    }
    public function kesehatan_tambah()
    {
        $karyawan = Karyawan::doesnthave('Kesehatan_awal')->where('is_aktif', 1)->orderBy('nama', 'ASC')->get();
        $pengecek = $this->get_data_karyawan_pemeriksa();
        return view('page.kesehatan.kesehatan_tambah', ['karyawan' => $karyawan, 'pengecek' => $pengecek]);
    }
    public function kesehatan_aksi_tambah(Request $request)
    {
        $this->validate(
            $request,
            [
                'karyawan_id' => 'required|unique:kesehatan.kesehatan_awals',
                'status_vaksin' => 'required',
                'tinggi' => 'required',
                'berat' => 'required',
                'status_mata' => 'required',
                'suhu' => 'required',
                'spo2' => 'required',
                'pr' => 'required',
            ],
            [
                'karyawan_id.required' => 'Karyawan harus di pilih',
                'karyawan_id.unique' => 'Karyawan sudah pernah di input',
                'status_vaksin.required' => 'Status Vaksin harus di pilih',
                'tinggi.required' => 'Tinggi harus di isi',
                'berat.required' => 'Berat harus di isi',
                'status_mata.required' => 'Kategori buta warna harus di isi',
                'suhu.required' => 'Suhu harus di isi',
                'spo2.required' => 'Spo2 buta warna harus di isi',
                'pr.required' => 'Pulse Oximeter buta warna harus di isi',
                'status_mata.required' => 'Kategori buta warna harus di isi'
            ]
        );

        if ($request->hasFile('file_mcu')) {
            $karyawan = Karyawan::find($request->karyawan_id);
            $file = $request->file('file_mcu')->getClientOriginalName();
            $path = $request->file('file_mcu')->move(base_path('\public\file\kesehatan'), $karyawan->nama . '_MCU_' . $file);
            $file_mcu = $karyawan->nama . '_MCU_' . $file;
        } else {
            $file_mcu = NULL;
        }



        if ($request->status_vaksin == 'Sudah') {
            $this->validate(
                $request,
                [
                    'tgl.*' => 'required',
                    'dosis.*' => 'required',
                    'tahap.*' => 'required',
                ],
                [
                    'tgl.required' => 'Tanggal harus di isi',
                    'dosis.required' => 'Dosis pengecekan harus di isi',
                    'tahap.required' => 'Tahap pengecekan harus di isi',
                ]
            );
            for ($i = 0; $i < count($request->date); $i++) {
                $vaksin_karyawan = Vaksin_karyawan::create([
                    'karyawan_id' => $request->karyawan_id,
                    'tgl' => $request->date[$i],
                    'dosis' => $request->dosis[$i],
                    'tahap' => $request->ket[$i],
                ]);
            }
        }

        if ($request->riwayat_penyakit == 'Iya') {
            $this->validate(
                $request,
                [
                    'jenis.*' => 'required',
                    'nama.*' => 'required',
                    'kriteria.*' => 'required',
                ],
                [
                    'jenis.required' => 'Jenis penyakit harus di isi',
                    'nama.required' => 'Nama penyakit harus di isi',
                    'kriteria.required' => 'Kriteria harus di pilih',
                ]
            );
            for ($i = 0; $i < count($request->nama); $i++) {
                $riwayat_penyakit = Riwayat_penyakit::create([
                    'karyawan_id' => $request->karyawan_id,
                    'nama' => $request->nama[$i],
                    'jenis' => $request->jenis[$i],
                    'kriteria' => $request->kriteria[$i],
                    'keterangan' => $request->keterangan[$i],
                ]);
            }
        }

        if ($request->status_tes == 'Iya') {
            $this->validate(
                $request,
                [
                    'tgl_cek.*' => 'required',
                    'pemeriksa_id.*' => 'required',
                    'hasil_covid.*' => 'required',
                    'jenis_tes.*' => 'required',
                ],
                [
                    'tgl_cek.required' => 'Tanggal harus di isi',
                    'pemeriksa_id.required' => 'Pemeriksa harus di isi',
                    'hasil_covid.required' => 'Pemeriksa harus di isi',
                    'jenis_tes.required' => 'Pemeriksa harus di isi',
                ]
            );
            for ($i = 0; $i < count($request->tgl_cek); $i++) {
                $kesehatan_mingguan_rapid = Kesehatan_mingguan_rapid::create([
                    'karyawan_id' => $request->karyawan_id,
                    'pemeriksa_id' => $request->pemeriksa_id[$i],
                    'tgl_cek' => $request->tgl_cek[$i],
                    'hasil' => $request->hasil_covid[$i],
                    'jenis' => $request->jenis_tes[$i],
                    'keterangan' => $request->keterangan[$i],
                ]);
            }
        }
        $kesehatan_awal = Kesehatan_awal::create([
            'karyawan_id' => $request->karyawan_id,
            'vaksin' => $request->status_vaksin,
            'ket_vaksin' => $request->ket_vaksin,
            'tinggi' => $request->tinggi,
            'status_mata' => $request->status_mata,
            'mata_kiri' => $request->mata_kiri,
            'mata_kanan' => $request->mata_kanan,
            'suhu' => $request->suhu,
            'spo2' => $request->spo2,
            'pr' => $request->pr,
            'sistolik' => $request->sistolik,
            'diastolik' => $request->diastolik,
            'file_mcu' => $file_mcu,
        ]);

        for ($i = 0; $i < count($request->tgl_cek); $i++) {
            $berat_karyawan = Berat_karyawan::create([
                'karyawan_id' => $request->karyawan_id,
                'berat' => $request->berat[$i],
                'lemak' => $request->lemak[$i],
                'kandungan_air' => $request->kandungan_air[$i],
                'otot' => $request->otot[$i],
                'tulang' => $request->tulang[$i],
                'kalori' => $request->kalori[$i],
            ]);
        }

        if ($kesehatan_awal && $berat_karyawan) {
            return redirect()->back()->with('success', "Berhasil menambahkan data");
        } else {
            return redirect()->back()->with('error', "Gagal menambahkan data");
        }
    }

    public function kesehatan_vaksin($karyawan_id)
    {
        $data = Vaksin_karyawan::where('karyawan_id', $karyawan_id);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($data) {
                return '<button type="button" id="delete" class="btn btn-sm btn-danger" data-id="' . $data->id . '"><i class="fas fa-trash"></i> </button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function kesehatan_riwayat_penyakit($karyawan_id)
    {
        $data = Riwayat_penyakit::where('karyawan_id', $karyawan_id);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('kriteria_penyakit', function ($data) {
                if ($data->kriteria != 1) {
                    return 'Penyakit tidak menular';
                } else {
                    return ' Penyakit menular';
                }
            })
            ->addColumn('aksi', function ($data) {

                return '<button type="button" id="delete" class="btn btn-sm btn-danger" data-id="' . $data->id . '"><i class="fas fa-trash"></i> </button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function kesehatan_detail()
    {
        $karyawan = Karyawan::orderBy('nama', 'ASC')
            ->has('Kesehatan_awal')
            ->get();
        $kesehatan_awal = Kesehatan_awal::all();
        return view('page.kesehatan.kesehatan_detail', ['karyawan' => $karyawan, 'kesehatan_awal' => $kesehatan_awal]);
    }

    public function kesehatan_bulanan_berat_detail_data($karyawan_id)
    {
        $data = Berat_karyawan::where('karyawan_id', $karyawan_id);
        return datatables()->of($data)
            ->addIndexColumn()
            // ->addColumn('tg', function ($data) {
            //     return $data->tgl_cek ? with(new Carbon($data->tgl_cek))->format('F') : '';
            // })
            ->addColumn('z', function ($data) {
                return $data->berat . ' Kg';
            })
            ->addColumn('l', function ($data) {
                return $data->lemak . ' gram';
            })
            ->addColumn('k', function ($data) {
                return $data->kandungan_air . ' %';
            })
            ->addColumn('o', function ($data) {
                return $data->otot . ' Kg';
            })
            ->addColumn('t', function ($data) {
                return $data->tulang . ' Kg';
            })
            ->addColumn('ka', function ($data) {
                return $data->kalori . ' kkal';
            })
            ->addColumn('ti', function ($data) {
                return $data->karyawan->kesehatan_awal->tinggi . ' Cm';
            })

            ->addColumn('bmi', function ($data) {
                return  $data->berat / (($data->karyawan->kesehatan_awal->tinggi / 100) * ($data->karyawan->kesehatan_awal->tinggi / 100));
            })
            ->addColumn('aksi', function ($data) {
                return ' <button type="button" id="delete" class="btn btn-xs btn-danger m-1" data-id="' . $data->id . '"><i class="fas fa-trash"></i> Hapus</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function obat_detail_data_karyawan($karyawan_id)
    {
        $data = Detail_obat::whereHas('karyawan_sakit', function ($q) use ($karyawan_id) {
            $q->where('karyawan_id', $karyawan_id);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_cek', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->Karyawan_sakit->tgl_cek)->format('d-m-Y');
            })
            ->addColumn('diag', function ($data) {
                return $data->Karyawan_sakit->diagnosa;
            })
            ->addColumn('nama_obat', function ($data) {
                return $data->Obat->nama;
            })
            ->addColumn('jumlah_obat', function ($data) {
                return $data->jumlah . ' pcs';
            })
            ->addColumn('aksi', function ($data) {
                return '<i class="fas fa-trash text-danger" id="delete"></i>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function kesehatan_data_detail($karyawan_id)
    {
        $kesehatan_awal = Kesehatan_awal::with('karyawan.divisi')
            ->where('karyawan_id', $karyawan_id)->first();

        if ($kesehatan_awal->Karyawan->Vaksin_karyawan->isEmpty()) {
            $status = 'Belum Vaksin';
        } else {
            $status = 'Sudah Vaksin';
        }

        if ($kesehatan_awal->Karyawan->kelamin == 'L') {
            $jenis = 'Laki laki';
        } else {
            $jenis = 'Perempuan';
        }

        if ($kesehatan_awal->mata_kiri <= 6) {
            $mata_kiri = 'Tidak normal (kiri)';
        } else {
            $mata_kiri = 'Normal (kiri)';
        }

        if ($kesehatan_awal->mata_kanan <= 6) {
            $mata_kanan = 'Tidak normal (kanan)';
        } else {
            $mata_kanan = 'Normal (kanan)';
        }
        $data = array();
        $data['nama'] =  $kesehatan_awal->Karyawan->nama;
        $data['divisi'] =  $kesehatan_awal->Karyawan->Divisi->nama;
        $data['jenis'] =  $jenis;
        $data['tinggi'] =  $kesehatan_awal->tinggi . ' cm';
        $data['status_mata'] =  $kesehatan_awal->status_mata;
        $data['status_vaksin'] =  $status;
        $data['mata_kiri'] =  $mata_kiri;
        $data['mata_kanan'] =  $mata_kanan;
        $data['umur'] =  Carbon::parse($kesehatan_awal->Karyawan->tgllahir)->age . ' Tahun';

        echo json_encode($data);
    }
    public function kesehatan_mingguan()
    {
        $karyawan = Karyawan::orderBy('nama', 'ASC')
            ->has('Kesehatan_awal')
            ->get();
        return view('page.kesehatan.kesehatan_mingguan', ['karyawan' => $karyawan]);
    }
    public function kesehatan_mingguan_tensi_data()
    {
        $data = Kesehatan_mingguan_tensi::with('karyawan')
            ->orderBy('tgl_cek', 'DESC');

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('x', function ($data) {
                return $data->karyawan->divisi->nama;
            })
            ->addColumn('y', function ($data) {
                if ((($data->sistolik) - ($data->diastolik)) < 30) {
                    return $data->karyawan->nama . '<br><span class="badge bg-danger"><i class="fas fa-exclamation"></i> perlu tindakan lanjut</span>';
                } else {
                    return $data->karyawan->nama;
                }
            })
            ->addColumn('hasil', function ($data) {
                if ($data->sistolik < 130) {
                    return '<span class="badge bg-success">Normal</span>';
                } else if ($data->sistolik >= 130 && $data->sistolik <= 139) {
                    return '<span class="badge bg-warning">Pra-Hipertensi</span>';
                } else if ($data->sistolik >= 140 && $data->sistolik <= 159) {
                    return '<span class="badge bg-info">Stadium 1 Hipertensi</span>';
                } else if ($data->sistolik >= 160) {
                    return '<span class="badge bg-danger">Stadium 2 Hipertensi</span>';
                } else {
                    return 'Error';
                }
            })
            ->addColumn('sis', function ($data) {
                return $data->sistolik . ' mmHg';
            })
            ->addColumn('dias', function ($data) {
                return $data->diastolik . ' mmHg';
            })
            ->addColumn('button', function ($data) {
                $btn = '<div class="btn-group">';
                $btn .= '<span><button type="button" id="edit_tensi" class="btn btn-sm btn-warning mr-1"><i class="fas fa-edit"></i> Ubah</button></span>';
                $btn .= '<span><button type="button" id="delete" class="btn btn-sm btn-danger" data-id="' . $data->id . '"><i class="fas fa-trash"></i> Hapus</button></span>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['button', 'hasil', 'y'])
            ->make(true);
    }

    public function kesehatan_mingguan_rapid_data()
    {
        $data = Kesehatan_mingguan_rapid::with('karyawan')
            ->orderBy('tgl_cek', 'DESC');

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('x', function ($data) {
                return $data->karyawan->divisi->nama;
            })
            ->addColumn('z', function ($data) {
                return $data->pemeriksa->nama;
            })
            ->addColumn('button', function ($data) {
                $btn = '<div class="btn-group">';
                if ($data->file == NULL) {
                    $btn .= '<span><button type="button" class="btn btn-sm btn-secondary mr-1" disabled="true"><i class="fas fa-file"></i> Cetak</button></span>';
                } else {
                    $btn .= '<a href="url(assets/public/file/kesehatan_rapid/a.pdf)" target="_break"><button type="button" class="btn btn-sm btn-info mr-1"><i class="fas fa-file"></i> Cetak</button></a>';
                }

                $btn .= '<span><button type="button" id="edit_rapid" class="btn btn-sm btn-warning mr-1"><i class="fas fa-edit"></i> Ubah</button></span>
                        <span><button type="button" id="delete" class="btn btn-sm btn-danger"  data-id="' . $data->id . '" ><i class="fas fa-trash"></i> Hapus</button></span>
                    </div>';

                return $btn;
            })
            ->rawColumns(['button', 'cetak'])
            ->make(true);
    }

    public function kesehatan_mingguan_tambah()
    {
        $pengecek = $this->get_data_karyawan_pemeriksa();
        $karyawan =  $this->get_data_karyawan_aktif();
        $divisi = Divisi::all();
        return view('page.kesehatan.kesehatan_mingguan_tambah', ['divisi' => $divisi, 'pengecek' => $pengecek, 'karyawan' => $karyawan]);
    }
    public function kesehatan_mingguan_tensi_delete($id)
    {
        $b = Kesehatan_mingguan_tensi::find($id);
        $delete = $b->delete();
        if ($delete) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di hapus']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }
    public function kesehatan_mingguan_rapid_delete($id)
    {
        $b = Kesehatan_mingguan_rapid::find($id);
        $delete = $b->delete();
        if ($delete) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di hapus']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }

    public function kesehatan_mingguan_tensi_tambah()
    {
        $pengecek = $this->get_data_karyawan_pemeriksa();
        $karyawan = $this->get_data_karyawan_aktif();
        $divisi = Divisi::all();
        return view('page.kesehatan.kesehatan_mingguan_tensi_tambah', ['divisi' => $divisi, 'pengecek' => $pengecek, 'karyawan' => $karyawan]);
    }
    public function kesehatan_mingguan_rapid_tambah()
    {
        $pengecek = $this->get_data_karyawan_pemeriksa();
        $karyawan = $this->get_data_karyawan_aktif();
        $divisi = Divisi::all();
        return view('page.kesehatan.kesehatan_mingguan_rapid_tambah', ['divisi' => $divisi, 'pengecek' => $pengecek, 'karyawan' => $karyawan]);
    }


    public function kesehatan_mingguan_tensi_aksi_tambah(Request $request)
    {
        $x = $this->validate(
            $request,
            [
                'karyawan_id.*' => 'required ',
                'date.*' => 'required ',
                'sistolik.*' => 'required ',
                'diastolik.*' => 'required ',

            ],
            [
                'karyawan_id.required' => 'Karyawan harus di pilih',
                'date.required' => 'Tanggal harus di pilih',
                'diastolik.required' => 'Hasil Diastolik harus di isi',
                'sistolik.required' => 'Hasil Sistolik harus di isi',
            ]
        );
        for ($i = 0; $i < count($request->karyawan_id); $i++) {
            $kesehatan_mingguan_tensi = Kesehatan_mingguan_tensi::create([
                'karyawan_id' => $request->karyawan_id[$i],
                'tgl_cek' => $request->date[$i],
                'sistolik' => $request->sistolik[$i],
                'diastolik' => $request->diastolik[$i],
                'keterangan' => $request->keterangan[$i]
            ]);
        }
        if ($kesehatan_mingguan_tensi) {
            return redirect()->back()->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }
    public function kesehatan_mingguan_rapid_aksi_tambah(Request $request)
    {
        $x = $this->validate(
            $request,
            [
                'jenis_tes.*' => 'required ',
                'pemeriksa_id.*' => 'required ',
                'date.*' => 'required ',
                'hasil_covid.*' => 'required ',

            ],
            [
                'jenis_tes.required' => 'Jenis Tes harus di pilih',
                'pemeriksa_id.required' => 'Pemeriksa harus di pilih',
                'date.required' => 'Tanggal harus di pilih',
                'hasil_covid.required' => 'Hasil Covid harus di pilih',
            ]
        );
        for ($i = 0; $i < count($request->karyawan_id); $i++) {

            // if (!empty($request->file[$i])) {
            //     $file_name = $request->file('file')[$i]->getClientOriginalName();
            //     $x = $request->file('file')[$i]->move(base_path('\public\file\kesehatan_rapid'), $request->date[$i] . '-' . $file_name);
            // } else {
            //     $file_name = NULL;
            // }

            $kesehatan_mingguan_rapid = Kesehatan_mingguan_rapid::create([
                'karyawan_id' => $request->karyawan_id[$i],
                'pemeriksa_id' => $request->pemeriksa_id[$i],
                'tgl_cek' => $request->date[$i],
                'hasil' => $request->hasil_covid[$i],
                'jenis' => $request->jenis_tes[$i],
                'keterangan' => $request->keterangan[$i],

            ]);
        }
        if ($kesehatan_mingguan_rapid) {
            return redirect()->back()->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }

    public function kesehatan_mingguan_detail()
    {

        $karyawan = Karyawan::orderBy('nama', 'ASC')
            ->has('Kesehatan_awal')
            ->get();
        return view('page.kesehatan.kesehatan_mingguan_detail', ['karyawan' => $karyawan]);
    }
    public function kesehatan_mingguan_tensi_detail_data($karyawan_id)
    {
        $data = Kesehatan_mingguan_tensi::where('karyawan_id', $karyawan_id);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('sis', function ($data) {
                return $data->sistolik . ' mmHg';
            })
            ->addColumn('dias', function ($data) {
                return $data->diastolik . ' mmHg';
            })
            ->addColumn('aksi', function ($data) {
                return '<i class="fas fa-trash text-danger" id="delete"></i>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function kesehatan_mingguan_rapid_detail_data($karyawan_id)
    {
        $data = Kesehatan_mingguan_rapid::where('karyawan_id', $karyawan_id);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('z', function ($data) {
                return $data->pemeriksa->nama;
            })
            ->addColumn('aksi', function ($data) {
                return '<i class="fas fa-trash text-danger" id="delete"></i>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function kesehatan_mingguan_tensi_detail_data_karyawan($karyawan_id)
    {
        $data = Kesehatan_mingguan_tensi::where('karyawan_id', $karyawan_id)->get();
        $data2 = Kesehatan_mingguan_rapid::where([['hasil', 'Non Reaktif'], ['karyawan_id', $karyawan_id]])->count();
        $data3 = Kesehatan_mingguan_rapid::where([['hasil', 'IgG'], ['karyawan_id', $karyawan_id]])->count();
        $data4 = Kesehatan_mingguan_rapid::where([['hasil', 'IgM'], ['karyawan_id', $karyawan_id]])->count();
        $data5 = Kesehatan_mingguan_rapid::where([['hasil', 'IgG-IgM '], ['karyawan_id', $karyawan_id]])->count();
        $tgl = $data->pluck('tgl_cek');
        $labels2 = $data->pluck('sistolik');
        $labels3 = $data->pluck('diastolik');
        return response()->json(compact('tgl', 'labels2', 'labels3', 'data2', 'data3', 'data4', 'data5'));
    }
    public function obat()
    {
        return view('page.kesehatan.obat');
    }
    public function obat_tambah()
    {
        return view('page.kesehatan.obat_tambah');
    }

    public function obat_aksi_delete($id)
    {
        $b = Detail_obat::where('obat_id', $id)->delete();
        $c = Obat::find($id);
        $delete = $c->delete();

        if ($delete) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di hapus']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }

    public function obat_data()
    {
        $data = Obat::all();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('a', function ($data) {

                $x = "";
                if ($data->stok <= 1) {
                    $x = $data->stok . ' Pc';
                } else {
                    $x =  $data->stok . ' Pcs';
                }
                $btn = $x . '<i class="fas fa-sync m-1" id="stok_obat_edit" aria-hidden="true"></i>';
                // $btn = $x . '<br><div class="inline-flex"><button type="button" id="stok"  class="btn btn-block btn-primary karyawan-img-small" style="border-radius:50%;" ><i class="fas fa-sync" aria-hidden="true"></i></button></div>';

                return $btn;
            })
            ->addColumn('aturan', function ($data) {
                return $data->aturan;
            })

            ->addColumn('button', function ($data) {
                $btn = '<div class="btn-toolbar d-flex justify-content-center" role="toolbar" aria-label="Toolbar with button groups">
                <button type="button" id="riwayat"  class="btn btn-sm btn-outline-primary btn-sm m-1"><i class="fas fa-eye"></i> Riwayat Pakai</button>
                <button type="button" id="edit" class="btn btn-sm btn-warning btn-sm m-1" data-obat-status="' . $data->is_aktif . '"><i class="fas fa-edit"></i> Ubah</button>
                <button type="button" id="delete" class="btn btn-sm btn-danger btn-sm m-1" data-id="' . $data->id . '"><i class="fas fa-trash"></i> Hapus</button>
                </div>';

                // $btn = '<div class="inline-flex"><button type="button" id="riwayat"  class="btn btn-block btn-primary btn-sm" style="border-radius:50%;" ><i class="fa fa-eye" aria-hidden="true"></i></button></div>';
                // $btn = $btn . '<div class="inline-flex"><button type="button" id="edit" class="btn btn-block btn-success btn-sm" style="border-radius:50%;" ><i class="fas fa-edit"></i></button></div>';
                return $btn;
            })
            ->rawColumns(['button', 'detail_button', 'a'])
            ->setRowClass(function ($data) {
                if ($data->is_aktif == 0) {
                    return 'text-danger font-weight-bold line-through';
                }
            })
            ->make(true);
    }
    public function obat_stok_data($id)
    {
        $data = Riwayat_stok_obat::where('obat_id', $id);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('a', function ($data) {
                if ($data->stok <= 1) {
                    $x = $data->stok . ' Pc';
                } else {
                    $x =  $data->stok . ' Pcs';
                }
                return $x;
            })
            ->addColumn('aksi', function ($data) {
                return '<button type="button" id="delete" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function obat_detail_data($id)
    {
        $data = Detail_obat::where('obat_id', $id)->orderby('id', 'DESC');
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->Karyawan_sakit->tgl_cek)->format('d-m-Y');
            })
            ->addColumn('div', function ($data) {
                return $data->karyawan_sakit->karyawan->divisi->nama;
            })
            ->addColumn('x', function ($data) {
                return $data->karyawan_sakit->karyawan->nama;
            })
            ->addColumn('anal', function ($data) {
                return $data->karyawan_sakit->analisa;
            })
            ->addColumn('diag', function ($data) {
                return $data->karyawan_sakit->diagnosa;
            })
            ->addColumn('jum', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('aksi', function ($data) {
                return '<i class="fas fa-trash text-danger" id="delete"></i>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function obat_cekdata($nama)
    {
        $data = Obat::where('nama', $nama)->get();
        echo json_encode($data);
    }
    public function obat_aksi_tambah(Request $request)
    {
        $this->validate(
            $request,
            [
                'nama' => 'required|unique:kesehatan.obats',
                'aturan_obat' => 'required',
            ],
            [
                'nama.required' => 'Nama obat harus di isi',
                'nama.unique' => 'Nama obat harus sudah pernah di input',
                'aturan_obat.required' => 'Aturan harus di isi',
            ]
        );
        $obat = Obat::create([
            'nama' => $request->nama,
            'aturan' => $request->aturan_obat,
            'stok' => 0,
            'keterangan' => $request->keterangan
        ]);

        if ($obat) {
            return redirect()->back()->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }

    public function obat_stok_aksi_tambah(Request $request)
    {

        $this->validate(
            $request,
            [
                'tgl_pembelian' => 'required',
                'stok' => 'required'
            ],
            [
                'tgl_pembelian.required' => 'Tgl Pembelian harus di isi',
                'stok.required' => 'Stok obat harus di isi'
            ]
        );
        $stok_obat = Riwayat_stok_obat::create([
            'tgl_pembelian' => $request->tgl_pembelian,
            'obat_id' => $request->id,
            'stok' => $request->stok,
            'keterangan' => $request->keterangan
        ]);

        $obat = Obat::find($request->id)->increment('stok', $request->stok);
        if ($stok_obat || $obat) {
            return redirect()->back()->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }
    public function obat_aksi_ubah(Request $request)
    {
        $id = $request->id;
        $obat = Obat::find($id);
        $obat->nama = $request->nama;
        $obat->keterangan = $request->keterangan;
        $obat->aturan = $request->aturan_obat;
        $obat->is_aktif = $request->status_obat;
        $obat->save();
        if ($obat) {
            return redirect()->back()->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }
    public function obat_data_select(Request $request, $where)
    {
        $x = explode(',', $where);
        $data = Obat::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')->where('is_aktif', 1)->whereNotIN('id', $x)->get();
        echo json_encode($data);
    }
    public function obat_data_id(Request $request, $id)
    {
        $data = Obat::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')->where('id', $id)->get();
        echo json_encode($data);
    }
    public function karyawan_sakit()
    {
        return view('page.kesehatan.karyawan_sakit');
    }
    public function karyawan_sakit_data($value)
    {

            $data = Karyawan_sakit::with(['Karyawan.Divisi', 'Pemeriksa'])->orderBy('tgl_cek', 'DESC')->get();


        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('tgl_cek', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->tgl_cek)->format('d-m-Y');
            })
            ->addColumn('x', function ($data) {
                return $data->Karyawan->Divisi->nama;
            })
            ->addColumn('y', function ($data) {
                return $data->Karyawan->nama;
            })
            ->addColumn('z', function ($data) {
                return $data->Pemeriksa->nama;
            })
            ->addColumn('o', function ($data) {
                if ($data->obat_id != NULL) {
                    return $data->Obat->nama;
                } else {
                    return '';
                }
            })
            ->addColumn('d', function ($data) {
                if ($data->obat_id != NULL) {
                    return $data->aturan;
                } else {
                    return '';
                }
            })
            ->addColumn('e', function ($data) {
                if ($data->obat_id != NULL) {
                    return $data->konsumsi . ' Hari';
                } else {
                    return '';
                }
            })
            ->addColumn('f', function ($data) {
                if ($data->terapi != NULL) {
                    return $data->terapi;
                } else {
                    return '';
                }
            })
            ->addColumn('detail_button', function ($data) {
                $btn = '';
                if ($data->tindakan == "Terapi") {
                    $btn = '<span class="badge yellow-text">';
                } else {
                    $btn = '<span class="badge red-text">';
                }
                $btn .= $data->tindakan . '</span>';

                return $btn;
                // $btn = $data->tindakan;
                // $btn = $btn . '<br><div class="inline-flex"><button type="button" id="detail_tindakan"  class="btn btn-block btn-primary karyawan-img-small" style="border-radius:50%;" ><i class="fas fa-eye"></i></button></div>';
                // return  $btn;

            })
            ->editColumn('keputusan', function ($data) {
                return $data->keputusan;
            })
            ->addColumn('button', function () {

                $btn = '<div class="inline-flex"><button type="button" id="edit_gcu"  class="btn btn-block btn-success karyawan-img-small" style="border-radius:50%;" ><i class="fa fa-eye" aria-hidden="true"></i></button></div>';
                return $btn;
            })
            ->addColumn('cetak', function ($data) {

                $btn = '<div class="btn-group">
                <span><button type="button" id="detail_tindakan"  class="btn btn-xs btn-outline-info m-1"><i class="fas fa-eye"></i> Detail</button></span>';

                $btn .= '<span><button type="button" id="delete"  data-id="' . $data->id . '" class="btn btn-xs btn-danger m-1"><i class="fas fa-trash"></i> Hapus</button></span></div>';
                // $btn = '<div class="inline-flex"><a href="/karyawan/sakit/cetak/' . $data->id . '" target="_break"><button type="button" id="cetak_gcu"  class="btn btn-block btn-success karyawan-img-small" style="border-radius:50%;" ><i class="fas fa-print"></i></button></a></div>';
                return $btn;
            })
            ->rawColumns(['button', 'detail_button', 'cetak', 'keputusan'])
            ->make(true);
    }
    public function karyawan_sakit_tambah()
    {
        $karyawan = $this->get_data_karyawan_aktif();
        $obat = Obat::where('stok', '!=', 0)->get();
        $pengecek = $this->get_data_karyawan_pemeriksa();
        return view('page.kesehatan.karyawan_sakit_tambah', ['karyawan' => $karyawan, 'pengecek' => $pengecek, 'obat' => $obat]);
    }
    public function karyawan_sakit_aksi_delete($id)
    {

        $b = Karyawan_sakit::find($id);

        if($b->Detail_Obat){
            Detail_obat::where('karyawan_sakit_id', $id)->delete();
            $delete =  $b->delete();

        }else{
            $delete =   $b->delete();
        }


        if ($delete) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di hapus']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }
    public function obat_data_detail($id)
    {
        $data = Detail_obat::with('obat')->where('karyawan_sakit_id', $id);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('aturan', function ($data) {
                if ($data->obat) {
                    return $data->obat->aturan;
                }
            })
            ->addColumn('konsumsi', function ($data) {

                return $data->konsumsi;
            })
            ->addColumn('x', function ($data) {
                return $data->obat->nama;
            })
            ->addColumn('y', function ($data) {
                if ($data->jumlah <= 1) {
                    $x = $data->jumlah . ' Pc';
                } else {
                    $x =  $data->jumlah . ' Pcs';
                }
                return $x;
            })


            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function karyawan_sakit_aksi_tambah(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),  [
            'karyawan_id' => 'required',
            'tgl' => 'required',
            'pemeriksa_id' => 'required',
            'analisa' => 'required',
            'diagnosa' => 'required',
            'terapi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        } else {
            //Cek Dengan Obat
            if (isset($request->isi_obat)) {
                //Cek Form Kosong
                if (in_array(NULL, $request->dosis_obat_custom_obat) || $request->karyawan_id == "NULL" || in_array(NULL, $request->dosis_obat_custom_hari) || in_array(NULL, $request->jumlah) ||  in_array("NULL", $request->obat)) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Form Kosong',
                    ], 500);
                }

                $karyawan_sakit =  Karyawan_sakit::create([
                    'tgl_cek' => $request->tgl,
                    'karyawan_id' => $request->karyawan_id,
                    'pemeriksa_id' => $request->pemeriksa_id,
                    'analisa' => $request->analisa,
                    'diagnosa' => $request->diagnosa,
                    'tindakan' => 'Pengobatan',
                    'terapi' => $request->terapi,
                    'keputusan' => '-'
                ]);

                for ($i = 0; $i < count($request->obat); $i++) {
                    Obat::find($request->obat[$i])->decrement('stok', $request->jumlah[$i]);
                    Detail_obat::create([
                        'karyawan_sakit_id' => $karyawan_sakit->id,
                        'obat_id' => $request->obat[$i],
                        'jumlah' => $request->jumlah[$i],
                        'aturan' => '-',
                        'konsumsi' =>  $request->dosis_obat_custom_obat[$i] . 'x' . $request->dosis_obat_custom_hari[$i],
                    ]);
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'Dengan Obat OK',
                ], 200);
            } else {

                Karyawan_sakit::create([
                    'tgl_cek' => $request->tgl,
                    'karyawan_id' => $request->karyawan_id,
                    'pemeriksa_id' => $request->pemeriksa_id,
                    'analisa' => $request->analisa,
                    'diagnosa' => $request->diagnosa,
                    'tindakan' => 'Terapi',
                    'terapi' => $request->terapi,
                    'keputusan' => '-'
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Tanpa Obat OK',
                ], 200);
            }
        }

        // $this->validate(
        //     $request,
        //     [
        //         'karyawan_id' => 'required',
        //         'tgl' => 'required',
        //         'pemeriksa_id' => 'required',
        //         'obat.*' => 'required',
        //     ],
        //     [
        //         'pemeriksa_id.required' => 'Pemeriksa harus di pilih',
        //         'karyawan_id.required' => 'Karyawan harus di pilih',
        //         'tgl.required' => 'Tanggal pengecekan harus di isi',
        //     ]
        // );
        // $karyawan_sakit = Karyawan_sakit::create([
        //     'tgl_cek' => $request->tgl,
        //     'karyawan_id' => $request->karyawan_id,
        //     'pemeriksa_id' => $request->pemeriksa_id,
        //     'analisa' => $request->analisa,
        //     'diagnosa' => $request->diagnosa,
        //     'tindakan' => $request->hasil_1,
        //     'terapi' => $request->terapi,
        //     'keputusan' => $request->hasil_2
        // ]);

        // if ($request->hasil_1 == 'Pengobatan') {

        //     for ($i = 0; $i < count($request->obat); $i++) {
        //         $obat = Obat::find($request->obat[$i])->decrement('stok', $request->jumlah[$i]);

        //         if ($request->dosis_obat_custom_obat[$i] != null) {
        //             $detail_obat = detail_obat::create([
        //                 'karyawan_sakit_id' => $karyawan_sakit->id,
        //                 'obat_id' => $request->obat[$i],
        //                 'jumlah' => $request->jumlah[$i],
        //                 'aturan' => '-',
        //                 'konsumsi' =>  $request->dosis_obat_custom_obat[$i] . 'x' . $request->dosis_obat_custom_hari[$i],
        //             ]);
        //         } else {
        //             $detail_obat = detail_obat::create([
        //                 'karyawan_sakit_id' => $karyawan_sakit->id,
        //                 'obat_id' => $request->obat[$i],
        //                 'jumlah' => $request->jumlah[$i],
        //                 'konsumsi' => $request->dosis_obat[$i],
        //                 'aturan' => '-',
        //             ]);
        //         }
        //     }
        // }

        // if ($karyawan_sakit || $detail_obat) {
        //     return redirect()->back()->with('success', 'Berhasil menambahkan data');
        // } else {
        //     return redirect()->back()->with('error', 'Gagal menambahkan data');
        // }
    }
    public function karyawan_sakit_cetak($id)
    {
        $karyawan_sakit = Karyawan_sakit::find($id);
        $dateOfBirth = $karyawan_sakit->karyawan->tgllahir;
        $umur = Carbon::parse($dateOfBirth)->age;
        $carbon = Carbon::now();
        $footer = Carbon::createFromFormat('Y-m-d', $karyawan_sakit->tgl_cek)->isoFormat('D MMMM Y');
        $pdf = PDF::loadView('page.kesehatan.surat_sakit', ['karyawan_sakit' => $karyawan_sakit, 'umur' => $umur, 'carbon' => $carbon, 'footer' => $footer])->setPaper('A5', 'Landscape');
        return $pdf->stream('');
    }
    public function karyawan_masuk_aksi_delete($id)
    {
        $b = Karyawan_masuk::find($id);
        $delete = $b->delete();
        if ($delete) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di hapus']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }
    public function karyawan_masuk()
    {
        return view('page.kesehatan.karyawan_masuk');
    }
    public function karyawan_masuk_data()
    {
        $data = Karyawan_masuk::with(['Karyawan.Divisi', 'Pemeriksa', 'Karyawan_sakit'])->orderBy('tgl_cek', 'DESC');
        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('tgl_cek', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->tgl_cek)->format('d-m-Y');
            })
            ->addColumn('x', function ($data) {
                return $data->Karyawan->Divisi->nama;
            })
            ->addColumn('y', function ($data) {
                return $data->Karyawan->nama;
            })
            ->addColumn('z', function ($data) {
                return $data->Pemeriksa->nama;
            })
            ->addColumn('button', function ($data) {
                $btn = '<div class="btn-group">';
                if ($data->alasan == "Sakit") {
                    $btn .= '<span class="m-1"><button type="button" id="riwayat" class="btn btn-block btn-outline-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Detail</button></span>';
                } else {
                    $btn .= '<span class="m-1"><button type="button"  class="btn btn-block btn-light btn-sm" disabled><i class="fa fa-eye" aria-hidden="true"></i> Detail</button></span>';
                }
                $btn = '<span class="m-1"><button type="button" id="delete" class="btn btn-sm btn-danger" data-id="' . $data->id . '"><i class="fas fa-trash"></i> Hapus</button></span></div>';
                return $btn;
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    public function karyawan_masuk_tambah()
    {
        $obat = Obat::where('stok', '!=', 0)->get();
        $karyawan = $this->get_data_karyawan_aktif();
        $pengecek = $this->get_data_karyawan_pemeriksa();
        return view('page.kesehatan.karyawan_masuk_tambah', ['karyawan' => $karyawan, 'pengecek' => $pengecek, 'obat' => $obat]);
    }
    public function karyawan_masuk_aksi_tambah(Request $request)
    {

        $validator = Validator::make($request->all(),  [
            'pemeriksa_id' => 'required',
            'karyawan_id' => 'required',
            'tgl' => 'required',
            'alasan' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        } else {

            if ($request->karyawan_id == "NULL") {
                return response()->json([
                    'status' => 200,
                    'message' => 'Form Kosong',
                ], 500);
            }

            Karyawan_masuk::create([
                'karyawan_id' => $request->karyawan_id,
                'pemeriksa_id' => $request->pemeriksa_id,
                'tgl_cek' => $request->tgl,
                'alasan' => $request->alasan,
                'keterangan' => $request->keterangan
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        }
        // $this->validate(
        //     $request,
        //     [
        //         'tgl' => 'required',
        //         'karyawan_id' => 'required'
        //     ],
        //     [
        //         'tgl.required' => 'Tgl pemeriksaan harus di isi',
        //         'karyawan_id.unique' => 'Nama karyawan harus di pilih'
        //     ]
        // );

        // if ($request->alasan == "Sakit") {


        //     $karyawan_sakit = Karyawan_sakit::create([
        //         'tgl_cek' => $request->tgl,
        //         'karyawan_id' => $request->karyawan_id,
        //         'pemeriksa_id' => $request->pemeriksa_id,
        //         'analisa' => $request->analisa,
        //         'diagnosa' => $request->diagnosa,
        //         'tindakan' => $request->hasil_1,
        //         'terapi' => $request->terapi,
        //         'keputusan' => $request->hasil_2
        //     ]);

        //     $karyawan_masuk = Karyawan_masuk::create([
        //         'karyawan_id' => $request->karyawan_id,
        //         'pemeriksa_id' => $request->pemeriksa_id,
        //         'karyawan_sakit_id' => $karyawan_sakit->id,
        //         'tgl_cek' => $request->tgl,
        //         'alasan' => $request->alasan,
        //         'keterangan' => $request->keterangan
        //     ]);
        // } else {
        //     $karyawan_masuk = Karyawan_masuk::create([
        //         'karyawan_id' => $request->karyawan_id,
        //         'pemeriksa_id' => $request->pemeriksa_id,
        //         'tgl_cek' => $request->tgl,
        //         'alasan' => $request->alasan,
        //         'keterangan' => $request->keterangan
        //     ]);
        // }
        // if ($request->hasil_1 == 'Pengobatan') {
        //     for ($i = 0; $i < count($request->obat); $i++) {
        //         $obat = obat::find($request->obat[$i])->decrement('stok', $request->jumlah[$i]);

        //         if ($request->dosis_obat_custom_obat[$i] != null) {
        //             $detail_obat = detail_obat::create([
        //                 'karyawan_sakit_id' => $karyawan_sakit->id,
        //                 'obat_id' => $request->obat[$i],
        //                 'jumlah' => $request->jumlah[$i],
        //                 'aturan' => '-',
        //                 'konsumsi' =>  $request->dosis_obat_custom_obat[$i] . 'x' . $request->dosis_obat_custom_hari[$i],
        //             ]);
        //         } else {
        //             $detail_obat = detail_obat::create([
        //                 'karyawan_sakit_id' => $karyawan_sakit->id,
        //                 'obat_id' => $request->obat[$i],
        //                 'jumlah' => $request->jumlah[$i],
        //                 'konsumsi' => $request->dosis_obat[$i],
        //                 'aturan' => '-',
        //             ]);
        //         }
        //     }
        // }
        // if ($karyawan_masuk) {
        //     return redirect()->back()->with('success', 'Berhasil menambahkan data');
        // } else {
        //     return redirect()->back()->with('error', 'Gagal menambahkan data');
        // }
    }
    public function karyawan_masuk_detail_data($id)
    {
        $data = Karyawan_sakit::where('id', $id);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('x', function ($data) {
                return $data->karyawan->divisi->nama;
            })
            ->addColumn('y', function ($data) {
                return $data->karyawan->nama;
            })
            ->addColumn('aksi', function ($data) {
                return '<button type="button" id="delete" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function kesehatan_bulanan()
    {
        $karyawan = $this->get_data_karyawan_aktif();
        return view('page.kesehatan.kesehatan_bulanan', ['karyawan' => $karyawan]);
    }


    public function kesehatan_bulanan_gcu_data()
    {
        $data = Gcu_karyawan::with('Karyawan.Divisi')
            ->orderBy('tgl_cek', 'DESC');

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('x', function ($data) {
                return $data->Karyawan->Divisi->nama;
            })
            ->addColumn('glu', function ($data) {
                if ($data->glukosa != NULL) {
                    return $data->glukosa;
                } else {
                    return '0 %';
                }
            })

            ->addColumn('kol', function ($data) {
                if ($data->kolesterol != NULL) {
                    return $data->kolesterol;
                } else {
                    return '0 %';
                }
            })

            ->addColumn('asam', function ($data) {
                if ($data->asam_urat != NULL) {
                    return $data->asam_urat;
                } else {
                    return '0 %';
                }
            })
            ->addColumn('button', function ($data) {
                $btn = '<div class="btn-group"><span><button type="button" id="edit_gcu" data-id="' . $data->id . '" class="btn btn-xs btn-warning m-1"><i class="fas fa-edit"></i> Ubah</button></span>
                <span><button type="button" id="delete" class="btn btn-xs btn-danger m-1" data-id="' . $data->id . '"><i class="fas fa-trash"></i> Hapus</button></span></div>';
                return $btn;
            })
            ->rawColumns(['button'])
            ->make(true);
    }

    public function kesehatan_bulanan_berat_delete($id)
    {
        $b = Berat_karyawan::find($id);
        $delete = $b->delete();
        if ($delete) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di hapus']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }

    public function kesehatan_bulanan_gcu_delete($id)
    {
        $b = Gcu_karyawan::find($id);
        $delete = $b->delete();
        if ($delete) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di hapus']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }

    public function kesehatan_bulanan_berat_data()
    {
        $data = berat_karyawan::with(['Karyawan.Divisi'])->orderBy('tgl_cek', 'DESC');

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('x', function ($data) {
                return $data->Karyawan->Divisi->nama;
            })
            ->addColumn('y', function ($data) {
                return $data->Karyawan->nama;
            })
            ->addColumn('z', function ($data) {
                return $data->berat . ' Kg';
            })
            ->addColumn('l', function ($data) {
                if ($data->lemak != NULL) {
                    return $data->lemak . ' gram';
                } else {
                    return '0 gram';
                }
            })
            ->addColumn('k', function ($data) {
                if ($data->kandungan_air != NULL) {
                    return $data->kandungan_air . ' %';
                } else {
                    return '0 %';
                }
            })
            ->addColumn('o', function ($data) {
                if ($data->otot != NULL) {
                    return $data->otot . ' Kg';
                } else {
                    return '0 Kg';
                }
            })
            ->addColumn('t', function ($data) {
                if ($data->tulang != NULL) {
                    return $data->tulang . ' Kg';
                } else {
                    return '0 Kg';
                }
            })
            ->addColumn('ka', function ($data) {
                if ($data->kalori != NULL) {
                    return $data->kalori . ' kkal';
                } else {
                    return '0 kkal';
                }
            })
            ->addColumn('suhu_k', function ($data) {
                return $data->suhu . ' °C';
            })
            ->addColumn('sis', function ($data) {
                return $data->sistolik . ' mmHg';
            })
            ->addColumn('dias', function ($data) {
                return $data->diastolik . ' mmHg';
            })
            ->addColumn('sp', function ($data) {
                return $data->spo2 . ' %';
            })
            ->addColumn('pr', function ($data) {
                return $data->pr . ' bpm';
            })
            ->addColumn('button', function ($data) {

                $btn = '<div class="btn-group">
                <span><button type="button" id="edit_berat" class="btn btn-xs btn-warning m-1" data-id="' . $data->id . '"><i class="fas fa-edit"></i> Ubah</button></span>
                <span><button type="button" id="delete" class="btn btn-xs btn-danger m-1" data-id="' . $data->id . '"><i class="fas fa-trash"></i> Hapus</button></span></div>';
                return $btn;
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    public function kesehatan_bulanan_gcu_tambah()
    {
        $karyawan =  $this->get_data_karyawan_aktif();
        return view('page.kesehatan.kesehatan_bulanan_gcu_tambah', ['karyawan' => $karyawan]);
    }


    public function kesehatan_bulanan_berat_tambah()
    {
        $karyawan = $this->get_data_karyawan_aktif();
        return view('page.kesehatan.kesehatan_bulanan_berat_tambah', ['karyawan' => $karyawan]);
    }

    public function kesehatan_bulanan_berat_aksi_tambah(Request $request)
    {
        $this->validate(
            $request,
            [
                'karyawan_id.*' => 'required',
                'tgl_cek.*' => 'required',
                'berat.*' => 'required',
                'lemak.*' => 'required',
                'kandungan_air.*' => 'required',
                'otot.*' => 'required',
                'tulang.*' => 'required',
                'kalori.*' => 'required',
            ],
            [
                'karyawan_id.required' => 'Divisi harus di pilih',
                'tgl_cek.required' => 'Tanggal pengecekan harus dipilih',
                'berat.required' => 'Berat harus di isi',
                'lemak.required' => 'Lemak harus di isi',
                'kandungan_air.required' => 'Kandungan air harus di isi',
                'otot.required' => 'Otot harus di isi',
                'tulang.required' => 'Tulang harus di isi',
                'kalori.required' => 'Kalori harus di isi',
            ]
        );
        for ($i = 0; $i < count($request->karyawan_id); $i++) {
            $berat_karyawan = berat_karyawan::create([
                'karyawan_id' => $request->karyawan_id[$i],
                'tgl_cek' => $request->tgl_cek[$i],
                'berat' => $request->berat[$i],
                'lemak' => $request->lemak[$i],
                'kandungan_air' => $request->kandungan_air[$i],
                'otot' => $request->otot[$i],
                'tulang' => $request->tulang[$i],
                'kalori' => $request->kalori[$i],
                'suhu' => $request->suhu[$i],
                'spo2' => $request->spo2[$i],
                'pr' => $request->pr[$i],
                'sistolik' => $request->sistolik[$i],
                'diastolik' => $request->diastolik[$i],
                'keterangan' => $request->keterangan[$i]
            ]);
        }
        if ($berat_karyawan) {
            return redirect()->back()->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }
    public function kesehatan_bulanan_gcu_aksi_tambah(Request $request)
    {
        for ($i = 0; $i < count($request->karyawan_id); $i++) {
            $gcu_karyawan = gcu_karyawan::create([
                'karyawan_id' => $request->karyawan_id[$i],
                'tgl_cek' => $request->tgl_cek[$i],
                'glukosa' => $request->glukosa[$i],
                'kolesterol' => $request->kolesterol[$i],
                'asam_urat' => $request->asam_urat[$i],
                'keterangan' => $request->keterangan[$i]
            ]);
        }

        if ($gcu_karyawan) {
            return redirect()->back()->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }
    public function kesehatan_bulanan_gcu_aksi_ubah(Request $request)
    {
        $id = $request->id;
        $gcu_karyawan = Gcu_karyawan::find($id);
        $gcu_karyawan->glukosa = $request->glukosa;
        $gcu_karyawan->kolesterol = $request->kolesterol;
        $gcu_karyawan->asam_urat = $request->asam_urat;
        $gcu_karyawan->keterangan = $request->catatan;
        $gcu_karyawan->save();

        if ($gcu_karyawan) {
            return redirect()->back()->with('success', 'Data berhasil di ubah');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }
    public function kesehatan_bulanan_berat_aksi_ubah(Request $request)
    {
        $id = $request->id;
        $berat_karyawan = Berat_karyawan::find($id);
        $berat_karyawan->berat = $request->berat;
        $berat_karyawan->lemak = $request->lemak;
        $berat_karyawan->otot = $request->otot;
        $berat_karyawan->kandungan_air = $request->kandungan_air;
        $berat_karyawan->tulang = $request->tulang;
        $berat_karyawan->kalori = $request->kalori;
        $berat_karyawan->keterangan = $request->catatan;
        $berat_karyawan->save();

        if ($berat_karyawan) {
            return redirect()->back()->with('success', 'Data berhasil di ubah');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }
    public function kesehatan_bulanan_gcu_detail()
    {
        $karyawan = Karyawan::orderBy('nama', 'ASC')
            ->has('Kesehatan_awal')
            ->get();
        return view('page.kesehatan.kesehatan_bulanan_detail', ['karyawan' => $karyawan]);
    }
    public function kesehatan_bulanan_detail_data_karyawan($karyawan_id)
    {
        $data = Gcu_karyawan::where('karyawan_id', $karyawan_id)->get();
        $data2 = Berat_karyawan::where('karyawan_id', $karyawan_id)->get();
        $tgl2 = $data2->pluck('tgl_cek');
        $tgl = $data->pluck('tgl_cek');
        $labels2 = $data->pluck('glukosa');
        $labels3 = $data->pluck('kolesterol');
        $labels4 = $data->pluck('asam_urat');
        $labels5 = $data2->pluck('berat');
        return response()->json(compact('tgl', 'tgl2', 'labels2', 'labels3', 'labels4', 'labels5'));
    }

    public function kesehatan_bulanan_gcu_detail_data($karyawan_id)
    {
        $data = Gcu_karyawan::where('karyawan_id', $karyawan_id);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('glu', function ($data) {
                if ($data->glukosa != NULL) {
                    return $data->glukosa;
                } else {
                    return '0 %';
                }
            })

            ->addColumn('kol', function ($data) {
                if ($data->kolesterol != NULL) {
                    return $data->kolesterol;
                } else {
                    return '0 %';
                }
            })

            ->addColumn('asam', function ($data) {
                if ($data->asam_urat != NULL) {
                    return $data->asam_urat;
                } else {
                    return '0 %';
                }
            })
            ->addColumn('aksi', function ($data) {
                return '<i class="fas fa-trash text-danger"  id="delete"></i>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }



    public function laporan_mingguan()
    {
        $karyawan = Karyawan::orderBy('nama', 'ASC')
            ->has('Kesehatan_awal')
            ->get();
        $divisi = Divisi::all();
        return view('page.kesehatan.laporan_mingguan', ['karyawan' => $karyawan, 'divisi' => $divisi]);
    }

    public function laporan_mingguan_data($filter_mingguan, $filter, $id, $start, $end)
    {
        if ($filter == 'divisi' && $filter_mingguan == 'tensi') {
            $data = kesehatan_mingguan_tensi::with(['Karyawan.Divisi'])->wherehas('karyawan', function ($divisi) use ($id) {
                $divisi->where('divisi_id', $id);
            })
                ->orderBy('tgl_cek', 'DESC')
                ->whereBetween('tgl_cek', [$start, $end]);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('x', function ($data) {
                    return $data->Karyawan->Divisi->nama;
                })
                ->addColumn('y', function ($data) {
                    if ((($data->sistolik) - ($data->diastolik)) < 30) {
                        return $data->Karyawan->nama . '<br><span class="badge bg-danger"><i class="fas fa-exclamation"></i> perlu tindakan lanjut</span>';
                    } else {
                        return $data->Karyawan->nama;
                    }
                })
                ->addColumn('hasil', function ($data) {
                    if ($data->sistolik < 130) {
                        return '<span class="badge bg-success">Normal</span>';
                    } else if ($data->sistolik >= 130 && $data->sistolik <= 139) {
                        return '<span class="badge bg-warning">Pra-Hipertensi</span>';
                    } else if ($data->sistolik >= 140 && $data->sistolik <= 159) {
                        return '<span class="badge bg-info">Stadium 1 Hipertensi</span>';
                    } else if ($data->sistolik >= 160) {
                        return '<span class="badge bg-danger">Stadium 2 Hipertensi</span>';
                    } else {
                        return 'Error';
                    }
                })
                ->addColumn('sis', function ($data) {
                    return $data->sistolik . ' mmHg';
                })
                ->addColumn('dias', function ($data) {
                    return $data->diastolik . ' mmHg';
                })
                ->rawColumns(['hasil', 'y'])
                ->make(true);
        } else if ($filter == 'karyawan' && $filter_mingguan == 'tensi') {
            $data = kesehatan_mingguan_tensi::with(['Karyawan.Divisi'])
                ->orderBy('tgl_cek', 'DESC')
                ->where('karyawan_id', $id)
                ->whereBetween('tgl_cek', [$start, $end]);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('x', function ($data) {
                    return $data->Karyawan->Divisi->nama;
                })
                ->addColumn('y', function ($data) {
                    if ((($data->sistolik) - ($data->diastolik)) < 30) {
                        return $data->Karyawan->nama . '<br><span class="badge bg-danger"><i class="fas fa-exclamation"></i> perlu tindakan lanjut</span>';
                    } else {
                        return $data->Karyawan->nama;
                    }
                })
                ->addColumn('hasil', function ($data) {
                    if ($data->sistolik < 130) {
                        return '<span class="badge bg-success">Normal</span>';
                    } else if ($data->sistolik >= 130 && $data->sistolik <= 139) {
                        return '<span class="badge bg-warning">Pra-Hipertensi</span>';
                    } else if ($data->sistolik >= 140 && $data->sistolik <= 159) {
                        return '<span class="badge bg-info">Stadium 1 Hipertensi</span>';
                    } else if ($data->sistolik >= 160) {
                        return '<span class="badge bg-danger">Stadium 2 Hipertensi</span>';
                    } else {
                        return 'Error';
                    }
                })
                ->addColumn('sis', function ($data) {
                    return $data->sistolik . ' mmHg';
                })
                ->addColumn('dias', function ($data) {
                    return $data->diastolik . ' mmHg';
                })
                ->rawColumns(['hasil', 'y'])
                ->make(true);
        } else if ($filter == 'karyawan' && $filter_mingguan == 'rapid') {
            $data = kesehatan_mingguan_rapid::with(['Karyawan.Divisi'])
                ->orderBy('tgl_cek', 'DESC')
                ->where('karyawan_id', $id)
                ->whereBetween('tgl_cek', [$start, $end]);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('x', function ($data) {
                    return $data->Karyawan->Divisi->nama;
                })
                ->addColumn('z', function ($data) {
                    return $data->pemeriksa->nama;
                })
                ->addColumn('yy', function ($data) {
                    return $data->Karyawan->nama;
                })
                ->make(true);
        } else if ($filter == 'divisi' && $filter_mingguan == 'rapid') {
            $data = kesehatan_mingguan_rapid::with(['Karyawan.Divisi', 'Pemeriksa'])->wherehas('karyawan', function ($divisi) use ($id) {
                $divisi->where('divisi_id', $id);
            })
                ->orderBy('tgl_cek', 'DESC')
                ->whereBetween('tgl_cek', [$start, $end]);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('x', function ($data) {
                    return $data->Karyawan->Divisi->nama;
                })
                ->addColumn('z', function ($data) {
                    return $data->Pemeriksa->nama;
                })
                ->make(true);
        } else if ($filter == 'x' && $filter_mingguan = 'y') {
            $data = kesehatan_mingguan_rapid::with(['Karyawan.Divisi'])
                ->orderBy('tgl_cek', 'DESC')
                ->where('karyawan_id', 0);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('x', function ($data) {
                    return $data->Karyawan->Divisi->nama;
                })
                ->addColumn('yy', function ($data) {
                    return $data->Karyawan->nama;
                })
                ->addColumn('hasil', function ($data) {
                    if ($data->sistolik < 130 && $data->diastolik < 85) {
                        return 'Normal';
                    } else if ($data->sistolik >= 130 && $data->sistolik <= 139 && $data->diastolik >= 85  && $data->diastolik <= 89) {
                        return 'Pra-Hipertensi';
                    } else if ($data->sistolik >= 140 && $data->sistolik <= 159 && $data->diastolik >= 90  && $data->diastolik <= 99) {
                        return 'Stadium 1 Hipertensi';
                    } else if ($data->sistolik >= 160  && $data->diastolik >= 100) {
                        return 'Stadium 2 Hipertensi';
                    } else {
                        return '';
                    }
                })
                ->addColumn('sis', function ($data) {
                    return $data->sistolik . ' mmHg';
                })
                ->addColumn('dias', function ($data) {
                    return $data->diastolik . ' mmHg';
                })
                ->make(true);
        }
    }

    public function laporan_bulanan()
    {
        $karyawan = Karyawan::orderBy('nama', 'ASC')
            ->has('Kesehatan_awal')
            ->get();
        $divisi = Divisi::all();
        return view('page.kesehatan.laporan_bulanan', ['karyawan' => $karyawan, 'divisi' => $divisi]);
    }

    public function laporan_bulanan_data($filter_bulanan, $filter, $id, $start, $end)
    {
        if ($filter == 'divisi' && $filter_bulanan == 'gcu') {
            $data = gcu_karyawan::with(['Karyawan.Divisi'])->wherehas('karyawan', function ($divisi) use ($id) {
                $divisi->where('divisi_id', $id);
            })
                ->orderBy('tgl_cek', 'DESC')
                ->whereBetween('tgl_cek', [$start, $end]);

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('x', function ($data) {
                    return $data->Karyawan->Divisi->nama;
                })
                ->addColumn('xx', function ($data) {
                    return $data->Karyawan->nama;
                })
                ->addColumn('glu', function ($data) {
                    if ($data->glukosa != NULL) {
                        return $data->glukosa;
                    } else {
                        return '0 %';
                    }
                })

                ->addColumn('kol', function ($data) {
                    if ($data->kolesterol != NULL) {
                        return $data->kolesterol;
                    } else {
                        return '0 %';
                    }
                })

                ->addColumn('asam', function ($data) {
                    if ($data->asam_urat != NULL) {
                        return $data->asam_urat;
                    } else {
                        return '0 %';
                    }
                })
                ->make(true);
        } else if ($filter == 'karyawan' && $filter_bulanan == 'gcu') {
            $data = gcu_karyawan::with(['Karyawan.Divisi'])
                ->where('karyawan_id', $id)
                ->orderBy('tgl_cek', 'DESC')
                ->whereBetween('tgl_cek', [$start, $end]);

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('x', function ($data) {
                    return $data->Karyawan->divisi->nama;
                })
                ->addColumn('xx', function ($data) {
                    return $data->Karyawan->nama;
                })
                ->addColumn('glu', function ($data) {
                    if ($data->glukosa != NULL) {
                        return $data->glukosa;
                    } else {
                        return '0 %';
                    }
                })

                ->addColumn('kol', function ($data) {
                    if ($data->kolesterol != NULL) {
                        return $data->kolesterol;
                    } else {
                        return '0 %';
                    }
                })

                ->addColumn('asam', function ($data) {
                    if ($data->asam_urat != NULL) {
                        return $data->asam_urat;
                    } else {
                        return '0 %';
                    }
                })
                ->make(true);
        } else if ($filter == 'karyawan' && $filter_bulanan == 'berat') {
            $data = berat_karyawan::with(['Karyawan.Divisi'])
                ->where('karyawan_id', $id)
                ->orderBy('tgl_cek', 'DESC')
                ->whereBetween('tgl_cek', [$start, $end]);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('x', function ($data) {
                    return $data->Karyawan->Divisi->nama;
                })
                ->addColumn('y', function ($data) {
                    return $data->Karyawan->nama;
                })
                ->addColumn('z', function ($data) {
                    return $data->berat . ' Kg';
                })
                ->addColumn('l', function ($data) {
                    if ($data->lemak != NULL) {
                        return $data->lemak . ' gram';
                    } else {
                        return '0 gram';
                    }
                })
                ->addColumn('k', function ($data) {
                    if ($data->kandungan_air != NULL) {
                        return $data->kandungan_air . ' %';
                    } else {
                        return '0 %';
                    }
                })
                ->addColumn('o', function ($data) {
                    if ($data->otot != NULL) {
                        return $data->otot . ' Kg';
                    } else {
                        return '0 Kg';
                    }
                })
                ->addColumn('t', function ($data) {
                    if ($data->tulang != NULL) {
                        return $data->tulang . ' Kg';
                    } else {
                        return '0 Kg';
                    }
                })
                ->addColumn('ka', function ($data) {
                    if ($data->kalori != NULL) {
                        return $data->kalori . ' kkal';
                    } else {
                        return '0 kkal';
                    }
                })
                ->addColumn('ti', function ($data) {
                    return $data->Karyawan->kesehatan_awal->tinggi . ' Cm';
                })
                ->addColumn('bmi', function ($data) {
                    return  $data->berat / (($data->Karyawan->kesehatan_awal->tinggi / 100) * ($data->Karyawan->kesehatan_awal->tinggi / 100));
                })
                ->make(true);
        } else if ($filter == 'divisi' && $filter_bulanan == 'berat') {
            $data = berat_karyawan::with(['Karyawan.Divisi'])->wherehas('karyawan', function ($divisi) use ($id) {
                $divisi->where('divisi_id', $id);
            })
                ->orderBy('tgl_cek', 'DESC')
                ->whereBetween('tgl_cek', [$start, $end]);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('x', function ($data) {
                    return $data->Karyawan->Divisi->nama;
                })
                ->addColumn('y', function ($data) {
                    return $data->Karyawan->nama;
                })
                ->addColumn('z', function ($data) {
                    return $data->berat . ' Kg';
                })
                ->addColumn('l', function ($data) {
                    if ($data->lemak != NULL) {
                        return $data->lemak . ' gram';
                    } else {
                        return '0 gram';
                    }
                })
                ->addColumn('k', function ($data) {
                    if ($data->kandungan_air != NULL) {
                        return $data->kandungan_air . ' %';
                    } else {
                        return '0 %';
                    }
                })
                ->addColumn('o', function ($data) {
                    if ($data->otot != NULL) {
                        return $data->otot . ' Kg';
                    } else {
                        return '0 Kg';
                    }
                })
                ->addColumn('t', function ($data) {
                    if ($data->tulang != NULL) {
                        return $data->tulang . ' Kg';
                    } else {
                        return '0 Kg';
                    }
                })
                ->addColumn('ka', function ($data) {
                    if ($data->kalori != NULL) {
                        return $data->kalori . ' kkal';
                    } else {
                        return '0 kkal';
                    }
                })
                ->addColumn('ti', function ($data) {
                    return $data->Karyawan->kesehatan_awal->tinggi . ' Cm';
                })
                ->addColumn('bmi', function ($data) {
                    return  $data->berat / (($data->Karyawan->kesehatan_awal->tinggi / 100) * ($data->Karyawan->Kesehatan_awal->tinggi / 100));
                })
                ->make(true);
        } else if ($filter == 'x' && $filter_bulanan = 'y') {
            $data = gcu_karyawan::with('Karyawan')
                ->orderBy('tgl_cek', 'DESC')
                ->where('karyawan_id', 0);

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('x', function ($data) {
                    return $data->Karyawan->nama;
                })
                ->addColumn('glu', function ($data) {
                    if ($data->glukosa != NULL) {
                        return $data->glukosa;
                    } else {
                        return '0 %';
                    }
                })
                ->addColumn('kol', function ($data) {
                    if ($data->kolesterol != NULL) {
                        return $data->kolesterol;
                    } else {
                        return '0 %';
                    }
                })
                ->addColumn('asam', function ($data) {
                    if ($data->asam_urat != NULL) {
                        return $data->asam_urat;
                    } else {
                        return '0 %';
                    }
                })
                ->make(true);
        }
    }
    public function laporan_tahunan()
    {
        $karyawan = Karyawan::orderBy('nama', 'ASC')
            ->has('Kesehatan_awal')
            ->get();
        $divisi = Divisi::all();
        return view('page.kesehatan.laporan_tahunan', ['karyawan' => $karyawan, 'divisi' => $divisi]);
    }
    public function laporan_tahunan_data($filter, $id, $start, $end)
    {
        if ($filter == 'divisi') {
            $data = Kesehatan_tahunan::wherehas('karyawan', function ($divisi) use ($id) {
                $divisi->where('divisi_id', $id);
            })
                ->orderBy('tgl_cek', 'DESC')
                ->whereBetween('tgl_cek', [$start, $end]);
        } else if ($filter == 'karyawan') {
            $data = kesehatan_tahunan::with('Karyawan.Divisi')
                ->orderBy('tgl_cek', 'DESC')
                ->where('karyawan_id', $id)
                ->whereBetween('tgl_cek', [$start, $end]);
        } else {
            $data = kesehatan_tahunan::with('Karyawan.Divisi')
                ->orderBy('tgl_cek', 'DESC')
                ->where('karyawan_id', 0);
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('x', function ($data) {
                return $data->Karyawan->divisi->nama;
            })
            ->addColumn('y', function ($data) {
                return $data->Karyawan->nama;
            })
            ->addColumn('z', function ($data) {
                return $data->pemeriksa->nama;
            })
            ->make(true);
    }
    public function kesehatan_mingguan_tensi_aksi_ubah(Request $request)
    {
        $id = $request->id;
        $kesehatan_mingguan_tensi = kesehatan_mingguan_tensi::find($id);
        $kesehatan_mingguan_tensi->sistolik = $request->sistolik;
        $kesehatan_mingguan_tensi->diastolik = $request->diastolik;
        $kesehatan_mingguan_tensi->keterangan = $request->catatan;
        $kesehatan_mingguan_tensi->save();

        if ($kesehatan_mingguan_tensi) {
            return redirect()->back()->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }

    public function kesehatan_mingguan_rapid_aksi_ubah(Request $request)
    {
        $id = $request->id;
        $kesehatan_mingguan_rapid = kesehatan_mingguan_rapid::find($id);
        $kesehatan_mingguan_rapid->hasil = $request->hasil;
        $kesehatan_mingguan_rapid->jenis = $request->jenis;
        $kesehatan_mingguan_rapid->keterangan = $request->catatan;
        $kesehatan_mingguan_rapid->save();

        if ($kesehatan_mingguan_rapid) {
            return redirect()->back()->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }

    public function kesehatan_vaksin_aksi_tambah(Request $request)
    {

        for ($i = 0; $i < count($request->dosis); $i++) {
            $vaksin_karyawan = Vaksin_karyawan::create([
                'karyawan_id' => $request->fk_karyawan_id,
                'tgl' => $request->date[$i],
                'dosis' => $request->dosis[$i],
                'tahap' => $request->ket[$i],
            ]);
        }


        if ($vaksin_karyawan) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di tam']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }



    public function kesehatan_riwayat_penyakit_aksi_tambah(Request $request)
    {


        $this->validate(
            $request,
            [
                'jenis.*' => 'required',
                'nama.*' => 'required',
                'kriteria.*' => 'required',
            ],
            [
                'jenis.required' => 'Jenis penyakit harus di isi',
                'nama.required' => 'Nama penyakit harus di isi',
                'kriteria.required' => 'Kriteria harus di pilih',
            ]
        );
        for ($i = 0; $i < count($request->nama); $i++) {
            $riwayat_penyakit = riwayat_penyakit::create([
                'karyawan_id' => $request->fk_karyawan_id,
                'nama' => $request->nama[$i],
                'jenis' => $request->jenis[$i],
                'kriteria' => $request->kriteria[$i],
                'keterangan' => $request->keterangan[$i],
            ]);
        }

        if ($riwayat_penyakit) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di hapus']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }

    public function kesehatan_vaksin_aksi_hapus(Request $request)
    {
        $b = Vaksin_karyawan::find($request->id);
        $delete = $b->delete();
        if ($delete) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di hapus']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }

    public function kesehatan_riwayat_penyakit_aksi_hapus(Request $request)
    {
        $b = Riwayat_penyakit::find($request->id);
        $delete = $b->delete();
        if ($delete) {
            return response()->json(['data' => 'success', 'msg' => 'Data berhasil di hapus']);
        } else {
            return response()->json(['data' => 'error', 'msg' => 'Hapus Gagal, periksa kembali']);
        }
    }

    public function chart_vaksin()
    {
        $data = array();

        $tahap_1 = 0;
        $tahap_2 = 0;
        $tahap_3 = 0;
        $tahap_4 = 0;

        $karyawan = Karyawan::with('Vaksin_karyawan')->where('is_aktif',1)->get();

        // $vaksin = Vaksin_karyawan::all();
        // foreach ($karyawan as $k) {
        //     if ($k->Vaksin_karyawan->last()) {

        //         if ($k->Vaksin_karyawan->last()->tahap == 1) {
        //             $data['tahap_1'] = $tahap_1++;
        //         }
        //         if ($k->Vaksin_karyawan->last()->tahap == 2) {
        //             $data['tahap_2'] = $tahap_2++;
        //         }
        //         if ($k->Vaksin_karyawan->last()->tahap == 3) {
        //             $data['tahap_3'] = $tahap_3++;
        //         }
        //         if ($k->Vaksin_karyawan->last()->tahap == 4) {
        //             $data['tahap_4'] = $tahap_4++;
        //         }
        //     }
        // }

        $tahapCounters = [
            'tahap_1' => 0,
            'tahap_2' => 0,
            'tahap_3' => 0,
            'tahap_4' => 0,

        ];
        $belum = Karyawan::doesntHave('Vaksin_karyawan')->where('is_aktif',1)->count();


        // Loop through the karyawan records
        foreach ($karyawan as $k) {
            // Get the last Vaksin_karyawan record for each Karyawan
            $lastVaksin = $k->Vaksin_karyawan->max('tahap');

            // Check if a last Vaksin_karyawan record exists and increment the corresponding tahap counter
            if ($lastVaksin) {
                $tahap = 'tahap_' . $lastVaksin;
                $tahapCounters[$tahap]++;
            }
        }
        return response()->json([
        'tahap_1' => $tahapCounters['tahap_1'],
        'tahap_2' => $tahapCounters['tahap_2'],
        'tahap_3' => $tahapCounters['tahap_3'],
        'tahap_4' => $tahapCounters['tahap_4'],
        'belum' => $belum
    ]);
    }


    public function riwayat_penyakit_data(Request $request)
    {
        $riwayat_penyakit = Riwayat_penyakit::where('nama', 'LIKE', '%' . $request->term . '%')->groupby('nama')->get();
        //   $data = array();
        //   foreach($riwayat_penyakit as $r){
        //     $data[] = $r->nama;
        //   }
        return json_encode($riwayat_penyakit);
    }

    public function riwayat_analisa_data(Request $request)
    {
        $data = Karyawan_sakit::where('analisa', 'LIKE', '%' . $request->term . '%')->groupby('analisa')->get();
        //   $data = array();
        //   foreach($riwayat_penyakit as $r){
        //     $data[] = $r->nama;
        //   }
        return json_encode($data);
    }


    public function chart_absen()
    {
        $tahun = Carbon::now()->format('Y');
        $tgl_awal = $tahun . "-01-01";
        $tgl_akhir = $tahun . "-12-31";

        $ijin_data = Karyawan_masuk::whereBetween('tgl_cek', [$tgl_awal, $tgl_akhir])
            ->where('alasan', 'Ijin')
            ->select('tgl_cek')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_cek)->format('m');
            });

        $cuti_data = Karyawan_masuk::whereBetween('tgl_cek', [$tgl_awal, $tgl_akhir])
            ->where('alasan', 'Cuti')
            ->select('tgl_cek')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_cek)->format('m');
            });

        $sakit_data = Karyawan_masuk::whereBetween('tgl_cek', [$tgl_awal, $tgl_akhir])
            ->where('alasan', 'Sakit')
            ->select('tgl_cek')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_cek)->format('m');
            });

        $data = [];
        foreach ($ijin_data as $key => $value) {
            $ijin_count[(int)  $key] = count($value);
        }
        foreach ($cuti_data as $key => $value) {
            $cuti_count[(int)  $key] = count($value);
        }
        foreach ($sakit_data as $key => $value) {
            $sakit_count[(int)  $key] = count($value);
        }


        for ($i = 1; $i <= 12; $i++) {
            if (!empty($ijin_count[$i])) {
                $data[$i]['ijin'] = $ijin_count[$i];
            } else {
                $data[$i]['ijin'] = 0;
            }
            if (!empty($cuti_count[$i])) {
                $data[$i]['cuti'] = $cuti_count[$i];
            } else {
                $data[$i]['cuti'] = 0;
            }
            if (!empty($sakit_count[$i])) {
                $data[$i]['sakit'] = $sakit_count[$i];
            } else {
                $data[$i]['sakit'] = 0;
            }
            $data[$i];
        }

        return response()->json($data);
    }

    public function penyakit_top($id)
    {

        $tahun = Carbon::now()->format('Y');
        $data =   Karyawan_sakit::select('diagnosa', DB::raw('count(*) as jumlah'))
            ->groupBy('diagnosa')
            ->where('diagnosa', '!=', 'null')
            ->whereMonth('tgl_cek', $id)
            ->whereYear('tgl_cek',  $tahun)
            ->orderBy('jumlah', 'DESC')
            ->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah . ' pegawai';
            })
            ->addColumn('detail', function ($data) {

                return  '<button class="btn btn-outline-primary btn-sm"  id="karyawan_diagnosa_modal" type="button"><i
                     class="fas fa-eye"></i> Detail</button>
                </a>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }

    public function obat_top_detail($month, $year, $data_obat)
    {
        $tahun = Carbon::now()->format('Y');
        $detail_obat = Detail_obat::whereHas('Karyawan_sakit', function ($q) use ($month, $tahun) {
            $q->whereMonth('tgl_cek', $month);
            $q->whereYear('tgl_cek',  $tahun);
        })
            ->where('obat_id', $data_obat)
            ->get();
        $obat = Obat::find($data_obat);


        foreach ($detail_obat as $key => $d) {
            $data[$key]['nama'] = $d->karyawan_sakit->Karyawan->nama;
            $data[$key]['tgl_cek'] = $d->karyawan_sakit->tgl_cek;
            $data[$key]['diagnosa'] = $d->karyawan_sakit->diagnosa;
        }

        $header = date("F", mktime(0, 0, 0, $month, 1));
        return response()->json([
            'header' => array(
                'bulan' => $header . ' ' .  $year,
                'jumlah' => count($detail_obat),
                'nama' => $obat->nama
            ),
            'data' => $data
        ]);
    }
    public function obat_top($id)
    {
        $tahun = Carbon::now()->format('Y');
        $data =   Detail_obat::select('obats.id as obat_id', 'obats.nama', DB::raw('count(*) as jumlah'))
            ->leftJoin('obats', 'obats.id', '=', 'detail_obats.obat_id')
            ->leftJoin('karyawan_sakits', 'karyawan_sakits.id', '=', 'detail_obats.karyawan_sakit_id')
            ->groupBy('obat_id')
            ->whereMonth('tgl_cek', $id)
            ->whereYear('tgl_cek',  $tahun)
            ->orderBy('jumlah', 'DESC')
            ->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah . ' pcs';
            })
            ->addColumn('detail', function ($data) {

                return  '<button class="btn btn-outline-primary btn-sm"  id="karyawan_obat_modal" type="button"><i
                     class="fas fa-eye"></i> Detail</button>
                </a>';
            })
            ->rawColumns(['detail'])
            ->make(true);

        // return response()->json($data);
    }



    public function penyakit_top_detail($month, $year, $data_sakit)
    {
        $tahun = Carbon::now()->format('Y');
        $karyawan_sakit =   Karyawan_sakit::with(['Detail_obat.Obat', 'Karyawan'])
            // ->leftJoin('karyawans', 'karyawans.id', '=', 'karyawan_sakits.karyawan_id')
            // ->leftJoin('detail_obats', 'detail_obats.karyawan_sakit_id', '=', 'karyawan_sakits.id')
            // ->leftJoin('obats', 'obats.id', '=', 'detail_obats.obat_id')
            ->where('diagnosa', $data_sakit)
            ->whereMonth('tgl_cek', $month)
            ->whereYear('tgl_cek',  $tahun)
            //  ->groupBy('detail_obats.karyawan_sakit_id')
            ->get();

        foreach ($karyawan_sakit as $key => $k) {
            $data[$key]['nama'] = $k->Karyawan->nama;
            $data[$key]['tgl_cek'] = $k->tgl_cek;
            $data[$key]['nama_obat'] = '';
            if (count($k->Detail_obat) > 0) {
                $x = array();
                foreach ($k->Detail_obat as $o) {
                    $x[] = $o->Obat->nama;
                }


                $data[$key]['nama_obat'] =  implode(', ', $x);
            }
        }
        $header = date("F", mktime(0, 0, 0, $month, 1));
        return response()->json([
            'header' => array(
                'bulan' => $header . ' ' . $year,
                'jumlah' => count($data),
                'nama' => $data_sakit
            ),
            'data' => $data
        ]);
    }
    public function person_top_detail($month, $year, $data_sakit)
    {
        $tahun = Carbon::now()->format('Y');
        $karyawan_sakit = Karyawan_sakit::with(['Detail_obat.Obat'])
            ->where('karyawan_id', $data_sakit)
            ->whereMonth('tgl_cek', $month)
            ->whereYear('tgl_cek',  $tahun)
            ->get();
        $karyawan = Karyawan::find($data_sakit);

        foreach ($karyawan_sakit as $key => $k) {
            $data[$key]['tgl_cek'] = $k->tgl_cek;
            $data[$key]['diagnosa'] = $k->diagnosa;
            $data[$key]['tindakan'] = $k->tindakan;
            $data[$key]['nama_obat'] = '';
            if (count($k->Detail_obat) > 0) {
                $x = array();
                foreach ($k->Detail_obat as $o) {
                    $x[] = $o->Obat->nama;
                }


                $data[$key]['nama_obat'] =  implode(', ', $x);
            }
        }

        $header = date("F", mktime(0, 0, 0, $month, 1));
        return response()->json([
            'header' => array(
                'bulan' => $header . ' ' . $year,
                'jumlah' => count($data),
                'nama' => $karyawan->nama
            ),
            'data' => $data
        ]);
    }
    public function person_top($id)
    {
        $tahun = Carbon::now()->format('Y');
        $data =   Karyawan_sakit::select('karyawans.id as karyawan_id', 'karyawans.nama', DB::raw('count(*) as jumlah'))
            ->leftJoin('karyawans', 'karyawans.id', '=', 'karyawan_sakits.karyawan_id')
            ->groupBy('karyawan_id')
            ->whereMonth('tgl_cek', $id)
            ->whereYear('tgl_cek', $tahun)
            ->orderBy('jumlah', 'DESC')
            ->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('detail', function ($data) {

                return  '<button class="btn btn-outline-primary btn-sm"  id="karyawan_sakit_modal" type="button"><i
                     class="fas fa-eye"></i> Detail</button>
                </a>';
            })
            ->rawColumns(['detail'])
            ->make(true);
        // return response()->json($data);
    }
}
