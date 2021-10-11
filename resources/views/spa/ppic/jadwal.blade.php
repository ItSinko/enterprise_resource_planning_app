@extends('adminlte.page')

@section('title', 'PPIC App')

@section('content_header')
<h1 class="m-0 text-dark">PPIC Jadwal Produksi</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="app"></div>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script src="{{ asset('native/js/ppic/jadwal.js') }}"></script>
@stop