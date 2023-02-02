<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row row-cols-2">
                    {{-- col --}}
                    <div class="col"> <label for="">Memo</label>
                        <div class="card nomor-so">
                            <div class="card-body">
                                <span id="nosoo">{{ $data->deskripsi }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- col --}}
                    <div class="col"> <label for="">Tgl Transfer</label>
                        <div class="card nomor-akn">
                            <div class="card-body">
                                <span id="noakn">
                                    {{ $data->tgl_keluar }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="view-seritfnon">
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
