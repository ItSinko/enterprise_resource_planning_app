@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="content-header">
    <input type="hidden" name="" id="authid" value="{{ Auth::user()->id }}">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Persetujuan Perubahan Produk Nomor Seri</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/gbj/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Produk</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-edit-tab" data-toggle="pill" href="#pills-edit" role="tab"
                    aria-controls="pills-edit" aria-selected="true">Ubah Nomor Seri</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-hapus-tab" data-toggle="pill" href="#pills-hapus" role="tab"
                    aria-controls="pills-hapus" aria-selected="false">Hapus Nomor Seri</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-edit" role="tabpanel" aria-labelledby="pills-edit-tab">
                <table class="table table-bordered" id="tableUbah" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Merk</th>
                            <th>Nama Produk</th>
                            <th>Kelompok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade show" id="pills-hapus" role="tabpanel" aria-labelledby="pills-hapus-tab">
                <table class="table table-bordered" id="tableHapus" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Merk</th>
                            <th>Nama Produk</th>
                            <th>Kelompok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
<div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered" id="editTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="head-cb"></th>
                        <th>Merk</th>
                        <th>Nama Produk</th>
                        <th>Tanggal Pengajuan</th>
                        <th>No Seri Lama</th>
                        <th>No Seri Baru</th>
                        <th>Diajukan Oleh</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary buttonSubmit" id="btnApproveEdit"><i class="fas fa-check"></i> Setuju</button>&nbsp;
            <button class="btn btn-danger buttonReject" id="btn-reject"><i class="fas fa-ban"></i> Tolak</button>
        </div>
      </div>
    </div>
  </div>
</div>
    <!-- Modal Hapus -->
    <div class="modal" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">`
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="hapusTable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>Merk</th>
                            <th>Nama Produk</th>
                            <th>Tanggal Pengajuan</th>
                            <th>No Seri</th>
                            <th>Diajukan Oleh</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary buttonSubmit" id="btnApproveEdit"><i class="fas fa-check"></i> Setuju</button>&nbsp;
                <button class="btn btn-danger buttonReject" id="btn-reject"><i class="fas fa-ban"></i> Tolak</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal Komentar --}}
    <div class="modal modalKomentar" aria-labelledby="testing" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title judulKomentar" id="staticBackdropLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <textarea name="" id="" cols="10" rows="10" class="form-control textcomentar"></textarea>
          </div>
           <div class="modal-footer">
            <button class="btn btn-primary kirimKomentar">Kirim</button>
           </div>
        </div>
      </div>
    </div>

    {{-- Modal Detail --}} 
    <div class="modal modelDetail" aria-labelledby="testing" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title judulKomentar" id="staticBackdropLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <label for="">Alasan Pengajuan</label>
                <textarea class="form-control alasan_staff " disabled name="" id="" cols="30" rows="10"></textarea>
              </div>
          </div>
        </div>
      </div>
@stop



