<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-4">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bulan</span>
                        <span class="info-box-number" id="bulan"></span>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fas fa-user"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Nama Karyawan</span>
                        <span class="info-box-number" id="nama"></span>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="info-box bg-gradient-warning">
                    <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Jumlah Sakit</span>
                        <span class="info-box-number" id="jumlah"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="table_sakit">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Obat</th>
                                <th>Diagnosa</th>
                                <th>Tindak Lanjut</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <td>1</td>
                                <td>09-02-2022</td>
                                <td>Vitalong-C</td>
                                <td>Batuk, Tenggorokan Sakit</td>
                                <td><span class="badge green-text">Lanjut Bekerja</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>08-02-2022</td>
                                <td>Sangobion</td>
                                <td>Pusing, Darah Rendah</td>
                                <td><span class="badge green-text">Lanjut Bekerja</span></td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
