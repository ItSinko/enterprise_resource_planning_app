@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Penjualan</h1>
        </div>
    </div>
</div>
@endsection
@section('content')
    <div id="app">
        <index></index>
    </div>

@section('adminlte_js')
    <script src="{{ asset('native/js/penjualan.js') }}"></script>
@endsection
@endsection
