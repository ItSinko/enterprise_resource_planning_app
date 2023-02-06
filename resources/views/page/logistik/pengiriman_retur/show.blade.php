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
                        <li class="breadcrumb-item active">Pengiriman</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .hide {
            display: none !important;
        }

        .margin {
            margin: 5px;
        }

        .align-center {
            text-align: center;
        }

        .removeshadow {
            box-shadow: none;
        }

        .nowraps {
            white-space: nowrap;
        }
        #profileImage {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #4682B4;
            font-size: 12px;
            color: #fff;
            text-align: center;
            line-height: 60px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }
        @media screen and (min-width: 1220px) {

            body {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .labelket {
                text-align: right;
            }

            .overflowy {
                max-height: 330px;
                overflow-y: scroll;
                box-shadow: none;
            }

            .cust {
                max-width: 40%;
            }

        }

        @media screen and (max-width: 1219px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: right;
            }

            .overflowy {
                max-height: 330px;
                overflow-y: scroll;
                box-shadow: none;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 991px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: left;
            }

            .margin-md {
                margin-top: 10px;
            }

            .align-md {
                text-align: center;
            }

            .overflowy {
                max-height: 455px;
                overflow-y: scroll;
                box-shadow: none;
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
                            <a href="{{ route('as.pengiriman.create') }}" type="button" class="btn btn-info float-right my-2"><i
                                    class="fas fa-plus"></i> Tambah</a>
                            <div class="table-responsive">
                                <table class="table table-hover" id="showtable" style="text-align: center; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Surat Jalan</th>
                                            <th>Tgl Pengiriman</th>
                                            <th>No Retur</th>
                                            <th>Customer</th>
                                            <th>Pengirim/Ekspekdisi</th>
                                            <th>Status</th>
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
                <div class="modal fade" id="detail_modal" role="dialog" aria-labelledby="detail_modal" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header">
                                <h4 class="modal-title">Detail Pengiriman</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="detail">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="edit_modal" role="dialog" aria-labelledby="edit_modal" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Pengiriman</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="edit">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('adminlte_js')
    <script>
        $(function() {
            function identitas_pengirim(){
                if($("#pilih_pengiriman:checked").val() != undefined){
                    $('#ekspedisi_id').val(null).trigger('change');
                    $("#input-ekspedisi_id").addClass('hide');
                    $('#non_ekspedisi').removeClass('hide');
                }else{
                    $('#non_ekspedisi').val('');
                    $("#input-ekspedisi_id").removeClass('hide');
                    $('#non_ekspedisi').addClass('hide');
                }
            }

            $(document).on('change', '#pilih_pengiriman', function(){
                identitas_pengirim();
            });

            function select_ekspedisi(){
                $('#ekspedisi_id').select2({
                    placeholder: "Pilih Ekspedisi",
                    minimumResultsForSearch: 20,
                    ajax: {
                        dataType: 'json',
                        delay: 250,
                        type: 'GET',
                        url: '/api/logistik/ekspedisi/select/0',
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
                });
            }

            function no_kolom(table){
                table.on('order.dt search.dt', function() {
                    table.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }

            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                ajax: {
                    'url': '/api/as/pengiriman/data',
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
                        data: null,
                    },
                    {
                        data: 'no_pengiriman',
                        className: 'nowraps align-center'
                    },
                    {
                        data: null,
                        className: 'nowraps align-center',
                        render: function(data, type, row) {
                            if(data.tanggal != null){
                                return moment(new Date(row.tanggal).toString()).format('DD-MM-YYYY');
                            }
                            else{
                                return '';
                            }
                        }
                    },
                    {
                        data: 'no_retur',
                        className: 'nowraps align-center'
                    },
                    {
                        data: 'customer_id',
                        className: 'nowraps align-center'
                    },
                    {
                        data: null,
                        className: 'nowraps align-center',
                        render: function(data, type, row) {
                            if (row.ekspedisi_id != null) {
                                return row.ekspedisi_id;
                            } else {
                                return row.pengirim;
                            }
                        }
                    },
                    {
                        data: null,
                        className: 'nowraps align-center',
                        render: function(data, type, row) {
                            if (row.status == "Belum Terkirim") {
                                return '<span class="red-text badge">' + row.status +'</span>';
                            } else {
                                return '<span class="green-text badge">' + row.status +'</span>';
                            }
                        }
                    },
                    {
                        data: null,
                        className: 'nowraps align-center',
                        render: function(data, type, row) {
                            var data = '';
                            if(row.status == "Belum Terkirim"){
                                if(row.no_pengiriman != null){
                                    data += `<button class="btn btn-outline-primary btn-sm" id="kirimbutton"><i class="fas fa-paper-plane"></i> Kirim</button> `;
                                }
                                data += `<a data-toggle="editmodal" data-target="#editmodal" class="editmodal" id="editmodal"><button type="button" class="btn btn-warning btn-sm"><i class="fas fa-pencil"></i> Edit</button></a> `;
                            }
                            data += `<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" id="detailmodal"><button type="button" class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i> Detail</button></a>`;
                            return data;
                        }
                    },
                ],
                columnDefs: [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }, ],
                order: [
                    [1, 'asc']
                ],
            });

            no_kolom(showtable);

            $(document).on('click', '#kirimbutton', function(){
                var rows = showtable.rows($(this).parents('tr')).data();
                var id = rows[0]['id'];
                var ekspedisi_id = rows[0]['ekspedisi_id'];
                if(ekspedisi_id == null){
                    Swal.fire({
                        title: 'Kirim Barang?',
                        text: "Setelah klik Kirim Barang, Anda tidak bisa merubahnya kembali",
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Kirim Barang'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "POST",
                                url: "{{ route('as.pengiriman.send') }}",
                                data: {'id': id },
                                beforeSend: function() {
                                    $('#loader').show();
                                },
                                success: function(response) {
                                    if (response['res'] == "success") {
                                        swal.fire(
                                            'Berhasil',
                                            response['msg'],
                                            'success'
                                        );
                                        $('#showtable').DataTable().ajax.reload();
                                    } else if (response['res'] == "error") {
                                        swal.fire(
                                            'Gagal',
                                            response['msg'],
                                            'error'
                                        );
                                    }
                                },
                                complete: function() {
                                    $('#loader').hide();
                                },
                                error: function(xhr, status, error) {
                                    alert(error);
                                }
                            });
                        }
                        else{
                            swal.fire(
                                'Batal',
                                'Batal melakukan pengiriman',
                                'error'
                            );
                        }
                    })
                }
                else{
                    const { value: resi } = Swal.fire({
                        title: 'No Resi',
                        input: 'text',
                        inputPlaceholder: 'Masukkan No Resi dari Pengiriman ini',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Kirim'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "POST",
                                url: "{{ route('as.pengiriman.send') }}",
                                data: {'id': id, 'resi': result.value},
                                beforeSend: function() {
                                    $('#loader').show();
                                },
                                success: function(response) {
                                    if (response['res'] == "success") {
                                        swal.fire(
                                            'Berhasil',
                                            response['msg'],
                                            'success'
                                        );
                                        $('#showtable').DataTable().ajax.reload();
                                    } else if (response['res'] == "error") {
                                        swal.fire(
                                            'Gagal',
                                            response['msg'],
                                            'error'
                                        );
                                    }
                                },
                                complete: function() {
                                    $('#loader').hide();
                                },
                                error: function(xhr, status, error) {
                                    alert(error);
                                }
                            });
                        }
                        else{
                            swal.fire(
                                'Batal',
                                'Batal melakukan pengiriman',
                                'error'
                            );
                        }
                    })
                }
            });

            $(document).on('submit', '#formsavenosj', function(e){
                e.preventDefault();
                var action = $(this).attr('action');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: $('#formsavenosj').serialize(),
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
                                response['msg'],
                                'success'
                            ).then(function() {
                                location.reload(true);
                            });
                            $("#editmodal").modal('hide');
                            $('#showtable').DataTable().ajax.reload();

                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                response['msg'],
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error, response) {
                        alert(error);
                    }
                });
            })

            function cust_image(cust_name){
                var cust = cust_name;
                var cust = cust.replace('.', '').replace('PT ', '').replace('CV ', '').replace('& ', '').replace('(',
                    '').replace(')', '');
                var init = cust.split(" ");
                var initial = "";
                for (var i = 0; i < init.length; i++) {
                    initial = initial + init[i].charAt(0);
                }
                var profileImage = $('#profileImage').text(initial);
            }

            $(document).on('click', "#detailmodal", function(event) {
                var rows = showtable.rows($(this).parents('tr')).data();
                var id = rows[0]['id'];
                event.preventDefault();
                $.ajax({
                    url: "/as/pengiriman/detail",
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#detail_modal').modal("show");
                        $('#detail').html(result).show();
                        pengiriman_detail(id);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            $(document).on('click', "#editmodal", function(event) {
                var rows = showtable.rows($(this).parents('tr')).data();
                var id = rows[0]['id'];
                event.preventDefault();
                $.ajax({
                    url: "/logistik/pengiriman_retur/edit",
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#edit_modal').modal("show");
                        $('#edit').html(result).show();
                        select_ekspedisi();
                        pengiriman_edit(id);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            function noseri_table(produk){
                if(produk != null){
                var noseritable = $('#noseritable').DataTable({
                    destroy: true,
                    data: produk,
                    columns: [
                        {
                            data: null,
                            className: 'align-center'
                        },
                        {
                            data: "nama",
                            className: 'nowraps align-center',
                        },
                        {
                            data: "noseri",
                            className: 'nowraps align-center',
                        },
                    ],
                });

                no_kolom(noseritable);
                }
                else{
                    $('#noseritable').DataTable({
                        destroy: true
                    });
                }
            }
            function part_table(part){
                if(part != null){
                    var parttable = $('#parttable').DataTable({
                        destroy: true,
                        data: part,
                        columns: [
                            {
                                data: null,
                                className: 'align-center'
                            },
                            {
                                data: "nama",
                                className: 'nowraps align-center',
                            },
                            {
                                data: "jumlah",
                                className: 'nowraps align-center',
                            },
                        ],
                    });
                    no_kolom(parttable);
                }
                else{
                    $('#parttable').DataTable({
                        destroy: true
                    });
                }
            }

            function pengiriman_detail(id){
                $.ajax({
                    url: "/api/as/pengiriman/detail",
                    dataType: 'json',
                    type: 'GET',
                    data: {'id': id},
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        var date_pengiriman = "";
                        if(result.tgl_pengiriman){
                            var date_pengiriman = result.tgl_pengiriman;
                            var [year, month, day] = date_pengiriman.split('-');
                        }

                        var date_retur = result.tgl_retur;
                        var [year_r, month_r, day_r] = date_retur.split('-');

                        $('#no_pengiriman').html(result.no_pengiriman ? result.no_pengiriman : '-');
                        $('#tgl_pengiriman').html(date_pengiriman != "" ? day+'-'+month+'-'+year : '-');
                        $('#ekspedisi_id').html(result.ekspedisi_id ? result.ekspedisi_id : '-');
                        $('#biaya_kirim').html(result.biaya_kirim ? result.biaya_kirim : '-');
                        $('#no_resi').html(result.no_resi);

                        $('#nama_penerima').html(result.nama_penerima);
                        $('#alamat_penerima').html(result.alamat_penerima);
                        $('#telp_penerima').html(result.telp_penerima != null ? result.telp_penerima : '-');
                        cust_image(result.customer_id);
                        $('#customer').html(result.customer_id);
                        $('#alamat').html(result.alamat);
                        $('#telp').html(result.telp);

                        $('#no_retur').html(result.no_retur);
                        $('#tgl_retur').html(day_r+'-'+month_r+'-'+year_r);
                        $('#jenis_retur').html(result.jenis);
                        $('#keterangan').html(result.keterangan);

                        noseri_table(result.produk);
                        part_table(result.part);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            }

            function pengiriman_edit(id){
                $.ajax({
                    url: "/api/as/pengiriman/detail",
                    dataType: 'json',
                    type: 'GET',
                    data: {'id': id},
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#id').val(result.id);
                        $('#no_surat_jalan').val(result.no_pengiriman != '' ? result.no_pengiriman : '-');
                        $('#tgl_pengiriman').val(result.tgl_pengiriman != '' ? result.tgl_pengiriman : '');

                        if(result.pengirim != '-'){
                            console.log(result.pengirim);
                            $('#input-ekspedisi_id').addClass('hide');
                            $('#non_ekspedisi').removeClass('hide');
                            $('#pilih_pengiriman').attr('checked', true);
                            $('#non_ekspedisi').val(result.pengirim != null ? result.pengirim : '');
                            $('#ekspedisi_id').val(null).trigger('change');
                        } else {
                            $('#input-ekspedisi_id').removeClass('hide');
                            $('#non_ekspedisi').addClass('hide');
                            $('#pilih_pengiriman').attr('checked', false);
                            $('#non_ekspedisi').val('');
                            $("#ekspedisi_id").append('<option value="'+result.id_ekspedisi+'" selected>'+result.ekspedisi_id+'</option>');
                        }
                        $('#biaya_kirim').val(result.biaya_kirim ? result.biaya_kirim : '-');

                        $('#nama_penerima_edit').html(result.nama_penerima);
                        $('#alamat_penerima_edit').html(result.alamat_penerima);
                        $('#telp_penerima_edit').html(result.telp_penerima != null ? result.telp_penerima : '-');

                        noseri_table(result.produk);
                        part_table(result.part);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            }

            $(document).on('keyup change', '#no_surat_jalan', function(){
                if ($(this).val() != "") {
                    var val = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: '/api/logistik/cek/no_sj/0/' + val ,
                        dataType: 'json',
                        success: function(data) {
                            if (data > 0) {
                                $('#no_surat_jalan').addClass('is-invalid');
                                $('#msg_no_sj').text("No Surat Jalan sudah terpakai");
                            } else {
                                $('#no_surat_jalan').removeClass('is-invalid');
                                $('#msg_no_sj').text("");
                            }
                        },
                        error: function() {
                            alert('Error occured');
                        }
                    });
                } else if ($(this).val() == "") {
                    $('#no_surat_jalan').addClass('is-invalid');
                    $('#msg_no_sj').text("No Surat Jalan tidak boleh kosong");
                }
            });
        })
    </script>
@endsection
