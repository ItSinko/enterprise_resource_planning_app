@extends('adminlte.page')

@section('title', 'ERP')
@section('content')
    <div id="app">
        <index></index>
    </div>

@section('adminlte_js')
    <script src="{{ asset('native/js/gbmp.js') }}"></script>
@endsection
@endsection
