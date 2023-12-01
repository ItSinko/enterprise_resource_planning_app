<script>
import pengajuan from './pengajuan.vue';
import riwayat from './riwayat.vue';
export default {
    components: {
        pengajuan,
        riwayat
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
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-pengajuanAtasan-tab" data-toggle="pill" data-target="#pills-pengajuanAtasan"
                        type="button" role="tab" aria-controls="pills-pengajuanAtasan" aria-selected="true">Pengajuan</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-riwayatAtasan-tab" data-toggle="pill" data-target="#pills-riwayatAtasan"
                        type="button" role="tab" aria-controls="pills-riwayatAtasan" aria-selected="false">Riwayat</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-pengajuanAtasan" role="tabpanel" aria-labelledby="pills-pengajuanAtasan-tab">
                    <pengajuan :dataTable="dataPengajuan" />
                </div>
                <div class="tab-pane fade" id="pills-riwayatAtasan" role="tabpanel" aria-labelledby="pills-riwayatAtasan-tab">
                    <riwayat :dataTable="dataRiwayat" />
                </div>
            </div>
        </div>
    </div>
</template> 