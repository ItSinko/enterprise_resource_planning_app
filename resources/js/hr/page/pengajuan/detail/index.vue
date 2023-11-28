<script>
import Header from '../../../components/header.vue';
import izin from './izin.vue';
import keterlambatan from './keterlambatan.vue';
import keperluan_pribadi from './keperluan_pribadi.vue';
import keperluan_kantor from './keperluan_kantor.vue';
import cuti from './cuti.vue';
export default {
    components: {
        Header,
        izin,
        cuti,
        keperluan_kantor,
        keterlambatan,
        keperluan_pribadi
    },
    data() {
        return {
            title: 'Detail Pengajuan',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: this.$route?.params.link ? 'Laporan Pengajuan' : 'Laporan Kehadiran',
                    link: this.$route?.params.link ? this.$route.params.link : '/hr/form_pengajuan'
                },
                {
                    name: 'Detail Pengajuan',
                    link: '/hr/form_pengajuan/detail'
                }
            ],
            item:
            // { "jenis": "Permohonan Tidak Masuk Kerja", "jenisIzin": "sakit", "tanggalAwal": "2023-11-27", "tanggalAkhir": "2023-11-27", "jumlahHari": 1, "keterangan": "mual", "pelimpahan": { "label": "Sri Wahyuni", "value": 1 } }
                // { "jenis": "Permohonan Cuti", "jenisCuti": "lain-lain", "tanggalAwal": "2023-12-04", "tanggalAkhir": "2023-12-06", "jumlahHari": 3, "pelimpahan": { "label": "Sri Wahyuni", "value": 1 }, "jenisCutiLain": "cuti haid" }
            // { "jenis": "Izin Keperluan Kantor", "jenisIzin": "terlambat", "tanggalIzin": "2023-11-27", "waktuAwal": "09:00", "waktuAkhir": "10:00", "jumlahHari": "", "keterangan": "dikarenakan ban bocor", "pelimpahan": "" }
            // { "jenis": "Izin Keterlambatan", "waktuSampai": "19:02", "alasan": "dikarenakan ban bocor" }
            { "jenis": "Izin Keperluan Pribadi", "jenisIzin": "pulang", "alasan": "pindah rumah", "waktu": "16:00" }
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <izin :item="item" v-if="item.jenis == 'Permohonan Tidak Masuk Kerja'"></izin>
                <cuti :item="item" v-if="item.jenis == 'Permohonan Cuti'"></cuti>
                <keperluan_kantor :item="item" v-if="item.jenis == 'Izin Keperluan Kantor'"></keperluan_kantor>
                <keterlambatan :item="item" v-if="item.jenis == 'Izin Keterlambatan'"></keterlambatan>
                <keperluan_pribadi :item="item" v-if="item.jenis == 'Izin Keperluan Pribadi'"></keperluan_pribadi>
            </div>
        </div>
    </div>
</template>