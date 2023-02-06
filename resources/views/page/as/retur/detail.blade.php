<div class="row filter d-flex justify-content-center">
    <div class="col-lg-4 col-md-12">
                <div class="row">
                    <div class="col-lg-12 col-md-6">
                        <div class="info-box bg-navy removeshadow">
                            <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">No Referensi Transaksi</span>
                            <span class="info-box-number" id="no_pesanan"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-6">
                        <div class="info-box bg-orange removeshadow">
                            <span class="info-box-icon"><i class="fas fa-user"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Nama Customer</span>
                            <span class="info-box-number" id="customer_nama"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-6">
                        <div class="info-box bg-olive removeshadow">
                            <span class="info-box-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Alamat</span>
                            <span class="info-box-number" id="alamat"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-6">
                        <div class="info-box bg-light removeshadow">
                            <span class="info-box-icon"><i class="fas fa-phone"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Telepon</span>
                            <span class="info-box-number" id="telepon"></span>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
    <div class="col-lg-7 col-md-12">

                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="pills-detail-memo-tab" data-toggle="pill" href="#pills-detail-memo" role="tab" aria-controls="pills-detail-memo" aria-selected="true">Detail Memo</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="pills-data-produk-tab" data-toggle="pill" href="#pills-data-produk" role="tab" aria-controls="pills-data-produk" aria-selected="false">Data Produk</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-detail-memo" role="tabpanel" aria-labelledby="pills-detail-memo-tab">
                            <div class="card">
                                <div class="card-body">
                                <strong><i class="fas fa-book mr-1 fa-fw"></i> No Retur</strong>
                                <p class="text-muted"><i class="fas fa-unknown mr-1 fa-fw"></i> <span id="no_retur"></span></p>
                                <hr>
                                <strong><i class="fas fa-calendar-alt mr-1 fa-fw"></i> Tanggal Retur</strong>
                                <p class="text-muted"><i class="fas fa-unknown mr-1 fa-fw"></i> <span id="tgl_retur"></span></p>
                                <hr>
                                <strong><i class="fas fa-asterisk mr-1 fa-fw"></i> Jenis Retur</strong>
                                <p class="text-muted"><i class="fas fa-unknown mr-1 fa-fw"></i> <span id="jenis"></span></p>
                                <hr class="pic">
                                <strong class="pic"><i class="fas fa-user mr-1 fa-fw"></i> Penanggung Jawab</strong>
                                <p class="pic" class="text-muted"><i class="fas fa-unknown mr-1 fa-fw"></i> <span id="karyawan_id"></span></p>
                                <hr>
                                <strong><i class="far fa-file-alt mr-1 fa-fw"></i> Alasan Retur</strong>
                                <p class="text-muted"><i class="fas fa-unknown mr-1 fa-fw"></i> <span id="keterangan"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-data-produk" role="tabpanel" aria-labelledby="pills-data-produk-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table" id="barangtable" width="100%">
                                            <thead class="bg-secondary">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Barang</th>
                                                    <th>Jenis</th>
                                                    <th>Jumlah</th>
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
                    </div>


    </div>
</div>
