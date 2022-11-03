<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Info Produk</h5>
                    <div class="row">
                        <div class="col-8">
                            <div><small class="text-muted">Nama Produk</small></div>
                            <div><b>Elitech MTB 2 MTR</b></div>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-3">
                            <div><small class="text-muted">Jumlah</small></div>
                            <div><b>1000 pcs</b></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Data No Seri</h5>
                    <div class="table-responsive">
                        <table class="table table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Seri</th>
                                    @if (Auth::user()->Karyawan->divisi_id == '8')
                                        <th>Tanggal Pengujian</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>SJ0131831947</td>
                                    @if (Auth::user()->Karyawan->divisi_id == '8')
                                        <td>23-10-2021</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>SJ0131831948</td>
                                    @if (Auth::user()->Karyawan->divisi_id == '8')
                                        <td>23-10-2021</td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