@section('adminlte_js')
<script>
    $("#head-cb").prop('checked', false);
    $("#head-cb1").prop('checked', false);
    $('.buttonSubmit').attr('id', 'btnApproveEdit');
    $('.buttonReject').attr('id', 'btnRejectEdit');
    var authid = $('#authid').val();
    // Datatable
    $('#tableUbah').DataTable({
        processing: true,
        ordering: false,
        ajax: {
            'type': 'POST',
            'datatype': 'JSON',
            'url': '/api/v2/gbj/list-update-noseri',
            'headers': {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        },
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'merk'},
            {data: 'produk'},
            {data: 'kelompok'},
            {data: 'action'},
        ],
        language: {
            search: "Cari:"
        }
    });
    $('#tableHapus').DataTable({
        processing: true,
        ordering: false,
        ajax: {
            'type': 'POST',
            'datatype': 'JSON',
            'url': '/api/v2/gbj/list-approve-noseri',
            'headers': {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        },
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'merk'},
            {data: 'produk'},
            {data: 'kelompok'},
            {data: 'action'},
        ],
        language: {
            search: "Cari:"
        }
    });

    let id = '';
    $(document).on('click', '.editmodal', function(e) {
        id = $(this).data('id');
        console.log(id);

        $('#editTable').DataTable({
            processing: true,
            ordering: false,
            destroy: true,
            ajax: {
                'type': 'POST',
                'datatype': 'JSON',
                'url': '/api/v2/gbj/detail-update-noseri',
                'data': {
                    gbj: id,
                },
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            columns: [
                {data: 'checkbox'},
                {data: 'merk'},
                {data: 'produk'},
                {data: 'tgl_aju'},
                {data: 'lama'},
                {data: 'baru'},
                {data: 'requested'},
                {data: 'action'},
            ],
            language: {
                search: "Cari:"
            }
        });
        $('#exampleModal').modal('show');
    })

    $(document).on('click', '.deletemodal', function(e) {
        id = $(this).data('id');
        console.log(id);

        $('#hapusTable').DataTable({
            processing: true,
            ordering: false,
            destroy: true,
            ajax: {
                'type': 'POST',
                'datatype': 'JSON',
                'url': '/api/v2/gbj/detail-delete-noseri',
                'data': {
                    gbj: id,
                },
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            columns: [
                {data: 'checkbox'},
                {data: 'merk'},
                {data: 'produk'},
                {data: 'tgl_aju'},
                {data: 'noseri'},
                {data: 'requested'},
            ],
            language: {
                search: "Cari:"
            }
        });
        $('#exampleModal2').modal('show');
    })

    $('#head-cb').click(function () {
        if ($(this).prop('checked') == true) {
            $('#editTable').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', true);
        } else {
            $('#editTable').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', false);
        }
    })

    $('#head-cb1').click(function () {
        if ($(this).prop('checked') == true) {
            $('#hapusTable').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', true);
        } else {
            $('#hapusTable').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', false);
        }
    });

    $('#pills-edit-tab').on('click', function () {
        $('.buttonSubmit').removeAttr('id');
        $('.buttonSubmit').attr('id', 'btnApproveEdit');
        $('.buttonReject').attr('id', 'btnRejectEdit');
    });

    $('#pills-hapus-tab').on('click', function () {
        $('.buttonSubmit').removeAttr('id');
        $('.buttonSubmit').attr('id', 'btnApproveHapus');
        $('.buttonReject').attr('id', 'btnRejectHapus');
    });

    $(document).on('click', '#btnApproveEdit', function () {
        let a = $('#editTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
        function () {
            return $(this).val();
        }).get();

        if (a.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimal 1 Data Dipilih!',
            })
        } else {
            $('.judulKomentar').text('Alasan Persetujuan Edit Nomor Seri');
            $('.textcomentar').val('');
            $('.modalKomentar').modal('show');
            $('.kirimKomentar').removeAttr('id');
            $('.kirimKomentar').attr('id', 'btnApproveEditKomentar');
        }

    })

    $(document).on('click', '#btnApproveHapus', function () {
        let a = $('#hapusTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
            function () {
                return $(this).val();
            }).get();

        if (a.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimal 1 Data Dipilih!',
            })
        } else {
            $('.judulKomentar').text('Alasan Persetujuan Hapus Nomor Seri');
            $('.textcomentar').val('');
            $('.modalKomentar').modal('show');
            $('.kirimKomentar').removeAttr('id');
            $('.kirimKomentar').attr('id', 'btnApproveHapusKomentar');
        }

    });

    $(document).on('click', '#btnRejectHapus', function () {
        let a = $('#hapusTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
            function () {
                return $(this).val();
            }).get();

        if (a.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimal 1 Data Dipilih!',
            })
        } else {
            $('.judulKomentar').text('Alasan Penolakan Hapus Nomor Seri');
            $('.textcomentar').val('');
            $('.modalKomentar').modal('show');
            $('.kirimKomentar').removeAttr('id');
            $('.kirimKomentar').attr('id', 'btnRejectHapusKomentar');
        }
    });

    $(document).on('click', '#btnRejectEdit', function () {
        let a = $('#editTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
        function () {
            return $(this).val();
        }).get();

        if (a.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Minimal 1 Data Dipilih!',
            })
        } else {
            $('.judulKomentar').text('Alasan Penolakan Edit Nomor Seri');
            $('.textcomentar').val('');
            $('.modalKomentar').modal('show');
            $('.kirimKomentar').removeAttr('id');
            $('.kirimKomentar').attr('id', 'btnRejectEditKomentar');
        }
    });

    $(document).on('click', '#btnApproveEditKomentar', function () {
        let komentar = $('.textcomentar').val();
        if (!komentar) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Komentar Tidak Boleh Kosong!',
            })
        } else {
            let a = $('#editTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
                    function () {
                        return $(this).val();
                    }).get();

            Swal.fire({
                title: 'Kamu Yakin?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/v2/gbj/proses-update-noseri',
                        type: 'post',
                        data: {
                            is_acc: 'approved',
                            noseriid: a,
                            accby: authid,
                            komentar: komentar,
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Approved',
                                text: res.msg,
                            }).then(() => {
                                location.reload()
                                $('.textcomentar').val('');
                            });
                        }
                    })
                }
            })
        }
    })

    $(document).on('click', '#btnApproveHapusKomentar', function () {
        let komentar = $('.textcomentar').val();
        if (!komentar) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Komentar Tidak Boleh Kosong!',
            })
        } else {
            let a = $('#hapusTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
                function () {
                    return $(this).val();
                }).get();

            Swal.fire({
                title: 'Kamu Yakin?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/v2/gbj/proses-delete-noseri',
                        type: 'post',
                        data: {
                            is_acc: 'approved',
                            noseriid: a,
                            accby: authid,
                            komentar: komentar,
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Approved',
                                text: res.msg,
                            }).then(() => {
                                location.reload()
                            });
                        }
                    })
                }
            })
        }
    })

    $(document).on('click', '#btnRejectEditKomentar', function () {
        let komentar = $('.textcomentar').val();
        if (!komentar) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Komentar Tidak Boleh Kosong!',
            })
        } else {
            let a = $('#editTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
                function () {
                    return $(this).val();
                }).get();

            Swal.fire({
                title: 'Kamu Yakin?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/v2/gbj/proses-update-noseri',
                        type: 'post',
                        data: {
                            is_acc: 'rejected',
                            noseriid: a,
                            accby: authid,
                            komentar: komentar,
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected',
                                text: res.msg,
                            }).then(() => {
                                location.reload()
                            });
                        }
                    })
                }
            })
        }
    })

    $(document).on('click', '#btnRejectHapusKomentar', function () {
        let komentar = $('.textcomentar').val();
        if (!komentar) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Komentar Tidak Boleh Kosong!',
            })
        } else {
            let a = $('#hapusTable').DataTable().column(0).nodes().to$().find('input[type=checkbox]:checked').map(
                function () {
                    return $(this).val();
                }).get();

            Swal.fire({
                title: 'Kamu Yakin?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/v2/gbj/proses-delete-noseri',
                        type: 'post',
                        data: {
                            is_acc: 'rejected',
                            noseriid: a,
                            accby: authid,
                            komentar: komentar,
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected',
                                text: res.msg,
                            }).then(() => {
                                location.reload()
                            });
                        }
                    })
                }
            })
        }
    })

    $(document).on('click', '.btnAlasan', function () {
        let id = $(this).data('id');
        // $.ajax({
        //     url: '/api/v2/gbj/get-alasan',
        //     type: 'post',
        //     data: {
        //         id: id,
        //     },
        //     success: function(res) {
        //         $('.alasan_staff').val(res.data.komentar);
        //     }
        // })
        $('.modelDetail').modal('show');
    })

</script>
@stop
