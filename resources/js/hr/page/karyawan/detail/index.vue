<script>
import Header from '../../../components/header.vue';
import headerDetail from './header.vue';
import informasiKeluarga from './detailKaryawan/informasiKeluarga.vue';
import informasiKeuangan from './detailKaryawan/informasiKeuangan.vue';
import informasiPekerjaan from './detailKaryawan/informasiPekerjaan.vue';
import informasiPendidikan from './detailKaryawan/informasiPendidikan.vue';
import informasiPendukung from './detailKaryawan/informasiPendukung.vue';
export default {
    components: {
        Header,
        headerDetail,
        informasiPekerjaan,
        informasiKeuangan,
        informasiPendidikan,
        informasiKeluarga,
        informasiPendukung
    },
    data() {
        return {
            title: 'Detail Karyawan',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Karyawan',
                    link: '/hr/karyawan'
                },
                {
                    name: 'Detail Karyawan',
                    link: '#'
                }
            ],
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <headerDetail />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-pekerjaan-tab" data-toggle="pill" data-target="#pills-pekerjaan"
                    type="button" role="tab" aria-controls="pills-pekerjaan" aria-selected="true">Informasi Pekerjaan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-keuangan-tab" data-toggle="pill" data-target="#pills-keuangan"
                    type="button" role="tab" aria-controls="pills-keuangan" aria-selected="false">Informasi Keuangan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-pendidikan-tab" data-toggle="pill" data-target="#pills-pendidikan"
                    type="button" role="tab" aria-controls="pills-pendidikan" aria-selected="false">Informasi
                    Pendidikan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-keluarga-tab" data-toggle="pill" data-target="#pills-keluarga"
                    type="button" role="tab" aria-controls="pills-keluarga" aria-selected="false">Informasi
                    Keluarga</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-pendukung-tab" data-toggle="pill" data-target="#pills-pendukung"
                    type="button" role="tab" aria-controls="pills-pendukung" aria-selected="false">Informasi
                    Pendukung</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-pekerjaan" role="tabpanel" aria-labelledby="pills-pekerjaan-tab">
                <informasiPekerjaan />
            </div>
            <div class="tab-pane fade" id="pills-keuangan" role="tabpanel" aria-labelledby="pills-keuangan-tab">
                <informasiKeuangan />
            </div>
            <div class="tab-pane fade" id="pills-pendidikan" role="tabpanel" aria-labelledby="pills-pendidikan-tab">
                <informasiPendidikan />
            </div>
            <div class="tab-pane fade" id="pills-keluarga" role="tabpanel" aria-labelledby="pills-keluarga-tab">
                <informasiKeluarga />
            </div>
            <div class="tab-pane fade" id="pills-pendukung" role="tabpanel" aria-labelledby="pills-pendukung-tab">
                <informasiPendukung />
            </div>
        </div>
    </div>
</template>