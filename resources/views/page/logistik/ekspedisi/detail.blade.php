@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')
    <style>
        li.list-group-item {
            border: 0 none;
        }

        #historitabel {
            text-align: center;
        }

        .align-center {
            text-align: center;
        }

        .margin-all {
            margin: 5px;
        }

        .margin-side {
            margin-left: 5px;
            margin-right: 5px;
        }

        #profileImage {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #4682B4;
            font-size: 22px;
            color: #fff;
            text-align: center;
            line-height: 100px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        #detailekat {
            background-color: #E9DDE5;

        }

        #detailspa {
            background-color: #FFE6C9;
        }

        #detailspb {
            background-color: #E1EBF2;
            /* color: #7D6378; */

        }

        .overflowy {
            max-height: 550px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }

        .removeshadow {
            box-shadow: none;
        }

        .align-center {
            text-align: center;
        }

        .bordertopnone {
            border-top: 0;
            border-left: 0;
            border-right: 0;
            border-bottom: 0;
            vertical-align: top;
        }

        .margin {
            margin-left: 10px;
            margin-right: 10px;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        @media screen and (min-width: 1440px) {
            section {
                font-size: 14px;
            }

            .dropdown-item {
                font-size: 14px;
            }

            .labelinfo {
                text-align: center;
            }
        }

        @media screen and (max-width: 1439px) {
            section {
                font-size: 12px;
            }

            .dropdown-item {
                font-size: 12px;
            }

            .labelinfo {
                text-align: center;
            }
        }

        @media screen and (max-width:991px) {
            .labelinfo {
                text-align: left;
            }
        }

        @media screen and (max-width:767px) {
            .labelinfo {
                text-align: center;
            }
        }
    </style>
@stop

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Ekspedisi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '15')
                        <li class="breadcrumb-item"><a href="{{ route('logistik.dashboard') }}">Beranda</a></li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('logistik.ekspedisi.show') }}">Ekspedisi</a></li>
                    <li class="breadcrumb-item active">Detail</li>

                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('content')
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Info Ekspedisi</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-4 align-center">
                                    <div id="profileImage" class="center margin-all"></div>
                                </div>
                                <div class="col-lg-12 col-md-8 labelinfo">
                                    <div class="margin-all">
                                        <h5><b>{{ $e->nama }}</b></h5>
                                    </div>
                                    <div class="margin-all"><b>{{ $e->alamat }}</b></div>
                                    <div class="margin-all">
                                        <span class="margin-side"><i class="fas fa-phone text-muted margin-side"></i>
                                            <b>{{ $e->telp }}</b></span>
                                        <span class="margin-side"><i class="fas fa-envelope text-muted margin-side"></i><b>
                                                @if (!empty($e->email))
                                                    {{ $e->email }}
                                                @else
                                                    -
                                                @endif
                                            </b></span>
                                    </div>
                                    <div class="margin-all"><a class="text-muted margin-side">Jalur :</a><b>
                                            @foreach ($e->jalurekspedisi as $j)
                                                @if ($j->nama == 'darat')
                                                    <span class="badge green-text">Darat</span>
                                                @elseif($j->nama == 'laut')
                                                    <span class="badge blue-text">Laut</span>
                                                @elseif($j->nama == 'udara')
                                                    <span class="badge orange-text">Udara</span>
                                                @else
                                                    <span class="badge purple-text">Lain</span>
                                                @endif
                                            @endforeach
                                        </b>
                                    </div>
                                    <div class="margin-all"><a class="text-muted margin-side">Jurusan :</a><b>
                                            @foreach ($e->Provinsi as $p)
                                                {{ $p->nama }}
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </b></div>
                                    <div class="margin-all"><a class="text-muted">{{ $e->ket }}</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Histori Pengiriman</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-center" id="showtable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No SO</th>
                                            <th>No SJ</th>
                                            <th>Tanggal Kirim</th>
                                            <th>No Resi</th>
                                            <th>Nama Customer</th>
                                            <th>Alamat</th>
                                            <th>Telepon</th>
                                            <th>Status</th>
                                            {{-- <th>Aksi</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header">
                        <h4>Detail</h4>
                    </div>
                    <div class="modal-body" id="detail">
                    </div>
                </div>
            </div>
        </div>
    </section>


@stop

@section('adminlte_js')
    <script>
        $(function() {
            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/logistik/ekspedisi/detail/{{ $e->id }}',
                    'dataType': 'json',
                    'type': 'POST',
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
                    },
                    {
                        data: 'so'
                    },
                    {
                        data: 'sj',

                    },
                    {
                        data: 'tgl',

                    },
                    {
                        data: 'noresi',

                    },
                    {
                        data: 'nama_customer',
                        className: 'wb',
                    },
                    {
                        data: 'alamat',
                        className: 'wb',
                    },
                    {
                        data: 'telp',

                    },
                    {
                        data: 'status',

                    }
                ]
            });

            var cust = <?php echo json_encode($e->nama); ?>;
            var cust = cust.replace('.', '').replace('PT ', '').replace('CV ', '').replace('& ', '').replace('(',
                '').replace(')', '');
            var init = cust.split(" ");
            var initial = "";
            for (var i = 0; i < init.length; i++) {
                initial = initial + init[i].charAt(0);
            }
            var profileImage = $('#profileImage').text(initial);
        });
    </script>
@stop
