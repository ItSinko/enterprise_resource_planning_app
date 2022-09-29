@extends('adminlte.page')
@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Kesehatan Bulanan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active"><a href="/kesehatan/bulanan">Kesehatan Bulanan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('adminlte_css')
<style>
    table { border-collapse: collapse; empty-cells: show; }

    td { position: relative; }

    .foo {
        border-radius: 50%;
        float: left;
        width: 10px;
        height: 10px;
        align-items: center !important;
    }

    tr.line-through td:not(:nth-last-child(-n+2)):before {
        content: " ";
        position: absolute;
        left: 0;
        top: 35%;
        border-bottom: 1px solid;
        width: 100%;
    }

    @media screen and (min-width: 1440px) {

        body {
            font-size: 14px;
        }

        #detailmodal {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }


    }

    @media screen and (max-width: 1439px) {
        body {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
        }

        #detailmodal {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }


    }



</style>
@stop
@section('content')
<section class="content-header">
    <div class="container-fluid">
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-12">
                {{ csrf_field() }}
                <div class="card">
                    <div class="card-header bg-success">
                        <div class="card-title">Chart</div>
                    </div>
                    <div class="card-body">
                        <div class='table-responsive'>
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="no_pemeriksaan" class="col-sm-4 col-form-label" style="text-align:right;">Nama Karyawan</label>
                                    <div class="col-sm-8">
                                        <select type="text" class="form-control @error('divisi') is-invalid @enderror select2" name="divisi" style="width:45%;" id="karyawan_id">
                                            <option value="0">Pilih Data</option>
                                            @foreach ($karyawan as $k)
                                            <option value="{{$k->id}}">{{$k->nama}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('divisi'))
                                        <div class="text-danger">
                                            {{ $errors->first('divisi')}}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <!-- LINE CHART -->
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">GCU</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="card-body">
                                                    <canvas id="gcu_chart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <!-- LINE CHART -->
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">Berat Badan</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="card-body">
                                                    <canvas id="berat_chart"></canvas>
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
        </div>
        <div class="col-lg-6">
            <form action="/kesehatan_harian/aksi_tambah" method="post">
                {{ csrf_field() }}
                <div class="card">
                    <div class="card-header bg-success">
                        <div class="card-title">GCU</div>
                    </div>
                    <div class="card-body">
                        <div class='table-responsive'>
                            <table id="tensi_tabel" class="table table-hover styled-table table-striped">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th>No</th>
                                        <th>Tgl Pengecekan</th>
                                        <th>Glucose</th>
                                        <th>Cholesterol</th>
                                        <th>Uric Acid</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-lg-6">
            <form action="/kesehatan/harian/aksi_tambah" method="post">
                {{ csrf_field() }}
                <div class="card">
                    <div class="card-header bg-success">
                        <div class="card-title">Berat Badan</div>
                    </div>
                    <div class="card-body">
                        <div class='table-responsive'>
                            <table id="berat_tabel" class="table table-hover styled-table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Berat</th>
                                        <th>Fat</th>
                                        <th>Tbw</th>
                                        <th>Muscle</th>
                                        <th>Bone</th>
                                        <th>Kalori</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</section>
