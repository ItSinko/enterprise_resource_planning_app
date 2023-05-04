<script>
import status from '../../../../../../components/status.vue';
import tambah from './tambah'
import detail from './detail'
import Detail from './tambah/detail.vue';
export default {
    components: {
        status,
        tambah,
        detail,
        Detail
    },
    data() {
        return {
            penyerahan: [
                {
                    no_penyerahan: 'P-001',
                    tgl_penyerahan: '2020-01-01',
                    status: 'kirim barang',
                },
                                {
                    no_penyerahan: 'P-001',
                    tgl_penyerahan: '2020-01-01',
                    status: 'terima barang',
                },
                {
                    no_penyerahan: 'P-002',
                    tgl_penyerahan: '2020-01-01',
                    status: 'selesai',
                }
            ],
            search: '',
            modal: false,
            modalDetail: false,
        }
    },
    methods: {
        showModal() {
            this.modal = true;
            this.$nextTick(() => {
                $('.tambahPenyerahan').modal('show');
            });
        },
        showModalDetail() {
            this.modalDetail = true;
            this.$nextTick(() => {
                $('.modalDetailPenyerahan').modal('show');
            });
        },
        closeModalTambah() {
            this.modal = false;
            this.modalDetail = false;
            $('.tambahPenyerahan').modal('hide');
            $('.modalDetailPenyerahan').modal('hide');
        },
        terimaBarang() {
            this.$swal({
                title: 'Terima Penyerahan?',
                text: "Apakah anda yakin untuk menerima barang ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, terima barang!'
            }).then((result) => {
                if (result.value) {
                    this.$swal(
                        'Terima Barang!',
                        'Data berhasil di terima.',
                        'success'
                    )
                }
            })
        },
    },
    computed: {
        filteredPenyerahan() {
            return this.penyerahan.filter(penyerahan => {
                return Object.keys(penyerahan).some(key => {
                    return String(penyerahan[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        }
    }
}
</script>
<template>
    <div>
        <tambah v-if="modal" @close="closeModalTambah" />
        <detail v-if="modalDetail" @close="closeModalTambah" />
        <div class="d-flex bd-highlight">
        <div class="p-2 flex-grow-1 bd-highlight">
            <button class="btn btn-info" v-if="divisi === '11'"  @click="showModal">
                <i class="fa fa-plus"></i>
                Buat Penyerahan
            </button>
        </div>
        <div class="p-2 bd-highlight">
            <div class="form-group"><input type="search" class="form-control" placeholder="cari" v-model="search"></div>
        </div>
        </div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Penyerahan</th>
                    <th>Tgl Penyerahan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(penyerahan, index) in filteredPenyerahan" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ penyerahan.no_penyerahan }}</td>
                    <td>{{ formatTanggal(penyerahan.tgl_penyerahan) }}</td>
                    <td>
                        <button class="btn btn-sm btn-info" @click="terimaBarang" v-if="penyerahan.status === 'terima barang'" >Terima Barang</button>
                        <status :status="penyerahan.status" v-else/>
                    </td>
                    <td>
                        <i class="fas fa-eye text-info" @click="showModalDetail"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>