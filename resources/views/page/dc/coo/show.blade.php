@extends('adminlte.page')

@section('title', 'ERP')


@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">COO</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '9')
                        <li class="breadcrumb-item"><a href="{{ route('dc.dashboard') }}">Beranda</a></li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                    @endif
                    <li class="breadcrumb-item active">Daftar COO</li>

                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .urgent {
            color: #dc3545;
            font-weight: 600;
        }

        .warning {
            color: #FFC700;
            font-weight: 600;
        }

        .info {
            color: #3a7bb0;
            font-weight: 600;
        }

        .filter {
            margin: 5px;
        }

        .nowraptext {
            white-space: nowrap;
        }

        @media screen and (min-width: 1440px) {
            section {
                font-size: 14px;
            }

            .dropdown-item {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 1439px) {
            section {
                font-size: 12px;
            }

            .dropdown-item {
                font-size: 12px;
            }
        }
    </style>
@stop

@section('content')
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped nowraptext" style="text-align:center;"
                                            id="showtable">
                                            <thead>
                                                <th>No</th>
                                                <th>No Seri</th>
                                                <th>No PO</th>
                                                <th>No AKN</th>
                                                <th>Nama Produk</th>
                                                <th>No AKD</th>
                                                <th>Bulan</th>
                                                <th>Tgl Surat Jalan</th>
                                                <th>Tgl Kirim</th>
                                                <th>Catatan</th>
                                                <th>Laporan</th>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                    <td>1</td>
                                                    <td>30031</td>
                                                    <td>MTB2390193</td>
                                                    <td>SO/EKAT/X/02/98</td>
                                                    <td>AK1-909090-1892180</td>
                                                    <td>Elitech MTB 2 MTR</td>
                                                    <td>AKD4284020</td>
                                                    <td>IX</td>
                                                    <td>30-09-2021</td>
                                                    <td><a href="/dc/coo/pdf">
                                                            <i class="fas fa-file"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>30031</td>
                                                    <td>MTB2390193</td>
                                                    <td>SO/EKAT/X/02/98</td>
                                                    <td>AK1-909090-1892180</td>
                                                    <td>Elitech MTB 2 MTR</td>
                                                    <td>AKD4284020</td>
                                                    <td>IX</td>
                                                    <td>30-09-2021</td>
                                                    <td><a href="/dc/coo/pdf">
                                                            <i class="fas fa-file"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>30031</td>
                                                    <td>MTB2390193</td>
                                                    <td>SO/EKAT/X/02/98</td>
                                                    <td>AK1-909090-1892180</td>
                                                    <td>Elitech MTB 2 MTR</td>
                                                    <td>AKD4284020</td>
                                                    <td>IX</td>
                                                    <td>30-09-2021</td>
                                                    <td><a href="/dc/coo/pdf">
                                                            <i class="fas fa-file"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>30031</td>
                                                    <td>MTB2390193</td>
                                                    <td>SO/EKAT/X/02/98</td>
                                                    <td>AK1-909090-1892180</td>
                                                    <td>Elitech MTB 2 MTR</td>
                                                    <td>AKD4284020</td>
                                                    <td>IX</td>
                                                    <td>30-09-2021</td>
                                                    <td><a href="/dc/coo/pdf">
                                                            <i class="fas fa-file"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td>30031</td>
                                                    <td>MTB2390193</td>
                                                    <td>SO/EKAT/X/02/98</td>
                                                    <td>AK1-909090-1892180</td>
                                                    <td>Elitech MTB 2 MTR</td>
                                                    <td>AKD4284020</td>
                                                    <td>IX</td>
                                                    <td>30-09-2021</td>
                                                    <td><a href="/dc/coo/pdf">
                                                            <i class="fas fa-file"></i>
                                                        </a>
                                                    </td>
                                                </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('adminlte_js')
    <script>
        $(function() {
            $('#showtable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/dc/data',
                    'type': 'POST',
                    'datatype': 'JSON',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'seri'
                }, {
                    data: 'po'
                }, {
                    data: 'no_paket'
                }, {
                    data: 'nama_produk'
                }, {
                    data: 'noakd'
                }, {
                    data: 'bulan'
                }, {
                    data: 'tgl_sj'
                }, {
                    data: 'tglkirimcoo'
                }, {
                    data: 'catatan'
                }, {
                    data: 'laporan'
                }]
            });
        });
    </script>
@stop
