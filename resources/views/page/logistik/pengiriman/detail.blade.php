@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Pengiriman</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('as.so.show') }}">Sales Order</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    @else
                        @if (Auth::user()->Karyawan->divisi_id == '15')
                            <li class="breadcrumb-item"><a href="{{ route('logistik.dashboard') }}">Beranda</a></li>
                        @elseif(Auth::user()->Karyawan->divisi_id == '2')
                            <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        @endif
                        <li class="breadcrumb-item"><a href="{{ route('logistik.pengiriman.show') }}">Pengiriman</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }

        #showtable {
            text-align: center;
            white-space: nowrap;
        }

        .ok {
            color: green;
            font-weight: 600;
        }

        .nok {
            color: #dc3545;
            font-weight: 600;
        }

        .warning {
            color: #FFC700;
            font-weight: 600;
        }

        .list-group-item {
            border: 0 none;
        }

        .align-right {
            text-align: right;
        }

        .align-center {
            text-align: center;
        }

        .margin {
            margin-bottom: 5px;
        }

        .filter {
            margin: 5px;
        }

        .hide {
            display: none !important;
        }

        .bgcolor {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .fa-search:hover {
            color: #ADD8E6;
        }

        .fa-search:active {
            color: #808080;
        }

        .nowrap-text {
            white-space: nowrap;
        }

        .minimizechar {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 30ch;
        }

        .wb {
            word-break: break-all;
        }

        @media screen and (min-width: 1440px) {
            section {
                font-size: 14px;
            }

            .dropdown-item {
                font-size: 14px;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 1439px) {
            section {
                font-size: 12px;
            }

            .dropdown-item {
                font-size: 12px;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 992px) {
            .align-md {
                text-align: center;
            }
        }
    </style>
@stop

@section('content_header')
    <h1 class="m-0 text-dark">Surat Jalan</h1>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            @if ($jenis == 'EKAT')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Info</h5>
                                <div class="row">
                                    <div class="col-lg-11 col-md-12">
                                        <div class="row d-flex justify-content-between">
                                            <div class="p-2 cust">
                                                <div class="margin">
                                                    <div><small class="text-muted">Subjek Pengiriman</small></div>
                                                </div>
                                                @if (isset($l->DetailLogistik[0]))
                                                    <div class="margin">
                                                        <b
                                                            id="customer">{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi }}</b>
                                                    </div>
                                                    <div class="margin">
                                                        <b
                                                            id="alamat">{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->alamat }}</b>
                                                    </div>
                                                    <div class="margin">
                                                        <b
                                                            id="provinsi">{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama }}</b>
                                                    </div>
                                                    <div class="margin">
                                                        <b
                                                            id="telepon">{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->telp }}</b>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">Ekspedisi / Pengiriman</small></div>
                                                    <div><b id="ekspedisi">
                                                            @if (!empty($l->ekspedisi_id))
                                                                {{ $l->Ekspedisi->nama }}
                                                            @else
                                                                {{ $l->nama_pengirim }}
                                                            @endif
                                                        </b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">No Surat Jalan</small></div>
                                                    <div><b id="no_sj">{{ $l->nosurat }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal Kirim</small></div>
                                                    <div><b id="no_sj">{{ $l->tgl_kirim }}</b></div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No Sales Order</small></div>
                                                    <div><b
                                                            id="no_so">{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">No PO</small></div>
                                                    <div><b
                                                            id="no_so">{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal PO</small></div>
                                                    <div><b
                                                            id="no_so">{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->tgl_po }}</b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No Invoice</small></div>
                                                    <div><b id="no_resi">-</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Status</small></div>
                                                    <div>
                                                        @if ($l->status_id == '10')
                                                            @if (empty($l->noresi) && !empty($l->ekspedisi_id))
                                                                <span class="badge blue-text">Dalam Pengirman</span>
                                                            @else
                                                                <span class="badge green-text">Selesai</span>
                                                            @endif
                                                        @elseif($l->status_id == '11')
                                                            <span class="badge red-text">Draft Pengiriman</span>
                                                        @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Resi</small></div>
                                                    <div><b id="no_resi">
                                                            @if (!empty($l->noresi))
                                                                {{ $l->noresi }}
                                                            @else
                                                                -
                                                            @endif
                                                        </b></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($jenis == 'SPA')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Info</h5>
                                <div class="row">
                                    <div class="col-lg-11 col-md-12">
                                        <div class="row d-flex justify-content-between">
                                            <div class="p-2 cust">
                                                <div class="margin">
                                                    <div><small class="text-muted">Subjek Pengiriman</small></div>
                                                </div>
                                                <div class="margin">
                                                    <b id="customer">
                                                        @if (isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama }}
                                                        @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama }}
                                                        @elseif(isset($l->DetailLogistikPart))
                                                            {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->nama }}
                                                        @endif
                                                    </b>
                                                </div>
                                                <div class="margin">
                                                    <b id="alamat">
                                                        @if (isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat }}
                                                        @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat }}
                                                        @elseif(isset($l->DetailLogistikPart))
                                                            {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->alamat }}
                                                        @endif
                                                    </b>
                                                </div>
                                                <div class="margin">
                                                    <b id="provinsi">
                                                        @if (isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->provinsi->nama }}
                                                        @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->provinsi->nama }}
                                                        @elseif(isset($l->DetailLogistikPart))
                                                            {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->provinsi->nama }}
                                                        @endif
                                                    </b>
                                                </div>
                                                <div class="margin">
                                                    <b id="telepon">
                                                        @if (isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->telp }}
                                                        @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->telp }}
                                                        @elseif(isset($l->DetailLogistikPart))
                                                            {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->telp }}
                                                        @endif
                                                    </b>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">Ekspedisi / Pengiriman</small></div>
                                                    <div><b id="ekspedisi">
                                                            @if (!empty($l->ekspedisi_id))
                                                                {{ $l->Ekspedisi->nama }}
                                                            @else
                                                                {{ $l->nama_pengirim }}
                                                            @endif
                                                        </b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">No Surat Jalan</small></div>
                                                    <div><b id="no_sj">
                                                            {{ $l->nosurat }}
                                                        </b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal Kirim</small></div>
                                                    <div><b id="no_sj">{{ $l->tgl_kirim }}</b></div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No Sales Order</small></div>
                                                    <div><b id="no_so">
                                                            @if (isset($l->DetailLogistik[0]))
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so }}
                                                            @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so }}
                                                            @elseif(isset($l->DetailLogistikPart))
                                                                {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so }}
                                                            @endif
                                                        </b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">No PO</small></div>
                                                    <div><b id="no_so">
                                                            @if (isset($l->DetailLogistik[0]))
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po }}
                                                            @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po }}
                                                            @elseif(isset($l->DetailLogistikPart))
                                                                {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->no_po }}
                                                            @endif
                                                        </b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal PO</small></div>
                                                    <div><b id="no_so">
                                                            @if (isset($l->DetailLogistik[0]))
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->tgl_po }}
                                                            @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->tgl_po }}
                                                            @elseif(isset($l->DetailLogistikPart))
                                                                {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->tgl_po }}
                                                            @endif
                                                        </b></div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No Invoice</small></div>
                                                    <div><b id="no_resi">-</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Status</small></div>
                                                    <div>
                                                        @if ($l->status_id == '10')
                                                            @if (empty($l->noresi))
                                                                <span class="badge blue-text">Dalam Pengirman</span>
                                                            @else
                                                                <span class="badge green-text">Selesai</span>
                                                            @endif
                                                        @elseif($l->status_id == '11')
                                                            <span class="badge red-text">Draft Pengiriman</span>
                                                        @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Resi</small></div>
                                                    <div><b id="no_resi">
                                                            @if (!empty($l->noresi))
                                                                {{ $l->noresi }}
                                                            @else
                                                                -
                                                            @endif
                                                        </b></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($jenis == 'SPB')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Info</h5>
                                <div class="row">
                                    <div class="col-lg-11 col-md-12">
                                        <div class="row d-flex justify-content-between">
                                            <div class="p-2 cust">
                                                <div class="margin">
                                                    <div><small class="text-muted">Subjek Pengiriman</small></div>
                                                </div>
                                                <div class="margin">
                                                    <b id="customer">
                                                        @if (isset($l->DetailLogistikPart))
                                                            {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->nama }}
                                                        @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama }}
                                                        @else
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama }}
                                                        @endif
                                                    </b>
                                                </div>
                                                <div class="margin">
                                                    <b id="alamat">
                                                        @if (isset($l->DetailLogistikPart))
                                                            {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->alamat }}
                                                        @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat }}
                                                        @else
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat }}
                                                        @endif
                                                    </b>
                                                </div>
                                                <div class="margin">
                                                    <b id="provinsi">
                                                        @if (isset($l->DetailLogistikPart))
                                                            {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama }}
                                                        @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama }}
                                                        @else
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama }}
                                                        @endif
                                                    </b>
                                                </div>
                                                <div class="margin">
                                                    <b id="telepon">
                                                        @if (isset($l->DetailLogistikPart))
                                                            {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->telp }}
                                                        @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->telp }}
                                                        @else
                                                            {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->telp }}
                                                        @endif
                                                    </b>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">Ekspedisi / Pengiriman</small></div>
                                                    <div><b id="ekspedisi">
                                                            @if (!empty($l->ekspedisi_id))
                                                                {{ $l->Ekspedisi->nama }}
                                                            @else
                                                                {{ $l->nama_pengirim }}
                                                            @endif
                                                        </b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">No Surat Jalan</small></div>
                                                    <div><b id="no_sj">{{ $l->nosurat }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal Kirim</small></div>
                                                    <div><b id="no_sj">{{ $l->tgl_kirim }}</b></div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No Sales Order</small></div>
                                                    <div><b id="no_so">
                                                            @if (isset($l->DetailLogistikPart))
                                                                {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so }}
                                                            @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so }}
                                                            @else
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so }}
                                                            @endif
                                                        </b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">No PO</small></div>
                                                    <div><b id="no_po">
                                                            @if (isset($l->DetailLogistikPart))
                                                                {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->no_po }}
                                                            @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po }}
                                                            @else
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po }}
                                                            @endif
                                                        </b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal PO</small></div>
                                                    <div><b id="no_so">
                                                            @if (isset($l->DetailLogistikPart))
                                                                {{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->tgl_po }}
                                                            @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->tgl_po }}
                                                            @else
                                                                {{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->tgl_po }}
                                                            @endif
                                                        </b></div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No Invoice</small></div>
                                                    <div><b id="no_resi">-</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Status</small></div>
                                                    <div>
                                                        @if ($l->status_id == '10')
                                                            @if (empty($l->noresi))
                                                                <span class="badge blue-text">Dalam Pengirman</span>
                                                            @else
                                                                <span class="badge green-text">Selesai</span>
                                                            @endif
                                                        @elseif($l->status_id == '11')
                                                            <span class="badge red-text">Draft Pengiriman</span>
                                                        @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Resi</small></div>
                                                    <div><b id="no_resi">
                                                            @if (!empty($l->noresi))
                                                                {{ $l->noresi }}
                                                            @else
                                                                -
                                                            @endif
                                                        </b></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($jenis == 'EKAT')
                @if (isset($l->DetailLogistik[0]))
                    @if ($l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->ket != '')
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Catatan:
                            </strong>{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->ket }}
                        </div>
                    @endif
                @endif
            @elseif($jenis == 'SPA')
                @if (isset($l->DetailLogistikPart) && !isset($l->DetailLogistik[0]))
                    @if ($l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->ket != '')
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Catatan:
                            </strong>{{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->ket }}
                        </div>
                    @endif
                @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                    @if ($l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->ket != '')
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Catatan:
                            </strong>{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->ket }}
                        </div>
                    @endif
                @else
                    @if ($l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->ket != '')
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Catatan:
                            </strong>{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->ket }}
                        </div>
                    @endif
                @endif
            @elseif($jenis == 'SPB')
                @if (isset($l->DetailLogistikPart) && !isset($l->DetailLogistik[0]))
                    @if ($l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->ket != '')
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Catatan:
                            </strong>{{ $l->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->ket }}
                        </div>
                    @endif
                @elseif(isset($l->DetailLogistikPart) && isset($l->DetailLogistik[0]))
                    @if ($l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->ket != '')
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Catatan:
                            </strong>{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->ket }}
                        </div>
                    @endif
                @else
                    @if ($l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->ket != '')
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Catatan:
                            </strong>{{ $l->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->ket }}
                        </div>
                    @endif
                @endif
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-center" id="detailtable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>No Seri</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
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

            <div class="modal fade" id="detailmodal" role="dialog" aria-labelledby="detailmodal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Logistik</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="detail">

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
            var role = "{{ Auth::user()->Karyawan->divisi_id }}";
            var jenis = "{{ $jenis }}";
            var showtable = $('#detailtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/logistik/pengiriman/data/' + "{{ $id }}" + '/' + jenis,
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
                        data: 'nama_produk'
                    },
                    {
                        data: 'jumlah',
                    },
                    {
                        data: 'no_seri',
                        className: 'minimizechar',
                    },
                    {
                        data: 'keterangan',
                    },
                    {
                        data: 'aksi',
                        visible: role != 15 ? false : true
                    }
                ]
            });

            function showtabless(id) {
                $('#showtable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/logistik/so/noseri/detail/selesai_kirim/data/' + id,
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
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'no_seri'
                        }
                    ]
                })
            }

            $('#detailtable').on('click', '.detailmodal', function(event) {
                var data = $(this).attr('data-id');
                $.ajax({
                    url: "/api/logistik/so/noseri/detail/selesai_kirim/" + data,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#detailmodal').modal("show");
                        $('#detail').html(result).show();
                        showtabless(data);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 5000
                });
            })
        })
    </script>
@stop
