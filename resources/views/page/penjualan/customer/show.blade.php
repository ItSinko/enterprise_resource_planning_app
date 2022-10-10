@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Customer</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->divisi_id == '26' || Auth::user()->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Customer</li>
                    @elseif(Auth::user()->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Customer</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }

        .hide {
            display: none !important
        }

        .align-center {
            text-align: center;
        }

        .nowrap-text {
            white-space: nowrap;
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

        .dropdown-toggle:hover {
            color: #4682B4;
        }

        .dropdown-toggle:active {
            color: #C0C0C0;
        }

        .yellow-bg {
            background-color: #fff4dc;
            color: #ffc107;
        }


        .removeboxshadow {
            box-shadow: none;
            border: 1px;
        }

        @media screen and (min-width: 1440px) {
            body {
                font-size: 14px;
            }

            .dropdown-item {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .labelket {
                text-align: right;
            }
        }

        @media screen and (max-width: 1439px) {
            body {
                font-size: 12px;
            }

            .dropdown-item {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: right;
            }
        }

        @media screen and (max-width: 991px) {
            .labelket {
                text-align: left;
            }
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if (Session::has('error') || count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show col-12" role="alert">
                            <strong>{{ Session::get('error') }}</strong> Periksa
                            kembali data yang diinput
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @elseif(Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show col-12" role="alert">
                            <strong>{{ Session::get('success') }}</strong>,
                            Terima kasih
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row" style="margin-bottom:10px;">
                                        <div class="col-12">
                                            <span class="float-left filter">
                                                <a id="exportbutton" href="{{ route('penjualan.customer.export') }}"><button
                                                        class="btn btn-success">
                                                        <i class="far fa-file-excel" id="load"></i> Export
                                                    </button>
                                                </a>
                                            </span>
                                            @if (Auth::user()->divisi->id == '26')
                                                <span class="float-right filter">
                                                    <a href="{{ route('penjualan.customer.create') }}"><button
                                                            class="btn btn-outline-info">
                                                            <i class="fas fa-plus"></i> Tambah
                                                        </button></a>
                                                </span>
                                            @endif
                                            <span class="float-right filter">
                                                <button class="btn btn-outline-secondary dropdown-toggle " type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                    id="filterpenjualan">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="filterpenjualan">
                                                    <form class="px-4" style="white-space:nowrap;" id="filter">
                                                        <div class="dropdown-header">
                                                            Status
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input type="radio" class="form-check-input"
                                                                    id="dropdownStatus1" value="2" name='filter' />
                                                                <label class="form-check-label" for="dropdownStatus1">
                                                                    Jawa
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input type="radio" class="form-check-input"
                                                                    id="dropdownStatus2" value="1" name='filter' />
                                                                <label class="form-check-label" for="dropdownStatus2">
                                                                    Luar Jawa
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input type="radio" class="form-check-input"
                                                                    id="dropdownStatus3" value="0" name='filter' />
                                                                <label class="form-check-label" for="dropdownStatus3">
                                                                    Semua
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary float-right">
                                                                Cari
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="showtable" style="width:100%;">
                                                    <thead style="text-align:center;">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama</th>
                                                            {{-- <th>Alamat</th> --}}
                                                            <th>Provinsi</th>
                                                            <th>Email</th>
                                                            <th>Telp</th>
                                                            <th>NPWP</th>
                                                            <th>KTP</th>
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
                        </div>

                        <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content" style="margin: 10px">
                                    <div class="modal-header yellow-bg">
                                        <h4 class="modal-title"><b>Ubah Customer</b></h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="edit">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="hapusmodal" role="dialog" aria-labelledby="hapusmodal"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content" style="margin: 10px">
                                    <div class="modal-header bg-danger">
                                        <h4 class="modal-title"><b>Hapus</b></h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="hapus">
                                        <div class="row">
                                            <div class="col-12">
                                                <form method="post" action="" id="form-hapus" data-target="">
                                                    @method('DELETE')
                                                    @csrf
                                                    <div class="card">
                                                        <div class="card-body">Apakah Anda yakin ingin menghapus data ini?
                                                        </div>
                                                        <div class="card-footer">
                                                            <span class="float-left">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Batal</button>
                                                            </span>
                                                            <span class="float-right">
                                                                <button type="submit" class="btn btn-danger "
                                                                    id="btnhapus">Hapus</button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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
    <script type="text/javascript" src="{{ asset('vendor/masking/masking.js') }}"></script>
    <script>
        $(function() {
            function validasi() {
                if (($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && ($("#npwp")
                        .val() != "" && !$("#npwp").hasClass('is-invalid')) && $("#alamat").val() != "" && $(
                        '.provinsi').val() != "" && ($("#telepon").val() != "" && !$("#telepon").hasClass(
                        'is-invalid')) && !$("#email").hasClass('is-invalid')) {
                    $("#btnsimpan").removeAttr('disabled');
                } else {
                    $("#btnsimpan").attr('disabled', true);
                }
            }
            var divisi_id = "{{ Auth::user()->divisi_id }}";
            $(document).on('submit', '#form-customer-update', function(e) {
                e.preventDefault();
                var action = $(this).attr('data-attr');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: $('#form-customer-update').serialize(),
                    beforeSend: function() {
                        swal.fire({
                            title: 'Sedang Proses',
                            html: 'Loading...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading()
                            }
                        })
                    },
                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan edit data',
                                'success'
                            );
                            $("#editmodal").modal('hide');
                            $('#showtable').DataTable().ajax.reload();
                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan edit data',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        alert($('#form-customer-update').serialize());
                    }
                });
                return false;
            });

            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/customer/data/' + divisi_id + '/' + 0,
                    "dataType": "json",
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    "processData": true,
                    beforeSend: function(xhr) {
                        var access_token = localStorage.getItem('lokal_token');
                        xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
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
                        data: 'nama',
                        className: 'nowrap-text',
                    },
                    // {
                    //     data: 'alamat',
                    //     className: 'minimizechar',
                    //     orderable: false,
                    // },
                    {
                        data: "prov",
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'email',
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'telp',
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'npwp',
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'ktp',
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'ket',
                        className: 'minimizechar',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'button',
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            function reset_izin_usaha() {
                //    var get= $('input[type="radio"][name="izin_usaha"]:checked').val();
                //    var count= $('input[type="radio"][name="izin_usaha"]:checked').length;
                //     $('input[type="radio"][name="izin_usaha"]').on('click', function() {
                //         $("input[type='radio'][name='izin_usaha']").removeAttr('checked');
                //         var current =  $(this).val();
                //         if (get == current){
                //         $("input[type='radio'][name='izin_usaha'][value='"+current+"']").prop("checked",false);
                //     }});
            }

            function tooltips() {
                $('[data-toggle="iumk_info"]').tooltip({
                    content: '<p><b>Izin usaha mikro dan kecil (IUMK)</b> adalah tanda legalitas kepada seseorang atau pelaku usaha/kegiatan tertentu dalam bentuk izin usaha mikro dan kecil dalam bentuk satu lembar</p>'
                });
                $('[data-toggle="iutm_info"]').tooltip({
                    content: '<p><b>Izin Usaha Toko Modern selanjutnya (IUTM)</b> adalah izin untuk dapat melaksanakan usaha pengelolaan Toko Modern yang diterbitkan oleh Pemerintah Daerah setempat</p>'
                });
                $('[data-toggle="siup_info"]').tooltip({
                    content: '<p><b>Surat Izin Usaha Perdagangan (SIUP)</b> adalah surat ijin yang diberikan kepada suatu badan usaha untuk dapat melakukan kegiatan usaha perdagangan</p>'
                });
            }
            $(document).on('click', '.editmodal', function(event) {
                event.preventDefault();
                var href = $(this).attr('data-attr');
                var id = $(this).data('id');
                $.ajax({
                    url: "/api/customer/update_modal/" + id,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#editmodal').modal("show");
                        $('#edit').html(result).show();
                        $('#npwp').mask('00.000.000.0-000.000');
                        $('#ktp').mask('0000000000000000');
                        tooltips();
                        reset_izin_usaha();
                        console.log(id);
                        // $("#editform").attr("action", href);
                        select_data();
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page " + href + " cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            $(document).on('click', '.hapusmodal', function(event) {
                event.preventDefault();
                var href = $(this).attr('data-attr');
                var id = $(this).data("id");
                $('#hapusmodal').modal("show");
                $('#hapusmodal').find('form').attr('action', '/api/customer/delete/' + id);
            });

            $(document).on('submit', '#form-hapus', function(e) {
                e.preventDefault();
                var action = $(this).attr('action');
                console.log(action);
                $.ajax({
                    url: action,
                    type: 'delete',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        swal.fire({
                            title: 'Sedang Proses',
                            html: 'Loading...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading()
                            }
                        })
                    },
                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan Hapus Data',
                                'success'
                            );
                            $('#showtable').DataTable().ajax.reload();
                            $("#hapusmodal").modal('hide');
                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan Penambahan Data Pengujian',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        swal.fire(
                            'Error',
                            'Data telah digunakan dalam Transaksi Lain',
                            'warning'
                        );
                    }
                });
                return false;
            });

            $(document).on('keyup change', 'input[name="nama_customer"]', function() {
                var id = $('#form-customer-update').attr('data-id');
                var val = $(this).val();
                if ($(this).val() == "") {
                    $("#msgnama_customer").text("Nama tidak boleh kosong");
                    $('#nama_customer').addClass('is-invalid');
                } else if ($(this).val() != "") {
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        async: false,
                        url: '/api/customer/nama/' + id + '/' + val,
                        success: function(data) {
                            if (data >= 1) {
                                $("#msgnama_customer").text("Nama sudah terpakai");
                                $('#nama_customer').addClass('is-invalid');
                                // $("#btnsimpan").attr("disabled", true);
                            } else {
                                $("#msgnama_customer").text("");
                                $('#nama_customer').removeClass('is-invalid');
                                // if ($('#telepon').val() != "" && $('#npwp').val() != "" && $('#alamat').val() != "" && $('.provinsi').val() != "") {
                                //     $("#btnsimpan").removeAttr("disabled");
                                // } else {
                                //     $("#btnsimpan").attr("disabled", true);
                                // }
                            }
                        }
                    });
                }
                validasi();
            })
            $(document).on('keyup change', 'input[name="telepon"]', function() {
                var id = $('#form-customer-update').attr('data-id');
                if ($(this).val() == "") {
                    $("#msgtelepon").text("Telepon tidak boleh kosong");
                    $("#telepon").addClass('is-invalid');
                    // $("#btnsimpan").attr('disabled', true);
                } else if ($(this).val() != "") {
                    if (!/^[0-9]+$/.test($(this).val())) {
                        $("#msgtelepon").text("Isi nomor telepon dengan angka");
                        $("#telepon").addClass('is-invalid');
                        // $("#btnsimpan").attr('disabled', true);
                    } else {
                        // if (checkTelepon(this.teleponer).value >= 1) {
                        //     this.msg["telepon"] = "Nomor Telepon sudah terpakai";
                        //     this.teleponer = true;
                        //     this.btndis = true;
                        // } else {
                        //     this.msg["telepon"] = "";
                        //     this.teleponer = false;
                        //     this.btndis = false;
                        // }
                        $("#msgtelepon").text("");
                        $("#telepon").removeClass('is-invalid');
                        // $("#btnsimpan").removeAttr('disabled');
                        // if (($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && ($("#npwp").val() != "" && !$("#npwp").hasClass('is-invalid')) && $("#alamat").val() != "") {
                        //     $("#btnsimpan").removeAttr('disabled');
                        // } else {
                        //     $("#btnsimpan").attr('disabled', true);
                        // }
                    }
                }
                validasi();
            })

            $(document).on('keyup change', '#alamat', function() {
                if ($(this).val() != "") {
                    $('#msgalamat').text("");
                    $('#alamat').removeClass("is-invalid");
                    // if (($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && ($("#npwp").val() != "" && !$("#npwp").hasClass('is-invalid')) && $("#telepon").val() != "") {
                    //     $("#btnsimpan").removeAttr('disabled');
                    // } else {
                    //     $("#btnsimpan").attr('disabled', true);
                    // }
                } else {
                    $('#msgalamat').text("Alamat tidak boleh kosong");
                    $('#alamat').addClass("is-invalid");
                    // $("#btnsimpan").attr('disabled', true);
                }

                validasi();
            });

            $(document).on('keyup change', 'input[name="npwp"]', function() {
                if ($(this).val() == "") {
                    $("#msgnpwp").text("NPWP tidak boleh kosong");
                    $('#npwp').addClass('is-invalid');
                } else if ($(this).val() != "") {
                    // if (checkCustomer($('#npwp').val()) >= 1) {
                    //     $("#msgnpwp").text("Nama sudah terpakai");
                    //     $('#npwp").addClass('is-invalid');
                    //     $("#btntambah").attr("disabled", true);
                    // } else {
                    //     $("#msgnpwp").text("");
                    //     $('#npwp").removeClass('is-invalid');
                    //     $("#btntambah").removeAttr("disabled");
                    // }
                    if (!/^[0-9.-]+$/.test($(this).val())) {
                        $('#msgnpwp').text("Masukkan NPWP dengan benar");
                        $('#npwp').addClass("is-invalid");
                        // $("#btnsimpan").attr('disabled', true);
                    } else {
                        $("#msgnpwp").text("");
                        $('#npwp').removeClass('is-invalid');
                        // if ($('#telepon').val() != "" && ($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && $('#alamat').val() != "") {
                        //     $("#btnsimpan").removeAttr("disabled");
                        // } else {
                        //     $("#btnsimpan").attr("disabled", true);
                        // }
                    }
                }
                validasi();
            });
            $(document).on('keyup change', 'input[name="email"]', function() {
                var errorhandling = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if ($(this).val() != "") {
                    if (!errorhandling.test($(this).val())) {
                        $('#msgemail').text("Masukkan email dengan benar");
                        $('#email').addClass("is-invalid");
                        // $("#btnsimpan").attr('disabled', true);
                    } else {
                        $('#msgemail').text("");
                        $('#email').removeClass("is-invalid");
                        // if (($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && ($("#npwp").val() != "" && !$("#npwp").hasClass('is-invalid')) && $("#telepon").val() != "" && $("#alamat").val() != "") {
                        //     $("#btnsimpan").removeAttr('disabled');
                        // }
                    }
                } else {
                    $('#msgemail').text("");
                    $('#email').removeClass("is-invalid");
                    // if (($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && ($("#npwp").val() != "" && !$("#npwp").hasClass('is-invalid')) && $("#telepon").val() != "" && $("#alamat").val() != "") {
                    //     $("#btnsimpan").removeAttr('disabled');
                    // }
                }

                validasi();
            })

            function select_data() {
                $('.provinsi').select2({
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/provinsi/select',
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            console.log(data);
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
            $('#filter').submit(function() {
                var values = [];
                $("input:checked").each(function() {
                    values.push($(this).val());
                });
                if (values != 0) {
                    var x = values;

                } else {
                    var x = ['kosong']
                }
                console.log(x);
                $('#showtable').DataTable().ajax.url('/api/customer/data/' + divisi_id + '/' + x).load();
                return false;
            });
        });
    </script>
@stop
