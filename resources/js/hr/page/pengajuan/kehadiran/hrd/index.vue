<script>
import Header from '../../../../components/header.vue';
import pengajuan from './pengajuan.vue';
import batal from './batal.vue';
import riwayat from './riwayat.vue';
export default {
    components: {
        Header,
        pengajuan,
        batal,
        riwayat,
    },
    data() {
        return {
            dataPengajuan: [
                {
                    id: 1,
                    nama: 'Prita Hanifah',
                    bagian: 'General Affair',
                    tanggal: '03 Januari 2021',
                    tgl_pengajuan: '2021-01-03',
                    jenis: 'Izin Keperluan Kantor',
                },
                {
                    id: 2,
                    nama: 'Prita Hanifah',
                    bagian: 'Produksi',
                    tanggal: '03 Januari 2021',
                    tgl_pengajuan: '2021-01-03',
                    jenis: 'Izin Keperluan Kantor',
                },
            ],
            dataRiwayat: [
                {
                    id: 1,
                    nama: 'Prita Hanifah',
                    bagian: 'General Affair',
                    tanggal: '03 Januari 2021',
                    tgl_pengajuan: '2021-01-03',
                    jenis: 'Izin Keperluan Kantor',
                }
            ],
            dataBatal: [
                {
                    id: 1,
                    nama: 'Prita Hanifah',
                    bagian: 'General Affair',
                    tanggal: '03 Januari 2021',
                    tgl_pengajuan: '2021-01-03',
                    jenis: 'Izin Keperluan Kantor',
                }
            ]
        }
    },
}
</script>
<template>
    <div>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                            Pengajuan
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-riwayat-tab" data-toggle="pill" data-target="#pills-riwayat"
                            type="button" role="tab" aria-controls="pills-riwayat" aria-selected="false">
                            Riwayat
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-batal-tab" data-toggle="pill" data-target="#pills-batal"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                            Batal
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <pengajuan :dataTable="dataPengajuan" />
                    </div>
                    <div class="tab-pane fade" id="pills-riwayat" role="tabpanel" aria-labelledby="pills-riwayat-tab">
                        <riwayat :dataTable="dataRiwayat" />
                    </div>
                    <div class="tab-pane fade" id="pills-batal" role="tabpanel" aria-labelledby="pills-batal-tab">
                        <batal :dataTable="dataBatal" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>