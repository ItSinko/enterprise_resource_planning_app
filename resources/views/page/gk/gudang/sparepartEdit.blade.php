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

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Detail Kerusakan Sparepart</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        @foreach ($header as $h)
                            <input type="hidden" name="" id="id" value="{{ $h->id }}">
                            <div class="col-sm">
                                <div class="row row-cols-2">
                                    {{-- col --}}
                                    <div class="col"> <label for="">Kode Sparepart</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                {{ $h->kode }}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nama Sparepart</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                {{ $h->nama }}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Unit</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                -
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Jumlah</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                {{ $h->jml }} pcs
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
                                <th rowspan="2">Posisi Barang</th>
                                <th rowspan="2">Kerusakan</th>
                                <th rowspan="2">Perbaikan</th>
                                <th rowspan="2">Tingkat Kerusakan</th>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade changeStatus" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
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
                                <div class="col"> <label for="">Tanggal Masuk & Tanggal Keluar</label>
                                    <div class="card-group">
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <p class="card-text" id="in">10-04-2022</p>
                                            </div>
                                        </div>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <p class="card-text" id="out">23-09-2022</p>
                                            </div>
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
                                    <div class="form-group col-md-6">
                                        <label for="">Layout</label>
                                        <select name="layout_id" id="layout_id" class="form-control layout_edit">
                                            @foreach ($layout as $l)
                                                <option value="{{ $l->id }}">{{ $l->ruang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">Tingkat Kerusakan</label>
                                        <select name="tk_kerusakan" id="tk_kerusakan"
                                            class="form-control kerusakan_edit">
                                            <option value="1">Level 1</option>
                                            <option value="2">Level 2</option>
                                            <option value="3">Level 3</option>
                                        </select>
                                    </div>
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
                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('adminlte_js')
    <script>
        function changeStatus() {
            $('.changeStatus').modal('show');
        }
        $('.layout_edit').select2({
            dropdownParent: $('.changeStatus')
        });
        $('.kerusakan_edit').select2({
            dropdownParent: $('.changeStatus')
        });
        var id = $('#id').val();

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
                url: "/api/gk/his-spr/" + id,
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
                    data: 'layout'
                },
                {
                    data: 'remarks'
                },
                {
                    data: 'perbaikan'
                },
                {
                    data: 'tingkat'
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
        $(document).on('click', '.detailModal', function() {
            id = $(this).data('id');

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
                    $('#user_id').val();
                    $('#layout_id').val(res.layout);
                    $('#layout_id').select2().trigger('change');
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
            $('#btnSave').attr('disabled', true);
            $.ajax({
                type: 'POST',
                url: "/api/gk/ubahunit",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
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
        })
    </script>
@stop
