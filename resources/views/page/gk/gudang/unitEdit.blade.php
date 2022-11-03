@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
    <style>
        .nomor-so {
            background-color: #717FE1;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 18px
        }

        .nomor-akn {
            background-color: #DF7458;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 18px
        }

        .nomor-po {
            background-color: #85D296;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 18px
        }

        .instansi {
            background-color: #36425E;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 18px
        }

        .sudah_ditransfer {
            float: left;
            width: auto;
            padding: 5px;
            margin-top: 5px;
            border: 1px solid #AEE1FC;
            background-color: #AEE1FC;
            color: #5170FD;
            font-size: 14px;
            border-radius: 6px;
        }

        .belum_diterima {
            float: left;
            width: auto;
            padding: 5px;
            margin-top: 5px;
            border: 1px solid #FFE2E5;
            background-color: #FFE2E5;
            color: #F7616B;
            font-size: 14px;
            border-radius: 6px;
        }

        .sudah_diterima {
            float: left;
            width: auto;
            padding: 5px;
            margin-top: 5px;
            border: 1px solid #C9F7F5;
            background-color: #C9F7F5;
            color: #1CC7CD;
            font-size: 14px;
            border-radius: 6px;
        }
    </style>
    <input type="hidden" name="" id="auth" value="{{ Auth::user()->Karyawan->divisi_id }}">

    <div class="row">
        <div class="col-sm-1">
            {{-- <a href="javascript:;" onclick = "history.back()">Redirect back to Page 1</a> --}}
            {{-- <a href="javascript:history.back()"><i class="fas fa-arrow-left"></i></a> --}}
            <button class="btn btn-secondary btnBack" type="button"><i class="fas fa-arrow-left"></i></button>
        </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        @foreach ($header1 as $h)
                            <div class="col-sm">
                                <div class="row">
                                    {{-- col --}}
                                    <input type="hidden" name="" id="id" value="{{ $h->gbj_id }}">
                                    <div class="col"> <label for="">Kode Unit</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                -
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nama Unit</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                {{ $h->nama }} {{ $h->variasi }}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Jumlah</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                {{ $h->jml }} {{ $h->satuan }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table_edit_sparepart">
                        <thead class="thead-light">
                            <tr>
                                <th colspan="2" class="text-center">Tanggal</th>
                                <th colspan="2" class="text-center">Tujuan</th>
                                <th rowspan="2">No Seri</th>
                                <th rowspan="2">No Seri Baru</th>
                                <th rowspan="2">Posisi Barang</th>
                                <th rowspan="2">Kerusakan</th>
                                <th rowspan="2">Perbaikan</th>
                                {{-- <th rowspan="2">Tingkat Kerusakan</th> --}}
                                <th rowspan="2">Hasil Jadi</th>
                                <th rowspan="2">Status</th>
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Keluar</th>
                                <th>Dari</th>
                                <th>Ke</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>WESRUF9835734958</td>
                                <td><span class="badge badge-warning">Sudah Ditransfer</span></td>
                                <td><button class="btn btn-outline-success lihatData"><i class="fa fa-eye"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade changeStatus">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col"> <label for="">No Seri</label>
                                    <div class="card nomor-so">
                                        <div class="card-body" id="nose">
                                            89798797856456
                                        </div>
                                    </div>
                                </div>
                                {{-- col --}}
                                <div class="col"> <label for="">Tanggal</label>
                                    <div class="card nomor-akn">
                                        <div class="card-body">
                                            <p class="card-text" id="in">Sudah Ditransfer</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" id="myForm" name="myForm">
                                <input type="hidden" name="id" id="kode">
                                <input type="hidden" name="userid" id="user_id" value="{{ Auth::user()->id }}">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="">Layout</label>
                                        <select name="layout_id" id="layout_id" class="form-control layout_edit">
                                            @foreach ($layout as $l)
                                                <option value="{{ $l->id }}">{{ $l->ruang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="">Tingkat Kerusakan</label>
                                        <select name="tk_kerusakan" id="tk_kerusakan" class="form-control kerusakan_edit">
                                            <option value="1">Level 1</option>
                                            <option value="2">Level 2</option>
                                            <option value="3">Level 3</option>
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="">Hasil Jadi</label>
                                        <select name="hasil_jadi" id="varian" class="form-control varian">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group fixidCol">
                                    <label for="">Perubahan Nomor Seri Baru</label>
                                    <input type="text" class="form-control fixid" name="noseri_fix">
                                    {{-- <select name="noseri_fix" class="form-control fixid">
                            </select> --}}
                                </div>
                                <div class="form-group">
                                    <label for="">Kerusakan</label>
                                    <textarea name="remark" id="remark" cols="5" rows="5" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Perbaikan</label>
                                    <textarea name="perbaikan" id="perbaikan" cols="5" rows="5" class="form-control"></textarea>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade editDetail" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <label for="">No Seri</label>
                                    <div class="card nomor-so">
                                        <div class="card-body" id="nose">89798797856456</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="">Tanggal Masuk & Keluar</label>
                                    <div class="card-group">
                                        <div class="card nomor-po">
                                            <div class="card-body" id="tgl_in">23-05-2022</div>
                                        </div>
                                        <div class="card nomor-po">
                                            <div class="card-body" id="tgl_out">24-05-2022</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="">Dari</label>
                                    <div class="card nomor-akn">
                                        <div class="card-body" id="from">Gudang Penjualan</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="">Ke</label>
                                    <div class="card instansi">
                                        <div class="card-body" id="to">After Sales</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <label for="">Kerusakan</label>
                            <textarea name="" id="kerusakan" cols="30" rows="5" class="form-control"></textarea>
                            <label for="">Perbaikan</label>
                            <textarea name="" id="perbaikan" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary buttonSimpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>

@stop
@section('adminlte_js')
    <script>
        let varian = []
        var access_token = localStorage.getItem('lokal_token');
        if (access_token == null) {
            Swal.fire({
                title: 'Session Expired',
                text: 'Silahkan login kembali',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.preventDefault();
                    document.getElementById('logout-form').submit();
                }
            })
        }
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '/api/gbj/sel-gbj',
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                },
                success: function(data) {
                    console.log(data)
                    $.map(data, function(item) {
                        varian.push({
                            id: item.id,
                            text: item.produk.nama + ' ' + item.nama
                        })
                    });
                }
            });
        });

        // disable button back browser
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };

        function changeStatus() {
            $('.varian').select2({
                placeholder: "Pilih Unit",
                allowClear: true,
                minimumInputLength: 2,
                minimumResultsForSearch: 10,
                data: varian,
                dropdownParent: $('.changeStatus')
            });
            $('.changeStatus').modal('show');
        }
        $('.layout_edit').select2({
            dropdownParent: $('.changeStatus')
        });
        $('.kerusakan_edit').select2({
            dropdownParent: $('.changeStatus')
        });

        $('.btnBack').click(function() {
            window.location.href = '{{ url(' / gk / gudang ') }}'
        })
        var id = $('#id').val();
        console.log(id);
        $('.table_edit_sparepart').dataTable({
            destroy: true,
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/gk/his-unit/" + id,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                }
            },
            columns: [{
                    data: 'inn'
                },
                {
                    data: 'out'
                },
                {
                    data: 'from'
                },
                {
                    data: 'to'
                },
                {
                    data: 'noser'
                },
                {
                    data: 'noseri_new'
                },
                {
                    data: 'layout'
                },
                {
                    data: 'remarks'
                },
                {
                    data: 'perbaikan'
                },
                // {data: 'tingkat'},
                {
                    data: 'unit_baru'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action'
                },
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
            "columnDefs": [{
                "targets": [9],
                "visible": document.getElementById('auth').value == '2' ? false : true
            }]
        });
        var id = '';
        var gbj = '';
        var status = '';
        $(document).on('click', '.unitmodal', function() {
            id = $(this).data('id');
            // gbj = $(this).data('gbj');
            status = $(this).data('status');
            // var route = "{{ route('autocom') }}";
            // $('.fixid').select2({
            //     placeholder: "Pilih Noseri",
            //     minimumInputLength: 2,
            //     minimumResultsForSearch: 10,
            //     ajax: {
            //         url: route,
            //         data: function(params) {
            //             return {
            //                 search: params.term,
            //                 id: gbj
            //             }
            //         },
            //         dataType: 'json',
            //         delay: 250,
            //         processResults: function (data) {
            //             return {
            //                 results: $.map(data, function (item) {
            //                     return {
            //                         text: item.noseri,
            //                         id: item.id
            //                     }
            //                 })
            //             };
            //         },
            //     },
            //     cache: true,
            //     allowClear: true,
            //     width: '100%'
            // });
            if (status == 1) {
                $('.fixidCol').show()
            } else {
                $('.fixidCol').hide()
            }

            $.ajax({
                url: "/api/gk/noseri/" + id,
                type: "get",
                dataType: "json",
                success: function(res) {
                    $('div#nose').text(res.noser);
                    $('p#in').text(res.in);
                    $('p#out').text(res.out);
                }
            })

            $.ajax({
                url: "/api/gk/detailseri",
                type: "post",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(res) {
                    console.log(res);
                    $('#kode').val(res.id);
                    $('#layout_id').val(res.layout);
                    $('#layout_id').select2().trigger('change');
                    $('.varian').val(res.hasiljadi);
                    $('.varian').select2().trigger('change');
                    $('.fixid').val(res.noseri);
                    // $('.fixid').select2().trigger('change');
                    $('#tk_kerusakan').val(res.tingkat);
                    $('#tk_kerusakan').select2().trigger('change');
                    $('#remark').val(res.note);
                    $('#perbaikan').val(res.repair);
                }
            })

            changeStatus();
        })

        $('body').on('submit', '#myForm', function(e) {

            e.preventDefault();
            var actionType = $('#btnSave').val();
            $('#btnSave').html('Sending..');
            $('#btnSave').attr('disabled', true);
            Swal.fire({
                title: 'Please wait',
                text: 'Data is transferring...',
                allowOutsideClick: false,
                showConfirmButton: false
            });
            var formData = new FormData(this);
            if (status == 1) {
                $.ajax({
                    type: 'POST',
                    url: "/api/v2/gk/checkSeriNew",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        if (data.status == 'success') {
                            $.ajax({
                                type: 'POST',
                                url: "/api/gk/ubahunit",
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: (data) => {
                                    // console.log(data);
                                    $('#myForm').trigger('reset');
                                    $('.changeStatus').modal('hide');
                                    $('#btnSave').html('Kirim');
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: data.msg,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $('.table_edit_sparepart').DataTable().ajax.reload();
                                    // location.reload();
                                },
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.msg,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#btnSave').html('Save Changes');
                            $('#btnSave').attr('disabled', false);
                        }
                    }
                })
            } else {
                $.ajax({
                    type: 'POST',
                    url: "/api/gk/ubahunit",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        // console.log(data);
                        $('#myForm').trigger('reset');
                        $('.changeStatus').modal('hide');
                        $('#btnSave').html('Kirim');
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: data.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('.table_edit_sparepart').DataTable().ajax.reload();
                        location.reload();
                    }
                });
            }


        })

        $(document).on('click', '.editData', function() {
            $('.editDetail').modal('show');
        });

        $(document).on('click', '.buttonSimpan', function() {
            let nomorseri = $('#nose').text();
            let tgl_in = $('#tgl_in').text();
            let tgl_out = new Date();
            let kerusakan = $('#kerusakan').val();
            let perbaikan = $('#perbaikan').val();
            let data = {
                nomorseri: nomorseri.trim(),
                tgl_in: tgl_in.trim(),
                tgl_out: tgl_out,
                kerusakan: kerusakan,
                perbaikan: perbaikan
            }
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil disimpan!',
                showConfirmButton: false,
                timer: 1500
            })
            console.log(data);
        });
    </script>
@stop
