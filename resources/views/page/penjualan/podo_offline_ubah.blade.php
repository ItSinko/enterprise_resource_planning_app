@extends('adminlte.page')
@section('title', 'Beta Version')
@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>
@stop
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="col-lg-12">
      <form method="post" action="/podo_offline/aksi_ubah/{{$podo_offline->id}}" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="card">
          <div class="card-header bg-success">
            <div class="card-title"><i class="fas fa-plus-circle"></i>&nbsp;Ubah</div>
          </div>
          <div class="card-body">
            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-horizontal">
                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label" style="text-align:right;">Customer</label>
                      <div class="col-sm-4">
                        <input class="form-control  @error('customer') is-invalid @enderror" name="customer_id" type="text" value="{{$podo_offline->offline->distributor->nama}}" readonly>
                        @if($errors->has('customer_id'))
                        <div class="text-danger">
                          {{ $errors->first('customer_id')}}
                        </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="keterangan" class="col-sm-4 col-form-label" style="text-align:right;">ID Order</label>
                      <div class="col-sm-4">
                        <input class="form-control @error('offline_id') is-invalid @enderror" name="offline_id" value="{{$podo_offline->offline->order_id}}" readonly>
                        @if($errors->has('offline_id'))
                        <div class="text-danger">
                          {{ $errors->first('offline_id')}}
                        </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="keterangan" class="col-sm-4 col-form-label" style="text-align:right;">Purchase Order</label>
                      <div class="col-sm-4">
                        <input class="form-control @error('po') is-invalid @enderror" type="text" name="po" value="{{$podo_offline->po}}">
                        @if($errors->has('po'))
                        <div class="text-danger">
                          {{ $errors->first('po')}}
                        </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="keterangan" class="col-sm-4 col-form-label" style="text-align:right;">Tgl Purchase Order</label>
                      <div class="col-sm-4">
                        <input class="form-control @error('tglpo') is-invalid @enderror" type="date" name="tglpo" value="{{$podo_offline->tglpo}}">
                        @if($errors->has('tglpo'))
                        <div class="text-danger">
                          {{ $errors->first('tglpo')}}
                        </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="keterangan" class="col-sm-4 col-form-label" style="text-align:right;">Delivery Order</label>
                      <div class="col-sm-4">
                        <input class="form-control @error('do') is-invalid @enderror" type="text" name="do" value="{{$podo_offline->do}}">
                        @if($errors->has('do'))
                        <div class="text-danger">
                          {{ $errors->first('do')}}
                        </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="keterangan" class="col-sm-4 col-form-label" style="text-align:right;">Tgl Delivery Order</label>
                      <div class="col-sm-4">
                        <input class="form-control @error('tgldo') is-invalid @enderror" type="date" name="tgldo" value="{{$podo_offline->tgldo}}">
                        @if($errors->has('tgldo'))
                        <div class="text-danger">
                          {{ $errors->first('tgldo')}}
                        </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="keterangan" class="col-sm-4 col-form-label" style="text-align:right;">Catatan</label>
                      <div class="col-sm-4">
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" placeholder="">{{$podo_offline->keterangan}}</textarea>
                        @if($errors->has('keterangan'))
                        <div class="text-danger">
                          {{ $errors->first('keterangan')}}
                        </div>
                        @endif
                      </div>
                    </div>

                    @if($podo_offline->file == NULL)
                    <div class="form-group row">
                      <label for="keterangan" class="col-sm-4 col-form-label" style="text-align:right;">Lampiran</label>
                      <div class="col-sm-4">
                        <input class="form-control @error('lampiran') is-invalid @enderror" type="file" name="lampiran">
                        @if($errors->has('lampiran'))
                        <div class="text-danger">
                          {{ $errors->first('lampiran')}}
                        </div>
                        @endif
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <span class="float-left"><a class="btn btn-danger rounded-pill" href="/podo_offline/"><i class="fas fa-times"></i>&nbsp;Batal</a></span>
            <span class="float-right"><button class="btn btn-success rounded-pill" id="button_tambah"><i class="fas fa-plus"></i>&nbsp;Ubah Data</button></span>
          </div>
        </div>
    </div>
    </form>
  </div>
</div>
@stop