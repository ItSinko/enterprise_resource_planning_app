<script>
import Header from '../../../components/header.vue';
import izin from './izin.vue';
import cuti from './cuti.vue';
import keperluanKantor from './keperluan_kantor.vue';
import keperluanPribadi from './keperluan_pribadi.vue';
import keterlambatan from './keterlambatan.vue';
export default {
    components: {
        Header,
        izin,
        cuti,
        keperluanKantor,
        keperluanPribadi,
        keterlambatan
    },
    data() {
        return {
            title: 'Pengajuan Form Kehadiran',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Laporan Kehadiran',
                    link: '/hr/form_pengajuan'
                },
                {
                    name: 'Pengajuan Form Kehadiran',
                    link: '/hr/form_pengajuan'
                }
            ],
            search: '',
            isOpenFormIzin: false,
            isOpenFormCuti: false,
            isOpenFormKeperluanKantor: false,
            isOpenFormKeperluanPribadi: false,
            isOpenFormKeterlambatan: false,
            openForm: '',
            jenisForm: [
                'Permohonan Tidak Masuk Kerja',
                'Permohonan Cuti',
                'Izin Keperluan Kantor',
                'Izin Keterlambatan',
                'Izin Keperluan Pribadi',
            ]
        }
    },
    watch: {
        openForm() {
            switch (this.openForm) {
                case 'Permohonan Tidak Masuk Kerja':
                    this.isOpenFormIzin = true;
                    this.isOpenFormCuti = false;
                    this.isOpenFormKeperluanKantor = false;
                    this.isOpenFormKeperluanPribadi = false;
                    this.isOpenFormKeterlambatan = false;
                    break;
                case 'Permohonan Cuti':
                    this.isOpenFormIzin = false;
                    this.isOpenFormCuti = true;
                    this.isOpenFormKeperluanKantor = false;
                    this.isOpenFormKeperluanPribadi = false;
                    this.isOpenFormKeterlambatan = false;
                    break;
                case 'Izin Keperluan Kantor':
                    this.isOpenFormIzin = false;
                    this.isOpenFormCuti = false;
                    this.isOpenFormKeperluanKantor = true;
                    this.isOpenFormKeperluanPribadi = false;
                    this.isOpenFormKeterlambatan = false;
                    break;
                case 'Izin Keperluan Pribadi':
                    this.isOpenFormIzin = false;
                    this.isOpenFormCuti = false;
                    this.isOpenFormKeperluanKantor = false;
                    this.isOpenFormKeperluanPribadi = true;
                    this.isOpenFormKeterlambatan = false;
                    break;
                case 'Izin Keterlambatan':
                    this.isOpenFormIzin = false;
                    this.isOpenFormCuti = false;
                    this.isOpenFormKeperluanKantor = false;
                    this.isOpenFormKeperluanPribadi = false;
                    this.isOpenFormKeterlambatan = true;
                    break;
                default:
                    this.isOpenFormIzin = false;
                    this.isOpenFormCuti = false;
                    this.isOpenFormKeperluanKantor = false;
                    this.isOpenFormKeperluanPribadi = false;
                    this.isOpenFormKeterlambatan = false;
                    break;
            }
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="form-group row mb-4">
                    <label for="staticEmail" class="col-md-2 col-form-label">Pilih Jenis Form</label>
                    <div class="col-md-3">
                        <select class="form-control" v-model="openForm">
                            <option value="" selected>Pilih Jenis Form</option>
                            <option v-for="item in jenisForm" :key="item" :value="item">{{ item }}</option>
                        </select>
                    </div>
                </div>

                <izin v-if="isOpenFormIzin" />
                <cuti v-if="isOpenFormCuti" />
                <keperluanKantor v-if="isOpenFormKeperluanKantor" />
                <keperluanPribadi v-if="isOpenFormKeperluanPribadi" />
                <keterlambatan v-if="isOpenFormKeterlambatan" />
            </div>
        </div>
    </div>
</template>