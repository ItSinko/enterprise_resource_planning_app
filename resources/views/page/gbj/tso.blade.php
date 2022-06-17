@extends('adminlte.page')

@section('title', 'ERP')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <h1>Transfer Produk Gudang Barang Jadi Tanpa SO</h1>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="row">
        <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
        <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary b-radius">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <div class="form-group row top-min">
                                        <label for="" class="col-12 font-weight-bold col-form-label">Tujuan</label>
                                        <div class="col-12">
                                            <select class="form-control ke" name="ke" id="ke">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-primary b-radius">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <form method="post">
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Keterangan</label>
                                            <div class="col-12">
                                                <textarea name="deskripsi" id="deskripsi" class="form-control deskripsi"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Produk</label>
                                            <div class="col-12">
                                                <select class="form-control product" name="gdg_brg_jadi_id" id="gdg_brg_jadi_id">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row top-min">
                                            <label for="" class="col-12 font-weight-bold col-form-label">Stok</label>
                                            <div class="col-12">
                                                <input type="text" id="qtyy"
                                                    class="form-control number-input input-notzero qtyy">
                                                <span class="form-text text-muted">Stok Input Maks : <span id="stock"><b></b></span> </span>
                                                <input type="text" class="stok-gudang" value="20" hidden>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="button" class="btn btn-primary btn-tambah">Tambah</button>
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
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="card card-noborder b-radius">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 table-responsive mb-4">
                            <table class="table table-hover addData">
                                <thead>
                                    <tr>
                                        {{-- <th>Tujuan</th> --}}
                                        <th>Keterangan</th>
                                        <th>Produk</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="tambah_data">
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary btn-simpan" type="button" hidden>Simpan</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Modal Scan Product --}}
<!-- Modal -->
<div class="modal fade modal-produk" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th>Nomor Seri</th>
                            <th><input type="checkbox" id="head-cb"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" id="btnSave">Simpan</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>

    (function ($) {
        $.fn.inputFilter = function (inputFilter) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
            });
        };
    }(jQuery));
    $(document).ready(function () {
        $('.deskripsi').val('')
        $('.qtyy').val('')
        $('.ke').select2({
            placeholder: "Pilih Tujuan",
            allowClear: true
        });
        $('.product').select2({
            placeholder: "Pilih Produk",
            allowClear: true
        });

        $(".number-input").inputFilter(function (value) {
            return /^\d*$/.test(value);
            var value = $(this).val();
        });

        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            // $('.cb-child').prop('checked', isChecked)
            $('.scan-produk').DataTable()
                .column(1)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', isChecked);
        });

        // load produk
        $.ajax({
            url: '/api/gbj/sel-gbj',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    console.log(res);
                    $("#gdg_brg_jadi_id").empty();
                    $("#gdg_brg_jadi_id").append('<option selected></option>');
                    $.each(res, function(key, value) {
                        $("#gdg_brg_jadi_id").append('<option value="'+value.id+'">'+value.produk.nama+' '+value.nama+'</option');
                    });
                } else {
                    $("#gdg_brg_jadi_id").empty();
                }
            }
        });

        $('#gdg_brg_jadi_id').on('change', function() {
            // console.log($(this).val());
            $.ajax({
                url : "{{ URL('/api/tfp/cekStok')}}",
                type: "post",
                data: {gdg_brg_jadi_id: $(this).val()},
                success: function(res) {
                    $('span#stock').text(res.stok_siap);
                }
            })
        })

        // load divisi
        $.ajax({
            url: '/api/gbj/sel-divisi',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    console.log(res);
                    $("#ke").empty();
                    $("#ke").append('<option selected></option>');
                    $.each(res, function(key, value) {
                        $("#ke").append('<option value="'+value.id+'">'+value.nama+'</option');
                    });
                } else {
                    $("#ke").empty();
                }
            }
        });
    });
    let prd1 = {};
    let tmp = [];
    $(document).on('click','.btn-tambah', function (e) {
        e.preventDefault();

        let divisi = $('.ke').val();
        let d_divisi = $('.ke').find(':selected').text();
        let deskripsi = $('.deskripsi').val();
        let produk = $('.product').val();
        let d_produk = $('.product').find(':selected').text();
        let stok = parseInt($('.qtyy').val());
        // let stok_gudang = parseInt($('.stok-gudang').val());

        $.ajax({
            url: "/api/tfp/cekStok",
            type:"POST",
            data: {
                "_token": "{{ csrf_token() }}",
                ke: divisi,
                deskripsi: deskripsi,
                gdg_brg_jadi_id: produk,
                qty: stok,
            },
            success: function (res) {
                console.log(res);
                if(res.stok < stok) {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Stok Tidak Mencukupi',
                        text: 'Stok gudang produk saat ini '+res.stok_siap,
                        confirmButtonText: 'Oke',
                    })
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    addData(divisi, d_divisi, deskripsi, d_produk, produk, stok);
                }
            $('.btn-simpan').prop('hidden', false);
            }
        });
    });

    var i = 0;
    function addData(divisi,d_divisi, deskripsi, d_produk, produk, stok) {
        if (deskripsi.length > 30) {
            var a = deskripsi.substring(0, 10) + '...';
        }else{
            var a = deskripsi;
        }

        i++;
        let tambah_data = '<tr id=row'+i+'><td>'+a+'<input type="hidden" name="deskripsi['+i+']" id="post_deskripsi" value="'+deskripsi+'"></td><td>'+d_produk+'<input type="hidden" name="gdg_brg_jadi_id['+i+']" id="post_produk'+i+'" value="'+produk+'"></td><td>'+stok+'<input type="hidden" name="qty['+i+']" id="post_qty" value="'+stok+'"></td><td><button class="btn btn-primary noseriModal" data-toggle="modal" data-id="'+produk+'" data-ke="'+divisi+'" data-desk="'+deskripsi+'" data-qty="'+stok+'"><i class="fas fa-qrcode"></i> Scan Produk</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Hapus</button></td></tr>'
        $('tbody.tambah_data').append(tambah_data);


    }
    $(document).on('click', '.btn-delete', function(e){
        e.preventDefault();
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
        if(check != 0){
            $('.btn-simpan').prop('hidden', false);
        }else{
            $('.btn-simpan').prop('hidden', true);
        }
    });

    function make_temp_array(prd1) {
        let result = {};
        console.log("func", prd1)
        for (const dpp in prd1) {
            for (let i = 0; i < prd1[dpp].noseri.length; i++) {
                result[prd1[dpp].noseri[i]] = dpp
            }
        }
        console.log("res", result)
        return result;
    }

    var prd = '';
    var jml = '';
    var idd = '';
    var tujuan = '';
    let desk = '';
    $(document).on('click', '.noseriModal', function(e) {
        var tr = $(this).closest('tr');
        jml = $(this).data('qty');
        desk = $(this).data('desk');
        tujuan = $(this).data('ke');
        idd = $(this).data('id');
        let temp_array = make_temp_array(prd1);

        $('.scan-produk').DataTable({
            processing: false,
            serverSide: false,
            autoWidth: false,
            destroy: true,
            stateSave: true,
            lengthChange: false,
            ajax: {
                url: "/api/tfp/noseri",
                type: "post",
                data: {gbj: idd},
            },
            columns: [
                { data: 'noseri', name: 'noseri'},
                // { data: 'checkbox', name: 'checkbox', orderable: 'false'},
                {
                    data(data) {
                        // console.log(data.ids, temp_array[data.ids]);
                        if (temp_array[data.ids] !== undefined) {
                            if (temp_array[data.ids] == idd)
                                return `<input type="checkbox" class="cb-child" name="noseri_id[][]"  value="${data.ids}" checked>`;
                            else
                                return '<span class="badge badge-info">Sudah Digunakan</span>'
                        } else {
                            if (data.is_change == 0) {
                                return `<span class="badge badge-warning">Noseri Tidak Bisa Digunakan</span>`
                            } else {
                                return `<input type="checkbox" class="cb-child" name="noseri_id[][]"  value="${data.ids}">`
                            }
                            // return `<input type="checkbox" class="cb-child" name="noseri_id[][]"  value="${data.ids}">`
                        }
                    }
                },
            ],

            'select': {
                'style': 'multi'
            },

            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $('.modal-produk').modal('show');
    })

    $('.scan-produk').on('click', '.cb-child',function (){
        if ($(this).is(':checked')) {
            if (prd1.length < 0 || prd1[idd] == undefined) {
                if (tmp.includes($(this).val())) {
                    console.log("sudah ada")
                } else {
                    tmp.push($(this).val())
                    if(tmp.length > jml){
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Jumlah Nomor Seri Melebihi Batas',
                            type: 'error',
                            timer: 1000
                        })
                        tmp.pop()
                        $(this).prop('checked', false);
                    }
                }
            } else {
                for (nomorseri in prd1[idd]){
                    if(nomorseri == "noseri"){
                     prd1[idd][nomorseri].push($(this).val())
                     if (prd1[idd][nomorseri].length > jml) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Jumlah Nomor Seri Melebihi Batas',
                                type: 'error',
                                timer: 1000
                            })
                            prd1[idd][nomorseri].pop();
                            $(this).prop('checked', false);
                        }
                    }
                }
            }
        } else {
            if (prd1.length < 0 || prd1[idd] == undefined) {
                tmp.splice($.inArray($(this).val(), tmp), 1);
            }else{
                for (nomorseri in prd1[idd]){
                    if(nomorseri == "noseri"){
                        prd1[idd][nomorseri].splice($.inArray($(this).val(), prd1[idd][nomorseri]), 1)
                    }
                }
            }
        }
        console.log("tmp", tmp)
        console.log("prd1", prd1)
    })

    $(document).on('click', '#btnSave', function() {
        // console.log(tmp);
        let checked = $('.scan-produk').DataTable().column(1).nodes().to$().find('input[type=checkbox]:checked').map(function () {
            return $(this).val();
        }).get();
        if (checked.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Tidak Ada data yang dipilih'
            })
        } else if (checked.length > jml) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Batas Maksimal '+jml+' Barang!'
            })
        } else {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Noseri Berhasil Disimpan',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                if (prd1.length < 0 || prd1[idd] == undefined) {
                    prd1[idd] = {
                        'desk': desk,
                        'qty': jml,
                        'tujuan' : $('.ke').val(),
                        'noseri': [...new Set(tmp)]
                    }
                } else {
                    console.log("prd1", prd1);
                }
                tmp = [];
                if (prd1[idd].noseri.length == 0) {
                    delete prd1[idd]
                }
                console.log(prd1);
                $('.modal-produk').modal('hide');
            })
        }
    })

    $(document).on('click', '.btn-simpan', function(e) {
        e.preventDefault();

        // let a = $('#post_ke').val();
        // let b = $('#post_deskripsi').val();
        // let c = $('#post_produk').val();
        // let d = parseInt($('#post_qty').val());
        // let stok_gudang = parseInt($('.stok-gudang').val());

        // let ke = [];
        // let desk = [];
        // let gdg = [];
        // let stok_push = [];

        // $('input[name^="ke"]').each(function() {
        //     ke.push($(this).val());
        // });

        // $('input[name^="deskripsi"]').each(function() {
        //     desk.push($(this).val());
        // });

        // $('input[name^="gdg_brg_jadi_id"]').each(function() {
        //     gdg.push($(this).val());
        // });

        // $('input[name^="qty"]').each(function() {
        //     // seri[id].qty = stok_push;
        //     stok_push.push($(this).val());
        // });

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, transfer it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $(this).prop('disabled', true);
                $.ajax({
                    url: "/api/tfp/create",
                    type:"POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        userid: $('#userid').val(),
                        data: prd1
                    },
                    success: function (res) {
                        Swal.fire(
                            'Sukses!',
                            'Data Berhasil Ditransfer',
                            'success'
                        ).then(() => {
                            location.reload();
                        })
                    }
                });

            }
        })


    })


</script>
@stop
