@extends('adminlte.page')
@section('title', 'ERP')

@section('content')
<div id="app">
    <index></index>
</div>

@endsection

@section('adminlte_js')
<script src="{{ asset('native/js/as.js') }}">
</script>
@endsection