@endsection
@section('adminlte_js')
<script>
    $(function() {
        $('#tensi_tabel > tbody').on('click', '#delete', function() {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Yakin ingin menghapus data ini?',
            icon: 'warning',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            showCancelButton: true,
            showCloseButton: true
        })
        .then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Berhasil',
                    text: 'Berhasil menghapus data',
                    icon: 'success',
                    showCloseButton: true
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Gagal',
                    text: 'Gagal menghapus data',
                    icon: 'error',
                    showCloseButton: true
                });
            }
        });
    });
        $('#berat_tabel > tbody').on('click', '#delete', function() {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Yakin ingin menghapus data ini?',
            icon: 'warning',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            showCancelButton: true,
            showCloseButton: true
        })
        .then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Berhasil',
                    text: 'Berhasil menghapus data',
                    icon: 'success',
                    showCloseButton: true
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Gagal',
                    text: 'Gagal menghapus data',
                    icon: 'error',
                    showCloseButton: true
                });
            }
        });
    });
        var karyawan_id = 0;
        var tensi_tabel = $('#tensi_tabel').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
          'type': 'POST',
          'headers': {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
          },
          'url': '/kesehatan/bulanan/gcu/detail/' + karyawan_id,
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
                    data: 'tgl_cek',
                    render: function (data, type, row) {
                        return moment(new Date(data).toString()).format('DD-MM-YYYY');
                    }
                },
                {
                    data: 'glu',
                    render: function(data) {
                        $l = '<br><span class="badge bg-danger">' + data + '</span>';
                        $n = '<br><span class="badge bg-success">' + data + '</span>';
                        $w = '<br><span class="badge bg-warning">' + data + '</span>';

                        if (data >= 200) {
                            return 'Diabetes' + $l;
                        } else if (data < 200) {
                            return 'Normal' + $n;;
                        } else if (data >= 140 && data <= 199) {
                            return 'Pra Diabetes' + $w;
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: 'kol',
                    render: function(data) {
                        $l = '<br><span class="badge bg-danger">' + data + '</span>';
                        $n = '<br><span class="badge bg-success">' + data + '</span>';
                        $w = '<br><span class="badge bg-warning">' + data + '</span>';
                        if (data > 239) {
                            return 'Bahaya' + $l;
                        } else if (data < 200) {
                            return 'Normal' + $n;
                        } else if (data >= 200 && data <= 239) {
                            return 'Hati hati' + $w;
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: 'asam',
                    render: function(data) {
                        $l = '<br><span class="badge bg-danger">' + data + '</span>';
                        $n = '<br><span class="badge bg-success">' + data + '</span>';
                        $w = '<br><span class="badge bg-warning">' + data + '</span>';

                        if (data >= 2 && data <= 7.5) {
                            return 'Normal' + $n;
                        } else if (data > 7.5) {
                            return 'Asam Urat' + $l;
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'aksi'
                },
            ]
        });



        var berat_tabel = $('#berat_tabel').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
          'type': 'POST',
          'headers': {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
          },
          'url': '/kesehatan/bulanan/berat/detail/' + karyawan_id,
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
                    data: 'tgl_cek',
                    render: function (data, type, row) {
                        return moment(new Date(data).toString()).format('DD-MM-YYYY');
                    }
                },
                {
                    data: 'z'
                },
                {
                    data: 'l'
                },
                {
                    data: 'k'
                },
                {
                    data: 'o'
                },
                {
                    data: 't'
                },
                {
                    data: 'ka'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'aksi'
                },
            ]
        });

    });
</script>

<script>
    //Tensi Sistolik
    var ctx = document.getElementById("gcu_chart");
    var gcu_chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                    label: 'Glucose',
                    data: [],
                    borderWidth: 2,
                    backgroundColor: 'transparent',
                    borderColor: 'red',
                },
                {
                    label: 'Colesterol',
                    data: [],
                    borderWidth: 2,
                    backgroundColor: 'transparent',
                    borderColor: 'blue',
                },
                {
                    label: 'Uri Acid',
                    data: [],
                    borderWidth: 2,
                    backgroundColor: 'transparent',
                    borderColor: 'black',
                }
            ]
        },
        options: {
            scales: {
                xAxes: [],
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    //Berat
    var ctx = document.getElementById("berat_chart");
    var berat_chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Berat',
                data: [],
                borderWidth: 2,
                backgroundColor: 'transparent',
                borderColor: 'blue',
            }, ]
        },
        options: {
            scales: {
                xAxes: [],
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    $('#karyawan_id').change(function() {
        var karyawan_id = $(this).val();
        $('#tensi_tabel').DataTable().ajax.url('/kesehatan/bulanan/gcu/detail/' + karyawan_id).load();
        $('#berat_tabel').DataTable().ajax.url('/kesehatan/bulanan/berat/detail/' + karyawan_id).load();
        var updateChart = function() {
            $.ajax({
                url: "/kesehatan/bulanan/detail/data/" + karyawan_id,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log(data);
                    gcu_chart.data.labels = data.tgl;
                    gcu_chart.data.datasets[0].data = data.labels2;
                    gcu_chart.data.datasets[1].data = data.labels3;
                    gcu_chart.data.datasets[2].data = data.labels4;
                    gcu_chart.update();

                    berat_chart.data.labels = data.tgl2;
                    berat_chart.data.datasets[0].data = data.labels5;
                    berat_chart.update();

                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
        updateChart();
    });
    $('.select2').select2();
</script>
@endsection
