<div class="row filter d-flex justify-content-center">
    <div class="col-lg-12">
        <div class="card-deck">
            <div class="card shadow-none">
                <div class="card-body">
                    <h5>Identitas Pengiriman</h5>
                    <ul class="fa-ul card-text">
                        <li class="pb-1 pt-2">
                            <strong>No Surat Jalan</strong>
                            <p class="text-muted"><span id="no_pengiriman"></span></p>
                        </li>
                        <li class="pb-1">
                            <strong>Tanggal Pengiriman</strong>
                            <p class="text-muted"><span id="tgl_pengiriman"></span></p>
                        </li>
                        <li class="pb-1">
                            <strong>Ekspedisi</strong>
                            <p class="text-muted"><span id="ekspedisi_id"></span></p>
                        </li>
                        <li>
                            <strong>No Resi</strong>
                            <p class="text-muted"><span id="no_resi"></span></p>
                        </li>
                        <li>
                            <strong>Biaya Kirim</strong>
                            <p class="text-muted"><span id="biaya_kirim"></span></p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-none">
                <div class="card-body">
                    <h5>Identitas Penerima</h5>
                    <div class="card-text" style="height: 80%">
                        <ul class="fa-ul h-100">
                            <li class="pt-2 mb-4"><span class="fa-li" style="--fa-li-width: 2.5em;"><i class="fa-solid fa-user"></i></span><span id="nama_penerima"></span></li>
                            <li class="py-2 my-4"><span class="fa-li" style="--fa-li-width: 2.5em;"><i class="fa-solid fa-map-pin"></i></span><span id="alamat_penerima"></span></li>
                            <li class="pb-2 mt-4"><span class="fa-li" style="--fa-li-width: 2.5em;"><i class="fa-solid fa-phone"></i></span><span id="telp_penerima"></span></li>
                        </ul>
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
                        aria-selected="false">No Seri</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-part_pengganti-tab" data-toggle="pill"
                        href="#pills-part_pengganti" role="tab" aria-controls="pills-part_pengganti"
                        aria-selected="false">Part</a>
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
                                        <h5><b id="customer"></b></h5>
                                    </div>
                                    <div class="margin-all text-muted"><i class="fas fa-phone"></i> <span id="telp"></span></div>
                                    <div class="margin-all mt-2 text-muted">
                                        <span id="alamat"></span>
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
                            <p class="text-muted"><span id="jenis_retur"></span></p>
                            <hr>
                            <strong>Alasan Retur</strong>
                            <p class="text-muted"><span id="keterangan"></span></p>
                        </div>
                        </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="pills-no_seri" role="tabpanel"
                        aria-labelledby="pills-no_seri-tab">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-hover table align-center" id="noseritable" style="width:100%;">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th style="width:10%;">No</th>
                                                <th style="width:65%;">Nama Produk</th>
                                                <th style="width:25%;">No Seri</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>DIGIT PRO BMI</td>
                                                <td>TD12250AA001</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>DIGIT PRO BMI</td>
                                                <td>TD12250AA002</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>FOX-BABY</td>
                                                <td>TD12250AA003</td>
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
                                            <tr>
                                                <td>1</td>
                                                <td>Basket For Trolley Set (Basket, Buckle, Handle)</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Silver Condutif Mechanic/Silver Conductive Mechanic 0.4ml</td>
                                                <td>1</td>
                                            </tr>
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
