<div class="row filter d-flex justify-content-center">
    <div class="col-lg-12">
        <div class="card" style="box-shadow:none;">
            <div class="card-body">
                <ul class="fa-ul card-text">
                    <li class="py-2">
                        <div class="row text-md">
                            <div class="col-lg-3 col-md-5 text-muted">No Perbaikan</div>
                            <div class="col-lg-9 col-md-7" id="no_perbaikan"></div>
                        </div>
                    </li>
                    <li class="py-2">
                        <div class="row text-md">
                            <div class="col-lg-3 col-md-5 text-muted">Tanggal Perbaikan</div>
                            <div class="col-lg-9 col-md-7" id="tgl_perbaikan"></div>
                        </div>
                    </li>
                    <li class="py-2">
                        <div class="row text-md">
                            <div class="col-lg-3 col-md-5 text-muted">Operator</div>
                            <div class="col-lg-9 col-md-7" id="karyawan_id"></div>
                        </div>
                    </li>
                </ul>
                <div class="info-box yellow-text removeshadow">
                    <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Keterangan</span>
                        <span class="info-box-number" id="ket_perbaikan"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mx-4 mb-2 ">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-referensi-tab" data-toggle="pill"
                            href="#pills-referensi" role="tab" aria-controls="pills-referensi"
                            aria-selected="true">Referensi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-no_seri-tab" data-toggle="pill"
                            href="#pills-no_seri" role="tab" aria-controls="pills-no_seri"
                            aria-selected="false">No Seri Retur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-part_pengganti-tab" data-toggle="pill"
                            href="#pills-part_pengganti" role="tab" aria-controls="pills-part_pengganti"
                            aria-selected="false">Part Pengganti</a>
                    </li>
                </ul>
                <div class="tab-content contentdiv" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-referensi" role="tabpanel"
                        aria-labelledby="pills-referensi-tab">
                        <div class="row m-1">
                            <div class="col-lg-5 col-md-6 col-sm-12">
                                <div class="card shadow-none">
                                    <div class="card-body align-center">
                                        <div id="profileImage" class="center margin-all"></div>
                                        <div class="margin-all mt-2">
                                            <h5><b id="customer_nama"></b></h5>
                                        </div>
                                        <div class="margin-all text-muted"><i class="fas fa-phone"></i> <span id="customer_telp"></span></div>
                                        <div class="margin-all mt-2 text-muted">
                                            <span id="customer_alamat"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-6 col-sm-12"><div class="card">
                                <div class="card-body">
                                    <strong>No Retur</strong>
                                    <p class="text-muted"><span id="no_retur"></span></p>
                                    <hr>
                                    <strong>Tanggal Retur</strong>
                                    <p class="text-muted"><span id="tgl_retur"></span></p>
                                    <hr>
                                    <strong>Jenis Retur</strong>
                                    <p class="text-muted"><span id="retur_jenis"></span></p>
                                    <hr>
                                    <strong>Alasan Retur</strong>
                                    <p class="text-muted"><span id="retur_keterangan"></span></p>
                                </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="pills-no_seri" role="tabpanel"
                        aria-labelledby="pills-no_seri-tab">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="info-box blue-text removeshadow">
                                    <span class="info-box-icon"><i class="fas fa-box-open"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Nama Produk</span>
                                        <span class="info-box-number" id="produk_id"></span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table-hover table align-center" id="noseritable" style="width:100%;">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th style="width:10%;">No</th>
                                                <th style="width:30%;">No Seri</th>
                                                <th style="width:25%;">Tindak Lanjut</th>
                                                <th style="width:25%;">Status</th>
                                                <th style="width:10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-part_pengganti" role="tabpanel"
                        aria-labelledby="pills-part_pengganti-tab">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-hover table align-center" id="parttable" style="width:100%;">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th style="width:10%;">No</th>
                                                <th style="width:70%;">Nama Part</th>
                                                <th style="width:20%;">Jumlah</th>
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
</div>
