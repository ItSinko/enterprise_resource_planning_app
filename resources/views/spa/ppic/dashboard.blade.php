@extends('adminlte.page')

@section('title', 'PPIC App')

@section('content_header')
<h1 class="m-0 text-dark">PPIC Dashboard</h1>
@stop

@section('content')
<div id="app">
</div>
@stop

@section('adminlte_js')
<script src="{{ asset('native/js/ppic/dashboard.js') }}"></script>
@stop