{{-- @extends('adminlte.page')
@section('title', 'Beta Version')
@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>
@stop
@section('content')
<section class="content-header">
    <div class="container-fluid">
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12">

            <div class="col-lg-12"> --}}
                <form action="/obat/aksi_tambah" method="post" id="form_tambah_obat">
                    {{ csrf_field() }}
                    {{-- <div class="card">
                        <div class="card-header bg-success">
                            <div class="card-title"><i class="fas fa-plus-circle"></i>&nbsp;Tambah</div>
                        </div>
                        <div class="card-body"> --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="no_pemeriksaan" class="col-sm-5 col-form-label" style="text-align:right;">Nama Obat</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="nama" style="width:45%;" placeholder="Masukkan Nama Obat" value="{{ old('nama') }}" id="nama_obat_tambah">
                                                    <div class="text-danger form-text" id="nama_obat_tambah_message">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-sm-5 col-form-label" style="text-align: right;">Aturan</label>
                                                <div class="col-sm-7 col-form-label">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input aturan_obat" type="radio" name="aturan_obat" value="Sebelum Makan">
                                                        <label class="form-check-label">Sebelum Makan</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input aturan_obat" type="radio" name="aturan_obat" value="Sesudah Makan">
                                                        <label class="form-check-label">Sesudah Makan</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="tanggal" class="col-sm-5 col-form-label" style="text-align:right;">Keterangan</label>
                                                <div class="col-sm-7">
                                                    <textarea type="text" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" value="{{old('keterangan')}}" placeholder="Catatan tambahan" style="width:45%;"></textarea>
                                                </div>
                                                <span role="alert" id="no_seri-msg"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6"><button class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;Batal</button></div>
                                    <div class="col-6"><button class="btn btn-primary float-right" id="button_tambah" disabled="true"><i class="fas fa-plus"></i>&nbsp;Simpan</button></div>
                                </div>
                            </div>
                        </div>
                        {{-- </div>

                    </div> --}}
                </form>
            {{-- </div>
        </div>
    </div>
</section> --}}
{{-- @endsection
@section('adminlte_js')
<script>
    $(document).ready(function() {

    });
</script>
@stop --}}
