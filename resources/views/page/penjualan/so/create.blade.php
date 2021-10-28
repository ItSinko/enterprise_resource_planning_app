@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Penjualan</h1>
@stop

@section('adminlte_css')
<style>
    .hide {
        display: none !important;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content">
            <div class="row">
                <div class="col-12">
                    @if(session()->has('error') || count($errors) > 0 )
                    <div class="alert alert-danger alert-dismissible fade show col-12" role="alert">
                        <strong>Gagal menambahkan!</strong> Periksa
                        kembali data yang diinput
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @elseif(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show col-12" role="alert">
                        <strong>Berhasil menambahkan data</strong>,
                        Terima kasih
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <h4>Info Penjualan</h4>
                            <div class="row">
                                <div class="col-4">
                                    <div>
                                        <b>Rumah Sakit Dr. Soetomo</b>
                                    </div>
                                    <div>
                                        <b>Jl. Dharmahusada 19 - 27, Surabaya</b>
                                    </div>
                                    <div>
                                        <b>0315653715</b>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-muted">No AKN</div>
                                    <div>
                                        <b id="no_akn">AKN-128371984719</b>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-muted">Tanggal Pemesanan</div>
                                    <div>
                                        <b id="tanggal_pemesanan">09-09-2022</b>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="text-muted">Status</div>
                                    <div>
                                        <b id="status"><span class="badge red-text">Batal</span></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="no_po" class="col-4 col-form-label" style="text-align:right;">No SO</label>
                                        <div class="col-5">
                                            <input type="text" class="form-control @error('no_so') is-invalid @enderror" value="" placeholder="Masukkan Nomor Sales Order" id="no_so" name="no_so" />
                                            <div class="invalid-feedback" id="msgno_so">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="no_po" class="col-4 col-form-label" style="text-align:right;">No PO</label>
                                        <div class="col-5">
                                            <input type="text" class="form-control @error('no_po') is-invalid @enderror" value="" placeholder="Masukkan Nomor Purchase Order" id="no_po" name="no_po" />
                                            <div class="invalid-feedback" id="msgno_po">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tanggal_po" class="col-4 col-form-label" style="text-align:right;">Tanggal PO</label>
                                        <div class="col-5">
                                            <input type="date" class="form-control @error('tanggal_po') is-invalid @enderror" value="" placeholder="Masukkan Tanggal Purchase Order" id="tanggal_po" name="tanggal_po" />
                                            <div class="invalid-feedback" id="msgtanggal_po">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-4" style="text-align: right">Delivery Order</label>
                                        <div class="col-5 col-form-label">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="do" id="yes" value="yes" />
                                                <label class="form-check-label" for="yes">Tersedia</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="do" id="no" value="no" />
                                                <label class="form-check-label" for="no">Tidak tersedia</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide" id="do_detail_no">
                                        <label for="" class="col-form-label col-4" style="text-align: right">Nomor DO</label>
                                        <div class="col-5">
                                            <input type="text" class="form-control col-form-label @error('no_do') is-invalid @enderror" id="no_do" name="no_do" />
                                            <div class="invalid-feedback" id="msgno_do">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide" id="do_detail_tgl">
                                        <label for="" class="col-form-label col-4" style="text-align: right">Tanggal DO</label>
                                        <div class="col-5">
                                            <input type="date" class="form-control col-form-label @error('tanggal_do') is-invalid @enderror" id="tanggal_do" name="tanggal_do" />
                                            <div class="invalid-feedback" id="msgtanggal_do">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="keterangan" class="col-4 col-form-label" style="text-align:right;">Keterangan</label>
                                        <div class="col-5">
                                            <textarea class="form-control" placeholder="Masukkan Keterangan" id="keterangan"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <a href="{{route('penjualan.penjualan.show')}}"><button type="button" class="btn btn-danger">
                                                Batal
                                            </button>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-info float-right" id="btntambah" disabled>
                                            Tambah
                                        </button>
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
@stop

@section('adminlte_js')
<script>
    $(function() {
        $('#no_po').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgno_po").text("");
                $("#no_po").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgno_po").text("Nomor PO Harus diisi");
                $("#no_po").addClass('is-invalid');
            }
        });
        $('#tanggal_po').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgtanggal_po").text("");
                $("#tanggal_po").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgtanggal_po").text("Tanggal PO Harus diisi");
                $("#tanggal_po").addClass('is-invalid');
            }
        });

        $('input[type="radio"][name="do"]').on('change', function() {
            if ($(this).val() == "yes") {
                $("#do_detail_no").removeClass("hide");
                $("#do_detail_tgl").removeClass("hide");
            } else if ($(this).val() == "no") {
                $("#do_detail_no").addClass("hide");
                $("#do_detail_tgl").addClass("hide");
            }
        });
    })
</script>
@stop