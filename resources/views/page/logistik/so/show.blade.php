@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Sales Order</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->divisi_id == '15')
                        <li class="breadcrumb-item"><a href="{{ route('logistik.dashboard') }}">Beranda</a></li>
                    @elseif(Auth::user()->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                    @endif
                    <li class="breadcrumb-item active">Sales Order</li>

                </ol>
            </div>
        </div>
    </div>
@stop

@section('adminlte_css')
    <style>
        .hide {
            display: none !important;
        }

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
        }

        .filter {
            margin: 5px;
        }

        .minimizechar {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 25ch;
        }

        .align-center {
            text-align: center;
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
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-proses_kirim-tab" data-toggle="pill"
                                        href="#pills-proses_kirim" role="tab" aria-controls="pills-proses_kirim"
                                        aria-selected="true">Dalam Proses</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-selesai_kirim-tab" data-toggle="pill"
                                        href="#pills-selesai_kirim" role="tab" aria-controls="pills-selesai_kirim"
                                        aria-selected="false">Selesai Proses</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-proses_kirim" role="tabpanel"
                                    aria-labelledby="pills-proses_kirim-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="float-right filter">
                                                <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <form id="filter">
                                                    <div class="dropdown-menu">
                                                        <div class="px-3 py-3">
                                                            <div class="form-group">
                                                                <label for="jenis_penjualan">Database</label>
                                                            </div>
                                                            <div class="form-group">
                                                                {{-- show 5 years from now to old --}}
                                                                @for ($i = date('Y'); $i >= date('Y') - 1; $i--)
                                                                <div class="form-check">
                                                                    {{-- checked if years equals now --}}
                                                                    <input class="form-check-input" type="radio"
                                                                        value="{{ $i }}" name="tahun" id="defaultCheck{{ $i }}" {{ $i == date('Y') ? 'checked' : '' }} />
                                                                    <label class="form-check-label" for="defaultCheck{{ $i }}">
                                                                        {{ $i }}
                                                                    </label>
                                                                </div>
                                                                @endfor
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="jenis_penjualan">Pengiriman</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" name="pengiriman" type="checkbox"
                                                                        value="belum_kirim" id="defaultCheck1" />
                                                                    <label class="form-check-label" for="defaultCheck1">
                                                                        Belum Dikirim
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" name="pengiriman" type="checkbox"
                                                                        value="sebagian_kirim" id="defaultCheck2" />
                                                                    <label class="form-check-label" for="defaultCheck2">
                                                                        Sebagian Dikirim
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <span class="float-right">
                                                                    <button class="btn btn-primary">
                                                                        Cari
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table" style="text-align:center;" id="showtable">
                                                    <thead>
                                                        <th>No</th>
                                                        <th>No SO</th>
                                                        <th>No PO</th>
                                                        <th>Batas Pengiriman</th>
                                                        <th>Customer</th>
                                                        <th>Keterangan</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show" id="pills-selesai_kirim" role="tabpanel"
                                    aria-labelledby="pills-selesai_kirim-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="float-right filter">
                                                <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <form id="filterSelesaiProses">
                                                    <div class="dropdown-menu">
                                                        <div class="px-3 py-3">
                                                            <div class="form-group">
                                                                <label for="jenis_penjualan">Database</label>
                                                            </div>
                                                            <div class="form-group">
                                                                {{-- show 5 years from now to old --}}
                                                                @for ($i = date('Y'); $i >= date('Y') - 1; $i--)
                                                                <div class="form-check">
                                                                    {{-- checked if years equals now --}}
                                                                    <input class="form-check-input" type="radio"
                                                                        value="{{ $i }}" name="tahunSelesaiProses" id="defaultCheck{{ $i }}" {{ $i == date('Y') ? 'checked' : '' }} />
                                                                    <label class="form-check-label" for="defaultCheck{{ $i }}">
                                                                        {{ $i }}
                                                                    </label>
                                                                </div>
                                                                @endfor
                                                            </div>
                                                            <div class="form-group">
                                                                <span class="float-right">
                                                                    <button class="btn btn-primary">
                                                                        Cari
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table" style="text-align:center; width:100%;"
                                                    id="selesaitable">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">No</th>
                                                            <th rowspan="2">No SO</th>
                                                            <th rowspan="2">No PO</th>
                                                            <th colspan="2">Pengiriman</th>
                                                            <th rowspan="2">Customer</th>
                                                            {{-- <th rowspan="2">Alamat</th> --}}
                                                            <th rowspan="2">Keterangan</th>
                                                            <th rowspan="2">Aksi</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Awal</th>
                                                            <th>Akhir</th>
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
                    </div>
                </div>
            </div>

            <div class="modal fade" id="batalmodal" tabindex="-1" role="dialog" aria-labelledby="batalmodal"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-navy">
                            <h4 id="modal-title">Pesanan Batal</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="batal">

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="noserimodal" tabindex="-1" role="dialog" aria-labelledby="noserimodal"
                aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div id="modal-overlay" class="overlay"></div>
                        <div class="modal-header bg-light">
                            <h4 id="modal-title">Noseri</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="noseri">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table" style="text-align:center;width:100%;" id="noseritable">
                                            <thead>
                                                <th>No</th>
                                                <th>No Seri</th>
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
            </div>
        </div>
        @include('page.logistik.so.modalsj')

        <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Surat Jalan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body" id="editsj">
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('adminlte_js')
    <script>
        let yearsNow = new Date().getFullYear();
        $(function() {
            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': `/logistik/so/data/semua/${yearsNow}`,
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
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'so',
                        className: 'nowrap-text'
                    },
                    {
                        data: 'no_po',
                        className: 'nowrap-text'
                    },
                    {
                        data: 'batas',
                        className: 'align-center nowrap-text',
                    },
                    {
                        data: 'nama_customer',
                        className: 'align-center nowrap-text minimizechar'
                    },
                    {
                        data: 'ket',
                        className: 'align-center nowrap-text minimizechar',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'status',
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'button',
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $(document).on('hidden.bs.modal', '#noserimodal', function(event) {
                $('#batalmodal').find('#modal-overlay').addClass('hide');
            });

            $("#showtable").on('click', '.batalmodal', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                var jenis = $(this).data('jenis');
                $.ajax({
                    url: '/logistik/so/cancel/' + id,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#batalmodal').modal("show");
                        $('#batal').html(result).show();
                        produktable(id, jenis);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            function produktable(id, jenis) {
                $('#produktable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/logistik/so/data/detail/belum_kirim/' + id + '/' + jenis,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'detail_pesanan_id',
                            className: 'hide'
                        },
                        {
                            data: 'nama_produk',
                        },
                        {
                            data: 'jumlah',
                            orderable: false,
                            searchable: false,
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'array_check',
                            className: 'hide'
                        },
                        {
                            data: 'aksi',
                            orderable: false,
                            searchable: false,
                            className: 'align-center nowrap-text'
                        }
                    ],
                });
            }

            $(document).on('click', '#pills-selesai_kirim-tab', function() {
                selesaitable();
            });

            function selesaitable() {
                var selesaitable = $('#selesaitable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': `/api/logistik/so/data/selesai/${yearsNow}`,
                        'dataType': 'json',
                        'type': 'GET',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            className: 'align-center nowrap-text',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'so',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'no_po',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'tgl_awal',
                            className: 'align-center nowrap-text',
                        },
                        {
                            data: 'tgl_akhir',
                            className: 'align-center nowrap-text',
                        },
                        {
                            data: 'nama_customer',
                            className: 'align-center minimizechar'
                        },
                        {
                            data: 'ket',
                            className: 'align-center minimizechar',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'button',
                            className: 'align-center nowrap-text',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            }

            $(document).on('click', '#produktable .noseri', function(event) {
                event.preventDefault();
                var array = $(this).closest('tr').find('div[name="array_check[]"]').text();
                var id = $(this).attr('data-id');
                $('#batalmodal').find('#modal-overlay').removeClass('hide');
                $('#noserimodal').modal("show");
                noseritable(id, array);
            });

            function noseritable(id, array) {
                $('#noseritable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    autowidth: true,
                    ajax: {
                        'url': '/api/logistik/so/noseri/detail/belum_kirim/' + id + '/' + array,
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
                            data: 'no_seri',
                            className: 'nowrap-text align-center',
                            orderable: true,
                            searchable: true
                        }
                    ]
                });
            }

            $('#filterSelesaiProses').submit(function() {
                let years = $('input[name="tahunSelesaiProses"]:checked').val() ?? yearsNow

                $('#selesaitable').DataTable().ajax.url('/api/logistik/so/data/selesai/' + years).load();
                return false;
            })


            $('#filter').submit(function() {
                let pengiriman = $('input[name="pengiriman"]:checked').val() ?? 'semua'
                let years = $('input[name="tahun"]:checked').val() ?? yearsNow

                $('#showtable').DataTable().ajax.url('/logistik/so/data/' + pengiriman + '/' + years).load();
                return false;

            });

            const ekspedisi = (provinsi) => {
                $('#ekspedisi_id').select2({
                    placeholder: "Pilih Ekspedisi",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/logistik/ekspedisi/select/' + provinsi,
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama
                                    };
                                })
                            };
                        },
                    }
                })
            }

            const sjlama = (sjlama) => {
                $('#sj-lama').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    autowidth: true,
                    responsive: true,
                    data: sjlama,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [
                        {
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'sj'
                        },
                        {
                            data: null,
                            render: function(data, type, row, meta) {
                                return '<button data-id="'+data.id+'" class="btn btn-warning btn-sm btnEditSJ"><i class="fa fa-pen"></i></button> <a target="_blank" href="/logistik/pengiriman/prints/' + data.id + '" class="btn btn-sm btn-primary"><i class="fa fa-print"></i></a>'
                            }
                        }
                    ]
                })
            }

            const header = (header) => {
                if(header.jenis_pesanan == 'ekatalog') {
                    $('.form-provinsi').removeClass('hide')

                    $('.dataprovinsiekat').val(header.provinsi)
                    if(header.provinsi.instansi){
                        // checked instansi
                        $('#provinsi2').prop('checked', true)
                        let selectElement = $('.provinsi_pengiriman');
                        // select instansi
                        let option = $('<option>', {
                            value: header.provinsi.instansi.id,
                            text: header.provinsi.instansi.nama
                        })
                        // reset select
                        selectElement.empty()
                        selectElement.append(option)
                        selectElement.val(header.provinsi.instansi.id)
                        $('input[name="provinsi_id"]').val(header.provinsi.instansi.id)
                        ekspedisi(header.provinsi.instansi.id)
                    } else {
                        $('#provinsi1').prop('checked', true)
                        let selectElement = $('.provinsi_pengiriman');
                        // select instansi
                        let option = $('<option>', {
                            value: header.provinsi.dsb.id,
                            text: header.provinsi.dsb.nama
                        })
                        selectElement.empty()
                        selectElement.append(option)
                        selectElement.val(header.provinsi.dsb.id)
                        $('input[name="provinsi_id"]').val(header.provinsi.dsb.id)
                        ekspedisi(header.provinsi.dsb.id)
                    }

                    let id = $('.provinsi_pengiriman').val()

                    $('.ekspedisi_id').select2({
                        ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih Ekspedisi",
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/logistik/ekspedisi/select/' + id,
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama
                                    };
                                })
                            };
                        },
                    }
                })
                }else{
                    // add hidden
                    $('.form-provinsi').addClass('hide')

                    $('.provinsi_pengiriman').val(header.provinsi.id)
                    $('.provinsi_pengiriman').text(header.provinsi.nama)
                    $('input[name="provinsi_id"]').val(header.provinsi.id)
                    ekspedisi(header.provinsi.id)
                }

                if(header.ekspedisi) {
                    $('#pengiriman1').prop('checked', true)
                    $('#ekspedisi').removeClass('hide')
                    $('#nonekspedisi').addClass('hide')

                    let selectElement = $('.ekspedisi_id');
                    let option = $('<option>', {
                        value: header.ekspedisi.id,
                        text: header.ekspedisi.nama
                    })
                    selectElement.empty()
                    selectElement.append(option)
                    selectElement.val(header.ekspedisi.id)
                } else {
                    $('#pengiriman2').prop('checked', true)
                    $('#ekspedisi').addClass('hide')
                    $('#nonekspedisi').removeClass('hide')
                    let selectElement = $('.ekspedisi_id');
                    selectElement.empty()
                }

                if(header.perusahaan_pengiriman && header.alamat_pengiriman) {
                    // pilihan pengiriman == penjualan
                    $('#pilihan_pengiriman0').prop('checked', true)
                    $('#perusahaan_pengiriman').attr('readonly', true);
                    $('#alamat_pengiriman').attr('readonly', true);
                    $('input[name="perusahaan_pengiriman"]').val(header.perusahaan_pengiriman)
                    $('input[name="alamat_pengiriman"]').val(header.alamat_pengiriman)
                }else{
                    $('#pilihan_pengiriman1').prop('checked', true)
                    $('input[name="perusahaan_pengiriman"]').val('')
                    $('input[name="alamat_pengiriman"]').val('')
                    $('#perusahaan_pengiriman').attr('readonly', false);
                    $('#alamat_pengiriman').attr('readonly', false);
                }

                if(header.kemasan == 'peti') {
                    $('input[name="kemasan"]').val('peti').prop('checked', true)
                } else {
                    $('input[name="kemasan"]').val('nonpeti').prop('checked', true)
                }

                $('input[name="pesanan_id"]').val(header.pesanan_id)
                $('input[name="so"]').val(header.so)
                $('input[name="no_po"]').val(header.no_po)
                $('input[name="tgl_po"]').val(header.tgl_po)
                $('input[name="nama_customer"]').val(header.customer.nama)
                $('input[name="alamat_customer"]').val(header.customer.alamat)

                $.ajax({
                    'url': '/api/logistik/so/data/sj_draft/' + header.pesanan_id,
                    'dataType': 'json',
                    success: function (data) {
                        sjlama(data.data)
                    }
                });

            }

            const tableproduk = (produk) => {
                $('.tableproduk').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    autowidth: true,
                    responsive: true,
                    data: produk,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            // checkbox
                            return '<input type="checkbox" class="check_detail" value="' + data.id + '" />';
                        }
                    }, {
                        data: 'nama',
                    },
                    {
                        data: 'jumlah',
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            var rowIndex = meta.row;
                            return '<input type="text" class="form-control form-control-sm jumlah'+rowIndex+'" name="jumlah[' + rowIndex + ']" id="jumlah[' + rowIndex + ']" value="0" disabled/>';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            var rowIndex = meta.row;
                            return '<button class="btn btn-outline-primary btn-sm noseri" data-index="' + rowIndex + '">No Seri</button>';
                        }
                    },
                    {
                        // hidden text
                        data: null,
                        render: function(data, type, row, meta) {
                            var rowIndex = meta.row;

                            return '<div class="keterangannoseri'+rowIndex+'" name="keterangan[' + rowIndex + ']" id="keterangan[' + rowIndex + ']"></div>';
                            
                        }
                    },
                    ]
                })
            }

            const tablepart = (part) => {
                $('.tablepart').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    autowidth: true,
                    responsive: true,
                    data: part,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            // checkbox
                            return '<input type="checkbox" class="check_detail_part" value="' + data.id + '" />';
                        }
                    }, {
                        data: 'nama',
                    },
                    {
                        data: 'jumlah',
                    },
                    ]
                })
            }

            $(document).on('click', '.cetaksj', function(event) {
                event.preventDefault();
                let data_x = $(this).data('x');
                let data_y = $(this).data('y');

                // open modal
                $('#cetaksjmodal').modal('show');
                $('#cetaksjmodal').data('data_y', data_y);
            });
            $('#cetaksjmodal').on('shown.bs.modal', function() {
                $(this).find('form').trigger('reset');
                let data_y = $(this).data('data_y');
                $.ajax({
                    'url': '/api/logistik/so/data/detail/item/' + data_y,
                    'dataType': 'json',
                    success: function (data) {
                        header(data.header)
                        tableproduk(data.item.produk)
                        tablepart(data.item.part)
                    }
                });
            })

            $(document).on('click', '.btnEditSJ', function(event) {
                event.preventDefault();
                let id = $(this).data('id');
                // open modal
                $.ajax({
                    url: "/logistik/pengiriman/edit_sj_draft/"+id,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result){
                        $('#modalEdit').modal('show');
                        $('#editsj').html(result).show();
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                })
            });

            $(document).on('click', '.batalEdit', function (event) {
                event.preventDefault();
                $('#modalEdit').modal('hide');
            })

            $(document).on('click', '.btnSimpanSuratJalan', function (e) {
                $('.btnSimpanSuratJalan').attr('disabled', true)

                let id = $('input[name="ideditsj"]').val()
                let jenis_sj_edit = $('select[name="jenis_sj_edit"]').val()
                let no_invoice_sj_edit = $('input[name="no_invoice_sj_edit"]').val()
                let tgl_sj = $('input[name="tgl_kirim_edit_sj"]').val()
                let sj = jenis_sj_edit + '' + no_invoice_sj_edit

                // validasi not null
                if(sj == null || tgl_sj == null) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No Surat Jalan dan Tanggal Surat Jalan tidak boleh kosong!',
                    })
                    $('.btnSimpanSuratJalan').attr('disabled', false)
                    return false
                }

                let action = $('form[id="formcetaksjeditdraft"]').attr('action')
                $.ajax({
                    type: "POST",
                    url: action,
                    data: {
                        id,
                        sj,
                        tgl_sj
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                        })
                        $('#modalEdit').modal('hide');
                        $('#loader').hide();
                        $.ajax({
                            'url': '/api/logistik/so/data/sj_draft/' + response.pesanan_id,
                            'dataType': 'json',
                            success: function (data) {
                                sjlama(data.data)
                            }
                        });
                        // $('#sj-lama').DataTable().ajax.reload();
                    },
                    error: function(jqXHR, testStatus, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error,
                        })
                        $('#loader').hide();
                    },
                })
            })

        })
    </script>
@stop
