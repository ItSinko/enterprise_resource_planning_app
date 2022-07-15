@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Produk dari Perakitan</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="card">
    <!-- /.card-header -->
    <div class="card-body">
        <table class="table table-bordered dalam-perakitan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor BPPB</th>
                    <th>Tanggal Masuk</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<!-- Modal Detail-->
<div class="modal fade terima-produk" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
    <input type="hidden" name="" id="gbjid" value="">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk <span id="title">AMBULATORY BLOOD PRESSURE MONITOR</span></b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout">Ubah Layout</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanseri">Simpan</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade detail-layout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk <span id="titlee">AMBULATORY BLOOD PRESSURE MONITOR</span></b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-seri">
                    <thead>
                        <tr>
                            <th>No Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Ubah Layout-->
<div class="modal fade" id="ubah-layout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Layout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Layout</label>
                    <select name="" id="change-layout" class="form-control">
                        @foreach ($layout as $l)
                        <option value="{{ $l->id }}">{{ $l->ruang }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" onclick="ubahData()">Simpan</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

@stop

@section('adminlte_js')
<script>
    $('.dalam-perakitan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/tfp/rakit',
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {data: 'bppb'},
            {
                data: 'tgl_masuk'
            },
            {
                data: 'product'
            },
            {
                data: 'jumlah'
            },
            {
                data: 'action'
            }
        ],
        order: [
                [1, 'desc']
            ],
        "language": {
            // "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            processing: "<span class='fa-stack fa-md'>\n\
                                        <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                                </span>&emsp;Mohon Tunggu ...",
        }
    });


    $(document).ready(function () {
        $('#head-cb').prop('checked', false);
        $('.terimaProduk').click(function (e) {
            $('.terima-produk').modal('show');
        });

        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            // $('.cb-child').prop('checked', isChecked)
            $('.scan-produk').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', isChecked);
        });
    });

    function ubahData() {
        let checkbox_terpilih = $('.scan-produk').DataTable().column(0).nodes()
                .to$().find('input[type=checkbox]:checked');
        let layout = $('#change-layout').val();
        $.each(checkbox_terpilih, function (index, elm) {
            let b = $(checkbox_terpilih).parent().next().next().children().val(layout);
        });
        $('#ubah-layout').modal('hide');
    }

    var nama = '';
    $(document).on('click', '.editmodal', function () {
        var id = $(this).data('id');
        nama = $(this).data('produk');
        // var tipe = $(this).data('var');
        var tgl = $(this).data('tgl');
        var brgid = $(this).data('brgid');
        console.log(id);
        $('#gbjid').val(brgid)
        // $('span#title').text(nama.concat(' ', tipe));
        $('span#title').text(nama);

        $('.scan-produk').DataTable().destroy();
        $('.scan-produk').DataTable({
            serverSide: false,
            autoWidth: false,
            processing: true,
            "ordering": false,
            stateSave: true,
            'bPaginate': true,
            ajax: {
                url: '/api/tfp/rakit-terima/' + brgid + '/' + tgl,
            },
            columns: [{
                    data: 'checkbox'
                },
                {
                    data: 'noserii'
                },
                {
                    data: 'layout'
                },
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });

        $.ajax({
            url: '/api/gbj/sel-layout',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res) {
                    console.log(res);
                    $("#layout_id").empty();
                    $.each(res, function (key, value) {
                        $("#layout_id").append('<option value="' + value.id + '">' + value
                            .ruang + '</option');
                    });
                } else {
                    $("#layout_id").empty();
                }
            }
        });

        openModalTerima();
    });

    $(document).on('click', '.detailmodal', function () {
        var id = $(this).data('id');
        var nama = $(this).data('produk');
        var tipe = $(this).data('var');
        var tgl = $(this).data('tgl');
        var brgid = $(this).data('brgid');
        console.log(tgl);
        $('#gbjid').val(brgid)
        $('span#titlee').text(nama.concat(' ', tipe));

        $('.table-seri').DataTable().destroy();
        $('.table-seri').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '/api/tfp/rakit-noseri/' + brgid + '/' + tgl,
            },
            columns: [{
                    data: 'noserii'
                },
                {
                    data: 'layout'
                },
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });

        openModalView();
    });

    function openModalTerima() {
        $('.terima-produk').modal('show');
    }

    function openModalView() {
        $('.detail-layout').modal('show');
    }

    // submit
    $('#form-terima').on('submit', function(e) {
        console.log('test');
    })
    let arr = {};
    $(document).on('click', '#simpanseri', function () {
        let no_seri = [];
        let layout = [];
        let a = $('.scan-produk').DataTable().column(0).nodes()
            .to$().find('input[type=checkbox]:checked');
        $(a).each(function (index, elm) {
            arr[$(elm).val()] = $(elm).parent().next().next().children().val()
            no_seri.push($(elm).val());
            layout.push($(elm).parent().next().next().children().val());
        });

        if (no_seri == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No Seri tidak boleh kosong!',
            });
        } else if (layout == '') {
           Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Layout tidak boleh kosong!',
            });
        } else {
            Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Produk yang anda terima akan diubah layoutnya",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Terima!'
        }).then((result) => {
            if (result.value) {
                // $(this).prop('disabled', true);
                // Swal.fire({
                //         title: 'Please wait',
                //         text: 'Data is transferring...',
                //         allowOutsideClick: false,
                //         showConfirmButton: false
                //     });
                $.ajax({
                    url: "/api/prd/terimaseri",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        userid: $('#userid').val(),
                        // seri: no_seri,
                        produk: nama,
                        // layout: layout,
                        gbj: $('#gbjid').val(),
                        data: arr,
                    },
                    success: function (res) {
                        console.log(res);
                        // if (res.msg == 'Successfully') {
                        //     Swal.fire(
                        //         'Terima!',
                        //         'Produk berhasil diterima',
                        //         'success'
                        //     )
                        //     $('.terima-produk').modal('hide');
                        //     location.reload();
                        // } else {
                        //     Swal.fire(
                        //         'Gagal!',
                        //         'Produk gagal diterima',
                        //         'error'
                        //     )
                        // }
                    }
                })
            }
        })
        }
    })

</script>
@stop
