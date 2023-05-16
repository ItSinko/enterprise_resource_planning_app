<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row row-cols-2">
                    {{-- col --}}
                    <div class="col"> <label for="">Nomor SO</label>
                        <div class="card nomor-so">
                            <div class="card-body">
                                <span id="nosoo">{{ $data->so }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- col --}}
                    <div class="col"> <label for="">Nomor AKN</label>
                        <div class="card nomor-akn">
                            <div class="card-body">
                                <span id="noakn">
                                    @if ($data->Ekatalog)
                                        {{ $data->Ekatalog->no_paket }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- col --}}
                    <div class="col"> <label for="">Nomor PO</label>
                        <div class="card nomor-po">
                            <div class="card-body">
                                <span id="nopoo">{{ $data->no_po }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- col --}}
                    <div class="col"> <label for="">Instansi</label>
                        <div class="card instansi">
                            <div class="card-body">
                                <span id="nminstansi">
                                    @if ($data->Ekatalog)
                                        {{ $data->Ekatalog->instansi }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="view-seritf">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>No Seri</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
