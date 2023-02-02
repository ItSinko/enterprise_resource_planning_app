@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Detail Alat Uji</h1>
@stop

@section('content')
    <style>
        .ui-autocomplete-input {
        z-index: 1511;
        }
        .ui-autocomplete {
        z-index: 1510 !important;
        overflow-y: scroll;
        overflow-x: hidden;
        max-height: 350px;
        }
        .ui-menu-item > a.ui-corner-all {
            display: block;
            padding: 3px 15px;
            clear: both;
            font-weight: normal;
            line-height: 18px;
            color: #555555;
            white-space: nowrap;
            text-decoration: none;
        }
        .ui-state-hover, .ui-state-active {
            color: #ffffff;
            text-decoration: none;
            background-color: #0088cc;
            border-radius: 0px;
            -webkit-border-radius: 0px;
            -moz-border-radius: 0px;
            background-image: none;
        }
        #modalIns{
            width: 500px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/barcodes/JsBarcode.code128.min.js"></script>
    <!-- <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet"> -->
    <!-- <script src = "https://code.jquery.com/jquery-1.10.2.js"></script> -->
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <div class="container-fluid">

        <!-- Detail Container -->
        <div class="container bg-light p-3">
            <div class="row m-3">

                <!-- gambar -->
                <div class="col-4">
                    <object data="{{ asset('/storage/gambar/'.$data->gbr_alatuji) }}" type="image/png" class="img-fluid text-center">
                        <img src="{{ asset('/storage/gambar/default.png') }}" class="img-fluid text-center" alt="gambar alat uji">
                    </object>
                </div>
                <!-- detail -->
                <div class="col-8">
                    <h1><strong>{{ $data->kode_alat }}</strong></h1>
                    <h5><strong>{{ $data->nm_alatuji }}</strong></h5>
                    <p class="mb-0 mt-3">Klasifikasi</p>
                    <span><strong>{{ $data->nama_klasifikasi }}</strong></span>
                    <p class="mb-0 mt-3">Fungsi</p>
                    <span><strong>{{ $data->desk_alatuji }}</strong></span>
                    <br/>
                    @if(auth()->user()->role == 1)
                    <a href="{{route('alatuji.edit', ['id' => $id])}}" class="btn btn-warning btn-sm mt-3 w-25"><strong>Ubah</strong></a>
                    @endif
                </div>
            </div>

            <!-- informasi & riwayat -->
            <div class="m-3">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" data-tabs="tabs" id="tabRiwayat">
                        <li class="nav-item">
                            <a href="#informasi" class="nav-link active" aria-current="true" data-toggle="tab">Informasi</a>
                        </li>
                        <li class="nav-item">
                            <a href="#peminjaman" class="nav-link" data-toggle="tab">Peminjaman</a>
                        </li>
                        @if(auth()->user()->role == 1)
                        <li class="nav-item">
                            <a href="#perawatan" class="nav-link" data-toggle="tab">Perawatan</a>
                        </li>
                        <li class="nav-item">
                            <a href="#verifikasi" class="nav-link" data-toggle="tab">Verifikasi</a>
                        </li>
                        <li class="nav-item">
                            <a href="#kalibrasi" class="nav-link" data-toggle="tab">Kalibrasi</a>
                        </li>
                        <li class="nav-item">
                            <a href="#perbaikan" class="nav-link" data-toggle="tab">Perbaikan</a>
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">

                        <!-- tab informasi -->
                        <div class="tab-pane fade show active" id="informasi">
                            <div class="row">
                                <!-- detail alat -->
                                <div class="col">
                                    <h5><strong>Detail</strong></h5>
                                    <table class="table table-striped table-sm">
                                        <tr>
                                            <th class="text-end">Merk</th>
                                            <td class="text-start">{{ $data->nama_merk }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Serial Number</th>
                                            <td class="text-start">{{ $data->serial_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Tipe</th>
                                            <td class="text-start">{{ $data->tipe }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Tanggal Masuk</th>
                                            <td class="text-start">{{ $data->tgl_masuk }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Lokasi</th>
                                            <td class="text-start">{{ $data->lokasi }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Kondisi</th>
                                            <td class="text-start">{!! $data->kondisi !!}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Pengguna Terakhir</th>
                                            <td class="text-start">{{ $last_user }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Waktu Penggunaan</th>
                                            <td class="text-start">{{ $data->total_waktu }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Total Penggunaan</th>
                                            <td class="text-start">{{ $data->total_penggunaan }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- dokumen alat -->
                                <div class="col">
                                    <h5><strong>Dokumen</strong></h5>
                                    <table class="table table-striped table-sm text-start">
                                        <tr>
                                            <td>SOP</td>
                                            <td><strong>{!! $data->sop_alatuji !!}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Manual Book</td>
                                            <td><strong>{!! $data->manual_alatuji !!}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Sertifikasi Kalibrasi</td>
                                            <td class=""><strong>{!! $data->sert_kalibrasi !!}</strong></td>
                                        </tr>
                                    </table>

                                    <!-- barcode & QR code -->
                                    <div class="row">
                                        <!-- barcode -->
                                        <div class="col">
                                            <div class="row"><span class="text-center">Barcode</span></div>
                                            <div class="row">
                                                <svg id="barcode"></svg>
                                            </div>
                                        </div>

                                        <!-- QR -->
                                        <!-- <div class="col">
                                            QR Code
                                            <svg id="qrcode"></svg>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- tab informasi end -->

                        <!-- tab peminjaman -->
                        <div class="tab-pane fade" id="peminjaman">
                            <!-- tombol pinjam -->
                            <div class="row mb-3">
                                <div class="col">
                                    <button class="btn btn-primary btn-sm float-right px-3"  data-toggle="modal" data-target="#pinjamModal" {{ $data->status_pinjam_id == 16 ? '' : 'DISABLED'}}>
                                        <strong>+ Pinjam</strong>
                                    </button>
                                </div>
                            </div>

                            <!-- tabel riwayat pinjam -->
                            <div class="table-responsive">
                            <table class="table table-sm text-center w-100" id="tabelPinjam">
                                <thead class="bc-grey">
                                    <tr class="">
                                        <th colspan="3" class="border-top-0 border-bottom-0 border-end"></th>
                                        <th colspan="3" class="border-top-0 border-bottom-0 border-start border-end">Peminjaman</th>
                                        <th colspan="3" class="border-top-0 border-bottom-0 border-start border-end">Pengembalian</th>
                                        <th class="border-top-0 border-bottom-0 border-start"></th>
                                    </tr>
                                    <tr class="border-top-0 border-bottom-0">
                                        <th class="border-top-0 border-bottom-0">No</th>
                                        <th class="border-top-0 border-bottom-0">Peminjam</th>
                                        <th class="border-top-0 border-bottom-0 border-end">Keterangan</th>
                                        <th class="border-top-0 border-bottom-0 border-start">Kondisi</th>
                                        <th class="border-top-0 border-bottom-0">Pengajuan</th>
                                        <th class="border-top-0 border-bottom-0 border-end">Peminjaman</th>
                                        <th class="border-top-0 border-bottom-0 border-start">Kondisi</th>
                                        <th class="border-top-0 border-bottom-0">Batas</th>
                                        <th class="border-top-0 border-bottom-0 border-end">Kembali</th>
                                        <th class="border-top-0 border-bottom-0 border-start">Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- <tr>
                                        <td>1</td>
                                        <td>nama</td>
                                        <td>pinjam</td>
                                        <td class="border-start"></td>
                                        <td>tanggal</td>
                                        <td class="border-end">tanggal</td>
                                        <td></td>
                                        <td>tanggal</td>
                                        <td class="border-end">tanggal</td>
                                        <td>
                                            <span class="badge w-100 bg-warning">
                                                <span class="text-dark">Konfirmasi</span>
                                            </span>
                                            <span class="badge w-100 bg-success">
                                                <span class="txt-success">Terima Barang</span>
                                            </span>
                                            <span class="badge w-100 bc-success">
                                                <span class="text-success">Selesai</span>
                                            </span>
                                        </td>
                                    </tr> -->

                                </tbody>
                            </table>
                            </div>

                        </div>
                        <!-- tab peminjaman end -->

                        @if(auth()->user()->role == 1)
                        <!-- tab perawatan -->
                        <div class="tab-pane fade" id="perawatan">
                            <!-- tombol perawatan -->
                            <div class="row mb-3">
                                <div class="col">
                                    <a href="{{route('alatuji.perawatan.detail', ['id' => $id])}}" class="btn btn-primary btn-sm float-right px-3"><strong>+ Perawatan</strong></a>
                                </div>
                            </div>

                            <!-- tabel riwayat perawatan -->
                            <div class="table-responsive">
                            <table class="table table-sm border text-center w-100" id="tabelPerawatan">
                                <thead class="bc-grey">
                                    <tr class="">
                                        <th colspan="6" class="border-top-0 border-bottom-0 border-end"></th>
                                        <th colspan="2" class="border-top-0 border-bottom-0 border-start border-end">Hasil</th>
                                        <th colspan="2" class="border-top-0 border-bottom-0 border-start"></th>
                                    </tr>
                                    <tr>
                                        <th class="border-top-0 border-bottom-0">No</th>
                                        <th class="border-top-0 border-bottom-0">Tgl Perawatan</th>
                                        <th class="border-top-0 border-bottom-0">Tgl Berikutnya</th>
                                        <th class="border-top-0 border-bottom-0">Operator</th>
                                        <th class="border-top-0 border-bottom-0">Cek kelengkapan</th>
                                        <th class="border-top-0 border-bottom-0 border-end">Cek Fungsi</th>
                                        <th class="border-top-0 border-bottom-0 border-start">Fisik</th>
                                        <th class="border-top-0 border-bottom-0 border-end">Alat</th>
                                        <th class="border-top-0 border-bottom-0 border-start">Tindak lanjut</th>
                                        <th class="border-top-0 border-bottom-0">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                        <td>1</td>
                                        <td>01-08-2022</td>
                                        <td>Kristin</td>
                                        <td>cek kelengkapan</td>
                                        <td>ek fungsi alat</td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                            </svg>
                                        </td>
                                        <td>perbaikan</td>
                                        <td>diserahkan</td>
                                    </tr> -->
                                </tbody>
                            </table>
                            </div>

                        </div>
                        <!-- tab perawatan end -->

                        <!-- tab verifikasi -->
                        <div class="tab-pane fade" id="verifikasi">
                            <!-- tombol verifikasi -->
                            <div class="row mb-3">
                                <div class="col">
                                    <a href="{{route('alatuji.verifikasi.detail', ['id' => $id])}}" class="btn btn-primary btn-sm float-right px-3"><strong>+ Verifikasi</strong></a>
                                </div>
                            </div>

                            <!-- tabel riwayat verifikasi -->
                            <div class="table-responsive">
                            <table class="table table-sm border text-center w-100" id="tabelVerifikasi">
                                <thead class="bc-grey">
                                    <tr class="">
                                        <th colspan="5" class="border-top-0 border-bottom-0 border-end"></th>
                                        <th colspan="2" class="border-top-0 border-bottom-0 border-start border-end">Cek</th>
                                        <th colspan="2" class="border-top-0 border-bottom-0 border-start"></th>
                                    </tr>
                                    <tr>
                                        <th class="border-top-0 border-bottom-0">No</th>
                                        <th class="border-top-0 border-bottom-0">Tgl Verifikasi</th>
                                        <th class="border-top-0 border-bottom-0">Tgl Berikutnya</th>
                                        <th class="border-top-0 border-bottom-0">Pelaksanaan Pengandalian</th>
                                        <th class="border-top-0 border-bottom-0 border-end">Keputusan</th>
                                        <th class="border-top-0 border-bottom-0 border-start">Fisik</th>
                                        <th class="border-top-0 border-bottom-0 border-end">Fungsi</th>
                                        <th class="border-top-0 border-bottom-0 border-start">Keterangan</th>
                                        <th class="border-top-0 border-bottom-0">Tindak Lanjut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                        <td>1</td>
                                        <td>01-08-2022</td>
                                        <td>Pelaksanaan Pengendalian</td>
                                        <td>hasil</td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                            </svg>
                                        </td>
                                        <td>Keterangan</td>
                                        <td>Tindak Lanjut</td>
                                    </tr> -->
                                </tbody>
                            </table>
                            </div>

                        </div>
                        <!-- tab verifikasi end -->

                        <!-- tab kalibrasi -->
                        <div class="tab-pane fade" id="kalibrasi">
                            <!-- tombol kalibrasi -->
                            <div class="row mb-3">
                                <div class="col">
                                    <a href="{{route('alatuji.mt.create', ['jenis' => 'Kalibrasi', 'id' => $id])}}" class="btn btn-primary btn-sm float-right px-3"><strong>+ Kalibrasi</strong></a>
                                </div>
                            </div>

                            <!-- tabel riwayat kalibrasi -->
                            <div class="table-responsive">
                            <table class="table table-sm border text-center w-100" id="tableKalibrasi">
                                <thead class="bc-grey">
                                    <tr class="">
                                        <th colspan="4" class="border-top-0 border-bottom-0 border-end"></th>
                                        <th colspan="2" class="border-top-0 border-bottom-0 border start-border-end">Cek</th>
                                        <th colspan="2" class="border-top-0 border-bottom-0 border-start"></th>
                                    </tr>
                                    <tr>
                                        <th class="border-top-0 border-bottom-0">No</th>
                                        <th class="border-top-0 border-bottom-0">Tanggal</th>
                                        <th class="border-top-0 border-bottom-0">Jenis</th>
                                        <th class="border-top-0 border-bottom-0 border-end">Masalah</th>
                                        <th class="border-top-0 border-bottom-0 border-start">Fisik</th>
                                        <th class="border-top-0 border-bottom-0 border-end">Fungsi</th>
                                        <th class="border-top-0 border-bottom-0 border-start">Status</th>
                                        <th class="border-top-0 border-bottom-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                        <td>1</td>
                                        <td>01-08-2022</td>
                                        <td>Internal</td>
                                        <td>masalah</td>
                                        <td>Operator</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <span class="badge w-100 bg-warning">
                                                <span class="text-dark">Proses</span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>01-08-2022</td>
                                        <td>External</td>
                                        <td>masalah</td>
                                        <td>-</td>
                                        <td>Perusahaan</td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-file-earmark-text-fill text-primary" viewBox="0 0 16 16">
                                            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1h-4z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-file-earmark-text-fill text-primary" viewBox="0 0 16 16">
                                            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1h-4z"/>
                                            </svg>
                                        </td>
                                        <td>10.000Rp</td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <span class="badge w-100 bc-success">
                                                <span class="text-success">Selesai</span>
                                            </span>
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                            </div>

                        </div>
                        <!-- tab kalibrasi end -->

                        <!-- tab perbaikan -->
                        <div class="tab-pane fade" id="perbaikan">
                            <!-- tombol perbaikan -->
                            <div class="row mb-3">
                                <div class="col">
                                    <a href="{{route('alatuji.mt.create', ['jenis' => 'Perbaikan', 'id' => $id])}}" class="btn btn-primary btn-sm float-right px-3"><strong>+ Perbaikan</strong></a>
                                </div>
                            </div>

                            <!-- tabel riwayat perbaikan -->
                            <div class="table-responsive">
                            <table class="table table-sm border text-center w-100" id="tablePerbaikan">
                            <thead class="bc-grey">
                                    <tr class="">
                                        <th colspan="4" class="border-top-0 border-bottom-0 border-end"></th>
                                        <th colspan="2" class="border-top-0 border-bottom-0 border start-border-end">Cek</th>
                                        <th colspan="2" class="border-top-0 border-bottom-0 border-start"></th>
                                    </tr>
                                    <tr>
                                        <th class="border-top-0 border-bottom-0">No</th>
                                        <th class="border-top-0 border-bottom-0">Tanggal</th>
                                        <th class="border-top-0 border-bottom-0">Jenis</th>
                                        <th class="border-top-0 border-bottom-0 border-end">Masalah</th>
                                        <th class="border-top-0 border-bottom-0 border-start">Fisik</th>
                                        <th class="border-top-0 border-bottom-0 border-end">Fungsi</th>
                                        <th class="border-top-0 border-bottom-0 border-start">Status</th>
                                        <th class="border-top-0 border-bottom-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                        <td>1</td>
                                        <td>01-08-2022</td>
                                        <td>Internal</td>
                                        <td>masalah</td>
                                        <td>Operator</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <span class="badge w-100 bg-warning">
                                                <span class="text-dark">Proses</span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>01-08-2022</td>
                                        <td>External</td>
                                        <td>masalah</td>
                                        <td>-</td>
                                        <td>Perusahaan</td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-file-earmark-text-fill text-primary" viewBox="0 0 16 16">
                                            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1h-4z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-file-earmark-text-fill text-primary" viewBox="0 0 16 16">
                                            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1h-4z"/>
                                            </svg>
                                        </td>
                                        <td>10.000Rp</td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                            </svg>
                                        </td>
                                        <td>
                                            <span class="badge w-100 bc-success">
                                                <span class="text-success">Selesai</span>
                                            </span>
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                            </div>

                        </div>
                        <!-- tab perbaikan end -->
                        @endif

                    </div>
                </div>
            </div>
            </div>

        </div>
        <!-- Detail Container end -->


        <!-- Modal Pinjam -->
        <div class="modal fade" id="pinjamModal" tabindex="-1" aria-labelledby="pinjamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <h4 class="modal-title" id="pinjamModalLabel">
                    <strong>
                        Ajukan Peminjaman
                    </strong>
                </h4>

                <form action="{{ route('alatuji.pinjam.store') }}" method="post">
                @csrf
                <input type="hidden" name="serial_number_id" value="{{$id}}">

                <div class="row mt-3">
                    <!-- kolom kiri -->
                    <div class="col-4">
                        <div class="card border-primary border-top-w3">
                         <div class="card-body">
                            <h5><strong>Info Peminjman</strong></h5>

                            <p class="mb-0 mt-3">Nama Peminjam</p>
                            <span><strong>{{auth()->user()->nama}}</strong></span>

                            <p class="mb-0 mt-3">Tanggal Pengajuan</p>
                            <span><strong>{{date('d M Y')}}</strong></span>
                         </div>
                        </div>
                    </div>

                    <!-- kolom kanan -->
                    <div class="col-8">
                        <div class="card border-primary border-top-w3">
                        <div class="card-body">

                            <div class="row">
                            @error('tgl_peminjaman')
                                <div class="col mb-0">
                                    <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                                </div>
                            @enderror

                            @error('tgl_pengembalian')
                                <div class="col mb-0">
                                    <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                                </div>
                            @enderror
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="tgl_peminjaman">Tgl Peminjaman</label>
                                        <input type="date" class="form-control" name="tgl_peminjaman" id="tgl_peminjaman" value="{{ old('tgl_peminjaman') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="tgl_pengembalian">Tgl Pengembalian</label>
                                        <input type="date" class="form-control" name="tgl_pengembalian" id="tgl_pengembalian" value="{{ old('tgl_pengembalian') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="pj">Penanggung Jawab</label>
                                        <input type="text" name="pj" id="pj" class="form-control">
                                        <small class="text-muted">Isi jika mewakilkan peminjaman, kosongkan jika tidak.</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                        </div>
                    </div>

                </div>

                <div class="row float-right">
                    <div class="col-auto">
                        <span type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                            Batal
                        </span>
                    </div>
                    <div class="col-auto">
                        <input type="submit" value="Pinjam" class="btn btn-primary">
                    </div>
                </div>
                </form>

            </div>
            </div>
        </div>
        </div>

        <!-- modal konfirmasi peminjaman -->
        <div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-body">

                <h4 class="modal-title">
                    <strong>
                        Konfirmasi Peminjaman
                    </strong>
                </h4>

                <div class="row mt-3">

                    <!-- kolom kiri -->
                    <div class="col-4">
                        <div class="card border-warning border-top-w3">
                        <div class="card-body">
                            <h5><strong>Info Peminjman</strong></h5>

                            <p class="mb-0 mt-3">Nama Peminjam</p>
                            <span><strong id="modal_display_terima_peminjam"></strong></span>

                            <p class="mb-0 mt-3">Tanggal Pengajuan</p>
                            <span><strong id="modal_display_terima_tgl1">01 September 2022</strong></span>

                            <p class="mb-0 mt-3">Tanggal Peminjaman</p>
                            <span><strong id="modal_display_terima_tgl2">01 September 2022</strong></span>

                            <p class="mb-0 mt-3">Tanggal Pengembalian</p>
                            <span><strong id="modal_display_terima_tgl3">01 September 2022</strong></span>
                        </div>
                        </div>
                    </div>
                    <!-- kolom kiri end -->

                    <!-- kolom kanan -->
                    <div class="col-8">

                        <div class="alert bc-warning" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-info-circle-fill text-warning" viewBox="0 0 16 16">
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                            </svg>

                            <strong>
                                Peminjaman Untuk Alat Uji
                            </strong>
                        </div>

                        <form action="{{route('alatuji.pinjam.konfirmasi')}}" method="POST">
                        @csrf
                        <input type="hidden" name="peminjaman_konfirm_id" id="peminjaman_konfirm_id" value="">
                        <input type="hidden" name="alatuji_konfirm_id" id="alatuji_konfirm_id" value="">

                        <!-- card kanan info pinjam -->
                        <div class="card border-warning border-top-w3">
                        <div class="card-body">

                            <div class="row mb-2">
                                <div class="col"><span class="float-right">Status Peminjaman</span></div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status_peminjaman" id="konfirmasi_terima" value="15" {{ old('status_peminjaman') == null ? 'checked' : (old('status_peminjaman') == 15 ? 'checked' : '') }}>
                                                <label class="form-check-label" for="konfirmasi_terima">Terima</label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status_peminjaman" id="konfirmasi_tolak" value="18" {{ old('status_peminjaman') == 18 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="konfirmasi_tolak">Tolak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col"><span class="float-right">Kondisi Barang</span></div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="kondisi_peminjaman" id="konfirmasi_ok" value="9" {{ old('kondisi_peminjaman') == null ? 'checked' : (old('kondisi_peminjaman') == 9 ? 'checked' : '') }}>
                                                <label class="form-check-label" for="konfirmasi_ok">OK</label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="kondisi_peminjaman" id="konformasi_not" value="10" {{ old('kondisi_peminjaman') == 10 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="konformasi_not">NOT OK</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('keterangan_konfirmasi')
                                <div class="col mb-0">
                                    <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                                </div>
                            @enderror

                            <div id="keterangan_konfirmasi_container" style="display:none">
                            <div class="row mb-2">
                                <div class="col"><span class="float-right">Keterangan</span></div>
                                <div class="col">
                                    <textarea class="form-control" name="keterangan_konfirmasi" id="" cols="30" rows="1"></textarea>
                                </div>
                            </div>
                            </div>

                        </div>
                        </div>
                        <!-- card kanan info pinjam end -->

                        <div class="row float-right">
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                    <strong>Batal</strong>
                                </button>
                            </div>
                            <div class="col-auto">
                                <input type="submit" value="Pinjam" class="btn btn-warning" style="font-weight:bold">
                            </div>
                        </div>
                        </form>

                    </div>
                    <!-- kolom kanan end -->

                </div>

            </div>
            </div>
        </div>
        </div>
        <!-- modal konfirmasi pinjam end -->

        <!-- modal terima barang peminjaman -->
        <div class="modal fade" id="terimaModal" tabindex="-1" aria-labelledby="terimaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-body">

                <h4 class="modal-title">
                <div class="alert bc-success" role="alert">
                    <strong>
                        Terima Barang Peminjaman
                    </strong>
                </div>
                </h4>

                <div class="row mt-3">

                    <!-- kolom kiri -->
                    <div class="col-4">
                        <div class="card border-success border-top-w3">
                        <div class="card-body">
                            <h5><strong>Info Peminjman</strong></h5>

                            <p class="mb-0 mt-3">Nama Peminjam</p>
                            <span><strong id="modal_display_kembali_nama"></strong></span>

                            <p class="mb-0 mt-3">Tanggal Peminjaman</p>
                            <span><strong id="modal_display_kembali_tgl1"></strong></span>

                            <p class="mb-0 mt-3">Batas Pengembalian</p>
                            <span><strong id="modal_display_kembali_tgl2"></strong></span>
                        </div>
                        </div>
                    </div>
                    <!-- kolom kiri end -->

                    <!-- kolom kanan -->
                    <div class="col-8">

                        <form action="{{route('alatuji.pinjam.kembali')}}" method="post">
                        @csrf
                        <input type="hidden" name="peminjaman_kembali_id" id="peminjaman_kembali_id" value="">
                        <input type="hidden" name="id_alat_uji" id="id_alat_uji" value="">

                        <!-- card kanan info kembali -->
                        <div class="card border-success border-top-w3">
                        <div class="card-body">

                            @error('tgl_kembali')
                            <div class="row">
                                <div class="col"></div>
                                <div class="col mb-0">
                                    <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                                </div>
                            </div>
                            @enderror

                            <div class="row mb-2">
                                <div class="col"><span class="float-right">Tanggal Pengembalian</span></div>
                                <div class="col">
                                    <input class="form-control" type="date" name="tgl_kembali" id="" value="{{ old('tgl_kembali') }}">
                                </div>
                            </div>

                            @error('jumlah_penggunaan')
                            <div class="row">
                                <div class="col"></div>
                                <div class="col mb-0">
                                    <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                                </div>
                            </div>
                            @enderror

                            <div class="row mb-2">
                                <div class="col"><span class="float-right">Jumlah Penggunaan</span></div>
                                <div class="col">
                                    <input class="form-control" type="number" name="jumlah_penggunaan" id="" value="{{ old('jumlah_penggunaan') }}">
                                </div>
                            </div>

                            @error('waktu_penggunaan')
                            <div class="row">
                                <div class="col"></div>
                                <div class="col mb-0">
                                    <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                                </div>
                            </div>
                            @enderror

                            <div class="row mb-2">
                                <div class="col"><span class="float-right">Waktu Penggunaan</span></div>
                                <div class="col">
                                    <div class="input-group">
                                        <input class="form-control" type="number" name="waktu_penggunaan" id="" value="{{ old('waktu_penggunaan') }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col"><span class="float-right">Kondisi Barang</span></div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="kondisi_kembali" id="kondisiOK" value="9" {{ old('kondisi_kembali') == null ? 'checked' : (old('kondisi_kembali') == 9 ? 'checked' : '') }}>
                                                <label class="form-check-label" for="kondisiOK">OK</label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="kondisi_kembali" id="kondisiNOT" value="10" {{ old('kondisi_kembali') == 10 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="kondisiNOT">NOT OK</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('keterangan_kembali')
                            <div class="row">
                                <div class="col"></div>
                                <div class="col mb-0">
                                    <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                                </div>
                            </div>
                            @enderror

                            <div class="row mb-2">
                                <div class="col"><span class="float-right">Keterangan</span></div>
                                <div class="col">
                                    <textarea class="form-control" name="keterangan_kembali" id="" cols="30" rows="1"></textarea>
                                </div>
                            </div>

                        </div>
                        </div>
                        <!-- card kanan info kembali end -->

                        <div class="row float-right">
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                    <strong>Batal</strong>
                                </button>
                            </div>
                            <div class="col-auto">
                                <input type="submit" value="Terima" class="btn btn-success" style="font-weight:bold">
                            </div>
                        </div>
                        </form>

                    </div>
                    <!-- kolom kanan end -->

                </div>

            </div>
            </div>
        </div>
        </div>
        <!-- modal terima barang end -->

        <!-- modal data kalibrasi perbaikan -->
        <div class="modal fade" id="MTDataModal" tabindex="-1" aria-labelledby="dataMtModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-body" id="MTModalBody">

                <h4 class="modal-title">
                    <strong id="jenis_judul">
                        <!-- judul -->
                    </strong>
                </h4>

                <div class="card">
                    <div class="card-body">

                        <!-- detail alat uji -->
                        <div class="row mb-1">
                            <div class="col text-muted">Nama Alat Uji</div>
                            <div class="col">
                                <strong id="mt_nama_alat"></strong>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col text-muted">NO Seri Alat Uji</div>
                            <div class="col">
                                <strong id="mt_noseri_alat"></strong>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col text-muted">Tanggal pengajuan</div>
                            <div class="col">
                                <strong id="mt_tgl_pengajuan"></strong>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col text-muted">Jenis Perbaikan</div>
                            <div class="col" id="mt_jenis_badge"></div>
                        </div>
                        <!-- detail alat uji end -->

                        <!-- tampilan masalah -->
                        <div class="alert alert-warning p-2" role="alert">
                            <div class="row">
                                <div class="col-3 d-flex justify-content-center">
                                    <i class="fa-solid fa-triangle-exclamation fa-3x"></i>
                                </div>
                                <div class="col-9">
                                    <div class="row">
                                        <strong>Masalah</strong>
                                    </div>
                                    <div class="row">
                                        <span id="mt_masalah"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- tampilan masalah end -->

                        <!-- informasi detail kalibrasi perbaikan -->
                        <div id="mt_data_external_container">
                        <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-toggle="tab" href="#mt_tab_dokumen">Dokumen</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-toggle="tab" href="#mt_tab_hasil">Hasil</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="mt_tab_dokumen" role="tabpanel" >
                                <table class="table table-sm table-striped">
                                    <tbody>
                                    <tr>
                                        <td>Status</td>
                                        <td id="mt_data_status"></td>
                                    </tr>
                                    <tr>
                                        <td>Memo</td>
                                        <td id="mt_data_memo"></td>
                                    </tr>
                                    <tr>
                                        <td>Surat Jalan</td>
                                        <td id="mt_data_suratjalan"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="mt_tab_hasil" role="tabpanel" >
                                <table class="table table-sm table-striped">
                                    <tbody>
                                    <tr>
                                        <td>Tanggal Terima</td>
                                        <td>
                                            <strong id="mt_data_tanggalTerima"></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Perusahaan</td>
                                        <td id="mt_data_perusahaan">
                                            <strong id="mt_data_perusahaan"></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Biaya</td>
                                        <td>
                                            <strong id="mt_data_biaya"></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kondisi Fisik</td>
                                        <td id="mt_data_kondisi_fisik"></td>
                                    </tr>
                                    <tr>
                                        <td>Kondisi Fungsi</td>
                                        <td id="mt_data_kondisi_fungsi"></td>
                                    </tr>
                                    <tr>
                                        <td>Tindak Lanjut</td>
                                        <td>
                                            <strong id="mt_data_tindak_lanjut"></strong>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>

                        <div id="mt_data_internal_container">
                            <h3>Hasil Pemeriksaan</h3>
                            <table class="table table-sm table-striped">
                                <tbody>
                                    <tr>
                                        <td>Status</td>
                                        <td id="mt_data_status2"></td>
                                    </tr>
                                    <tr>
                                        <td>Operator</td>
                                        <td><strong id="mt_data_operator"></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Hasil Fisik</td>
                                        <td><strong id="mt_data_kondisi_fisik2"></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Hasil Fungsi</td>
                                        <td><strong id="mt_data_kondisi_fungsi2"></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Tindak Lanjut</td>
                                        <td><strong id="mt_data_tindak_lanjut2"></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- informasi detail kalibrasi perbaikan end -->

                    </div>
                </div>

            </div>
            </div>
        </div>
        </div>
        <!-- modal data kalibrasi perbaikan end -->

        <div class="modal fade" id="gambarModal" tabindex="-1" aria-labelledby="gambarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-body" id="gambarModalBody">
                <!-- gambar -->
                <img src="" class="img-fluid" alt="gambar dokumen kalibrasi" id="gambarDokumen">
            </div>
            </div>
        </div>
        </div>


    </div>
@stop
@section('adminlte_js')
    <script>

    $(document).ready(function () {

        // auto complete penaggung jawab
        $(function() {
            $.ajax({
                type:'GET',
                url:'{{ url("/api/inventory/get_data_pj/peminjaman") }}',
                success:function(data) {
                    autoComp(data);
                }
            });
        });

        function autoComp(data){
            //hapus value jika ada yang null
            for(let i = 0; i<data.length;i++){
                if ( data[i] == null) {
                    data.splice(i, 1);
                }
            }
            console.log(data);
            $('#pj').autocomplete({
                source: data,
                autofocus: true,
            });
        }
        // auto complete penanggung jawab end

        @if($data->barcode != null)
        JsBarcode("#barcode", "{{$data->barcode}}");
        @endif

        // enable bootstrap tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // data tables riwayat peminjaman
        var tablePeminjaman = $('#tabelPinjam').DataTable({
            processing: true,
            serverSide: false,
            destroy: false,
            ajax: "{{ url('/api/inventory/peminjaman_hist') }}"+'/'+"{{$id}}"+'/'+"{{auth()->user()->role}}"+'/'+"{{auth()->user()->id}}",
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'nama', name: 'nama'},
                {data: 'ket_tambahan', name: 'ket_tambahan'},
                {data: 'kondisi_awal', name: 'kondisi_awal'},
                {data: 'created_at', name: 'tgl_pengajuan'},
                {data: 'tgl_pinjam', name: 'tgl_pinjam'},
                {data: 'kondisi_akhir', name: 'kondisi_akhir'},
                {data: 'tgl_batas', name: 'tgl_batas'},
                {data: 'tgl_kembali', name: 'tgl_kembali'},
                {data: 'status_id', name: 'status'}
            ],
            order:[[0, 'desc']],
        });

        // data tabel riwayat perawatan
        var tablePerawatan = $('#tabelPerawatan').DataTable({
            processing: true,
            serverSide: false,
            destroy: false,
            ajax: "{{ url('/api/inventory/perawatan_hist') }}"+'/'+"{{$id}}",
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'tgl_perawatan', name: 'tgl_perawatan'},
                {data: 'jadwal_perawatan', name: 'jadwal_perawatan'},
                {data: 'penanggung_jawab', name: 'operator'},
                {data: 'kelengkapan', name: 'kelengkapan'},
                {data: 'fungsi', name: 'fungsi'},
                {data: 'hasil_fisik', name: 'hasil_fisik'},
                {data: 'hasil_fungsi', name: 'hasil_fungsi'},
                {data: 'tindak_lanjut', name: 'tindak_lanjut'},
                {data: 'keterangan', name: 'keterangan'},
            ],
            order:[[0, 'desc']],
        });

        // data tabel riwayat verifikasi
        var tableVerifikasi = $('#tabelVerifikasi').DataTable({
            processing: true,
            serverSide: false,
            destroy: false,
            ajax: "{{url('/api/verifikasi/verifikasi_hist')}}"+'/'+"{{$id}}",
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'tgl_perawatan', name: 'tanggal'},
                {data: 'jadwal_perawatan', name: 'berikutnya'},
                {data: 'pengendalian', name: 'pengendalian'},
                {data: 'keputusan', name: 'Keputusan'},
                {data: 'hasil_fisik', name: 'cek_fisik'},
                {data: 'hasil_fungsi', name: 'cek_fungsi'},
                {data: 'keterangan', name: 'keterangan'},
                {data: 'tindak_lanjut', name: 'tindak_lanjut'},
            ],
            order:[[0, 'desc']],
        });

        function get_data_kalibrasi_perawatan(id, j, tbl){
            // karena fungsi di panggil 2 kali, di bagian tabel kalibrasi & perbaikan-
            // -jadi setiap click detail akan tembak fungsi 2 kali-
            // -maka ada pengecekan untuk mencegah elemen di append 2 kali
            if(j != tbl){
                return false;
            }
            $.ajax({
                type:'GET',
                url:"{{url('/api/kalibrasiperbaikan/data_show/')}}"+'/'+id+'/'+j,
                success:function(data) {
                    $('#MTDataModal').modal('show');
                    data = jQuery.parseJSON(data);

                    //cek jenis perbaikan internal / eksternal
                    if(data.jenis_id == 'internal'){
                        $("#mt_data_external_container").hide();
                        $("#mt_data_internal_container").show();
                    }else{
                        $("#mt_data_external_container").show();
                        $("#mt_data_internal_container").hide();
                    }

                    $('#jenis_judul').text(j);
                    $('#mt_nama_alat').text(data.nm_alatuji);
                    $('#mt_noseri_alat').text(data.serial_number);
                    $('#mt_tgl_pengajuan').text(data.tgl_kirim);
                    $('#mt_masalah').text(data.masalah);
                    $('#mt_data_memo').attr("href", data.memo);
                    $('#mt_data_suratjalan').attr("href", data.surat_jalan);
                    $('#mt_data_tanggalTerima').text(data.tgl_terima);
                    $('#mt_data_perusahaan').text(data.perusahaan);
                    $('#mt_data_biaya').text(data.biaya);
                    $('#mt_data_tindak_lanjut').text(data.tindak_lanjut);
                    $('#mt_data_operator').text(data.nama);
                    $('#mt_data_tindak_lanjut2').text(data.tindak_lanjut);

                    if(data.status_id == 12){
                        $('#mt_data_status, #mt_data_status2').append(
                            '<span class="badge px-3 bc-success" id="mt_temp_status"><span class="text-success">Selesai</span></span>'
                        );
                    }else{
                        $('#mt_data_status, #mt_data_status2').append(
                            //'<span class="badge px-3 bc-warning" id="mt_temp_status"><span class="text-dark">Proses</span></span>'
                            '<a href="/lab/alatuji/mt/konfirmasi/'+j+'/'+id+'" id="mt_temp_status" class="badge px-3 bg-warning"><span class="text-dark">Proses</span></a>'
                        );
                    }

                        if(data.hasil_fisik == 9){
                            $('#mt_data_kondisi_fisik, #mt_data_kondisi_fisik2').append(
                                '<span id="mt_temp_cek_fisik" data-toggle="tooltip" data-placement="top" title="Alat Dapat Di Gunakan"><i class="fa-solid fa-circle-check text-success"></i></span>'
                            );
                        }else if(data.hasil_fisik == 10){
                            $('#mt_data_kondisi_fisik, #mt_data_kondisi_fisik2').append(
                                '<span id="mt_temp_cek_fisik" data-toggle="tooltip" data-placement="top" title="Alat Tidak Dapat Di Gunakan"><i class="fa-solid fa-circle-xmark text-danger"></i></span>'
                            );
                        }else{
                            $('#mt_data_kondisi_fisik, #mt_data_kondisi_fisik2').append('<span id="mt_temp_cek_fungsi">-</span>');
                        }

                        if(data.hasil_fungsi == 9){
                            $('#mt_data_kondisi_fungsi, #mt_data_kondisi_fungsi2').append(
                                '<span id="mt_temp_cek_fungsi" data-toggle="tooltip" data-placement="top" title="Alat Dapat Di Gunakan"><i class="fa-solid fa-circle-check text-success"></i></span>'
                            );
                        }else if(data.hasil_fungsi == 10){
                            $('#mt_data_kondisi_fungsi, #mt_data_kondisi_fungsi2').append(
                                '<span id="mt_temp_cek_fungsi" data-toggle="tooltip" data-placement="top" title="Alat Tidak Dapat Di Gunakan"><i class="fa-solid fa-circle-xmark text-danger"></i></span>'
                            );
                        }else{
                            $('#mt_data_kondisi_fungsi, #mt_data_kondisi_fungsi2').append('<span id="mt_temp_cek_fungsi">-</span>');
                        }

                        if(data.jenis_id == 'internal'){
                            $('#mt_jenis_badge').append(
                                '<span class="badge w-100 bc-primary" id="mt_temp_jenis"><span class="text-primary">Internal</span></span>'
                            );
                        }else{
                            $('#mt_jenis_badge').append(
                                '<span class="badge w-100 bc-warning" id="mt_temp_jenis"><span class="text-warning">External</span></span>'
                            );
                        }

                        if(data.surat_jalan != null){
                            $('#mt_data_suratjalan').append(
                                '<span id="mt_temp_surat" style="cursor:pointer" class="text-primary" data-toggle="modal" data-target="#gambarModal"><i class="fa-solid fa-file-lines btn-hover"></i> '+data.surat_jalan+'</span>'
                            );
                            $('#mt_data_suratjalan').click(function(){
                                $('#gambarDokumen').attr('src', '{{url("storage/kalibrasiperbaikan/")}}'+'/'+data.surat_jalan);
                            });
                        }else{
                            $('#mt_data_suratjalan').append(
                                '<span id="mt_temp_surat">-<span>'
                            );
                        }

                        if(data.memo != null){
                            $('#mt_data_memo').append(
                                '<span id="mt_temp_memo" class="text-primary" style="cursor:pointer" data-toggle="modal" data-target="#gambarModal"><i class="fa-solid fa-file-lines btn-hover"></i> '+data.memo+'</span>'
                            );
                            $('#mt_temp_memo').click(function(){
                                $('#gambarDokumen').attr('src', '{{url("storage/kalibrasiperbaikan/")}}'+'/'+data.memo);
                            });
                        }else{
                            $('#mt_data_memo').append(
                                '<span id="mt_temp_memo">-<span>'
                            );
                        }

                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    }
                });
        }

        // data riwayat tabel kalibrasi
        var tableKalibrasi = $('#tableKalibrasi').DataTable({
            processing: true,
            serverSide: true,
            destroy: false,
            ajax: "{{url('/api/kalibrasiperbaikan/maintenance_hist/')}}"+'/'+"{{$id}}"+'/kalibrasi',
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'tgl_kirim', name: 'tgl_kirim'},
                {data: 'jenis_id', name: 'jenis_id'},
                {data: 'masalah', name: 'masalah'},
                {data: 'hasil_fisik', name: 'hasil_fisik'},
                {data: 'hasil_fungsi', name: 'hasil_fungsi'},
                {data: 'status_id', name: 'status_id'},
                {data: 'aksi', name: 'aksi'}
            ],
            order:[[0, 'desc']],
            columnDefs: [{
                'targets': 3,
                'render': function(data, type, full, meta){
                    return data.length > 10 ?
                    data.substr( 0, 10 )+'...' : data;
                }
            }],
            initComplete: function(settings, json) {
                $('.getDataMT').click(function(){
                    let id = $(this).attr('data-id');
                    let j = $(this).attr('data-jenis');
                    let tbl = 'kalibrasi';
                    get_data_kalibrasi_perawatan(id, j, tbl);
                });
            }
        });

        // data riwayat tabel perbaikan
        var tablePerbaikan = $('#tablePerbaikan').DataTable({
            processing: true,
            serverSide: true,
            destroy: false,
            ajax: "{{url('/api/kalibrasiperbaikan/maintenance_hist/')}}"+'/'+"{{$id}}"+'/perbaikan',
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'tgl_kirim', name: 'tgl_kirim'},
                {data: 'jenis_id', name: 'jenis_id'},
                {data: 'masalah', name: 'masalah'},
                {data: 'hasil_fisik', name: 'hasil_fisik'},
                {data: 'hasil_fungsi', name: 'hasil_fungsi'},
                {data: 'status_id', name: 'status_id'},
                {data: 'aksi', name: 'aksi'}
            ],
            order:[[0, 'desc']],
            columnDefs: [{
                'targets': 3,
                'render': function(data, type, full, meta){
                    return data.length > 10 ?
                    data.substr( 0, 10 )+'...' : data;
                }
            }],
            initComplete: function(settings, json) {
                $('.getDataMT').click(function(){
                    let id = $(this).attr('data-id');
                    let j = $(this).attr('data-jenis');
                    let tbl = 'perbaikan';
                    get_data_kalibrasi_perawatan(id, j, tbl);
                });
            }
        });

        // ketika modal data kalibrasi perbaikan di tutup
        // hapus elemen yang sudah di append di modal detail kalibrasi perbaikan
        $('#MTDataModal').on('hidden.bs.modal', function () {
            $("#mt_temp_jenis").remove();
            $("#mt_temp_status").remove();// <- karena di panggil 2 kali, di hapus 2 kali
            $("#mt_temp_status").remove();// di panggil di bagian internal & eksternal
            $("#mt_temp_cek_fisik").remove();// <- karena di panggil 2 kali, di hapus 2 kali
            $("#mt_temp_cek_fisik").remove();
            $("#mt_temp_cek_fisik").remove();
            $("#mt_temp_cek_fisik").remove();
            $("#mt_temp_cek_fungsi").remove();// <- karena di panggil 2 kali, di hapus 2 kali
            $("#mt_temp_cek_fungsi").remove();
            $("#mt_temp_cek_fungsi").remove();
            $("#mt_temp_cek_fungsi").remove();
            $("#mt_temp_surat").remove();
            $("#mt_temp_memo").remove();
        });

        // setelah input berhasil
        // pindah ke tabel riwayat verifikasi
        @if(session()->has('pinjamSuccess'))
                $('#tabRiwayat a[href="#peminjaman"]').tab('show');
        @endif
        @if(session()->has('perawatanSuccess'))
                $('#tabRiwayat a[href="#perawatan"]').tab('show');
        @endif
        @if(session()->has('verifSuccess'))
                $('#tabRiwayat a[href="#verifikasi"]').tab('show');
        @endif
        @if(session()->has('kalibSuccess'))
                $('#tabRiwayat a[href="#kalibrasi"]').tab('show');
        @endif
        @if(session()->has('perbSuccess'))
                $('#tabRiwayat a[href="#perbaikan"]').tab('show');
        @endif

        // tampilkan alert input data berhasil
        @if(session()->has('success'))
            Swal.fire({
                title: 'Berhasil',
                text: 'Data Berhasil di Perbarui',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
        // modal alert end

        // pindah ke tab x ketika kembali dahi halaman input data
        @if($x == 1)
                $('#tabRiwayat a[href="#peminjaman"]').tab('show');
        @endif
        @if($x == 2)
                $('#tabRiwayat a[href="#perawatan"]').tab('show');
        @endif
        @if($x == 3)
                $('#tabRiwayat a[href="#verifikasi"]').tab('show');
        @endif
        @if($x == 4)
                $('#tabRiwayat a[href="#kalibrasi"]').tab('show');
        @endif
        @if($x == 5)
                $('#tabRiwayat a[href="#perbaikan"]').tab('show');
        @endif
        // pindah tab end

        // modal konfirmasi peminjaman hide field keterangan
            @if(old('status_peminjaman') == '15')
                $('#keterangan_konfirmasi_container').show();
            @else
                $('#keterangan_konfirmasi_container').hide();
            @endif

            var kembali_x = 0;
            var kembali_y = 0;
            function cek(x,y){
                if(x == 1 && y == 1){
                    $('#keterangan_konfirmasi_container').hide();
                }else{
                    $('#keterangan_konfirmasi_container').show();
                }
            }
            $("input[name$='status_peminjaman']").click(function(){
                if($("#konfirmasi_terima").is(":checked")){
                    kembali_x = 1; cek(kembali_x,kembali_y);
                }
                if($("#konfirmasi_tolak").is(":checked")){
                    kembali_x = 0; cek(kembali_x,kembali_y);
                }
            });
            $("input[name$='kondisi_peminjaman']").click(function(){
                if($("#konfirmasi_ok").is(":checked")){
                    kembali_y = 1; cek(kembali_x,kembali_y);
                }
                if($("#konformasi_not").is(":checked")){
                    kembali_y = 0; cek(kembali_x,kembali_y);
                }
            });
        // modal konfirmasi hide keterangan end

        // cek validasi peminjaman tampilkan modal pinjam
        @if($errors->has('tgl_peminjaman') or $errors->has('tgl_pengembalian'))
            $('#pinjamModal').modal('show');
        @endif
        // cek validasi peminjaman end

        // cek validasi konfirmasi peminjaman tampilkan modal konfirmasi
        @if($errors->has('keterangan_konfirmasi'))
            $('#konfirmasiModal').modal('show');
            $('#keterangan_konfirmasi_container').show();
        @endif
        // cek validasi konfirmasi end

        // cek validasi konfirmasi peminjaman tampilkan modal konfirmasi
        @if($errors->has('tgl_kembali') or $errors->has('jumlah_penggunaan') or $errors->has('waktu_penggunaan') or $errors->has('keterangan_kembali'))
            $('#terimaModal').modal('show');
        @endif
        // cek validasi konfirmasi end

        // $(window).on('load', function() {
        //     console.log('ready');
        //     $('.getDataMT').click(function(){
        //         console.log($(this).attr('data-id'));
        //     });
        // });
    });

    // ambil data peminjaman, untuk modal konfirmasi peminjaman
    function pinjamData(id){
        $.ajax({
            type:'GET',
            url:"{{url('/api/inventory/peminjaman_terima_data/')}}"+'/'+id,
            success:function(data) {
                $('#konfirmasiModal').modal('show');
                $('#peminjaman_konfirm_id').val(data.id_peminjaman);
                $('#alatuji_konfirm_id').val(data.serial_number_id);
                $('#modal_display_terima_peminjam').text(data.nama);
                $('#modal_display_terima_tgl1').text(data.created_at);
                $('#modal_display_terima_tgl2').text(data.tgl_pinjam);
                $('#modal_display_terima_tgl3').text(data.tgl_batas);
            }
        })
    }
    // ambil data untuk modal konfirmasi end

    // ambil data peminjaman, untuk modal terima barang peminjaman
    function dikembalikanData(id){
        $.ajax({
            type:'GET',
            url:"{{url('/api/inventory/peminjaman_terima_data/')}}"+'/'+id,
            success:function(data) {
                $('#terimaModal').modal('show');
                $('#peminjaman_kembali_id').val(data.id_peminjaman);
                $('#id_alat_uji').val(data.serial_number_id);
                $('#modal_display_kembali_nama').text(data.nama);
                $('#modal_display_kembali_tgl1').text(data.tgl_pinjam);
                $('#modal_display_kembali_tgl2').text(data.tgl_batas);
            }
        })
    }
    // ambil data untuk modal terima barang peminjaman end

    // ambil data gambar kalibrasi & perawatan
    // function getData(id, jenis ,tipe){
    //     //id = id gambar
    //     //jenis = kalibrasi / perbaikan
    //     //tipe = memo / surat jalan
    //     $.ajax({
    //         type:'GET',
    //         url:"{{url('/api/kalibrasiperbaikan/gambar_show/')}}"+'/'+id+'/'+jenis+'/'+tipe,
    //         success:function(data) {
    //             $('#gambarModal').modal('show');
    //             $('#gambarDokumen').attr('src', '{{url("storage/kalibrasiperbaikan/")}}'+'/'+data);
    //         },
    //         error: function (request, status, error) {
    //             alert(request.responseText);
    //         }
    //     });
    // }
    // ambil gambar kalibrasi perawtan end
    </script>

@stop
