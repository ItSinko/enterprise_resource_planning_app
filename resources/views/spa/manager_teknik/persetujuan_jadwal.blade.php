@extends('adminlte.page')

@section('title', 'PPIC App')

@section('content_header')
<h1 class="m-0 text-dark">PPIC Data Gudang</h1>
@stop

@section('content')
<div id="app"></div>
@stop

@section('adminlte_js')
<script src="{{ asset('native/js/ppic/manager.js') }}"></script>
@stop