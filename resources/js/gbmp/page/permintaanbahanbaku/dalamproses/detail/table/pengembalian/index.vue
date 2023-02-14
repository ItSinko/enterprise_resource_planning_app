<script>
import moment from 'moment';
import status from '../../../../../../components/status.vue';
import terima from './terima'
import detail from './detail'

export default {
    components: {
        status,
        terima,
        detail
    },
    data() {
        return {
            pengembalian: [
                {
                    id: 1,
                    no_pengembalian: 'P-001',
                    tgl_pengembalian: '2020-01-01',
                    status: 'terima pengembalian',
                },
                {
                    id: 2,
                    no_pengembalian: 'P-002',
                    tgl_pengembalian: '2020-01-01',
                    status: 'selesai',
                }
            ],
            search: '',
            modal: false,
            modalTerima: false,
        }
    },
    methods: {
        formatTanggal(date) {
            return date ? moment(date).lang('id').format('LL') : '-'
        },
        closeModalTerima() {
            this.modal = false
            this.modalTerima = false
            $('.modalTerima').modal('hide')
            $('.modalDetail').modal('hide')
        },
        tambah() {
            this.modalTerima = true
            this.$nextTick(() => {
                $('.modalTerima').modal('show')
            })
        },
        terima(id) {
            this.modalTerima = true
            this.$nextTick(() => {
                $('.modalTerima').modal('show')
            })
        },
        detail(id) {
            this.modal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        }
    },
    computed: {
        filteredPengembalian() {
            return this.pengembalian.filter(pengembalian => {
                return Object.keys(pengembalian).some(key => {
                    return String(pengembalian[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        }
    },
}
</script>
<template>
    <div>
        <terima v-if="modalTerima" @close="closeModalTerima"/>
        <detail v-if="modal" @close="closeModalTerima"/>
        <div class="d-flex flex-row-reverse bd-highlight">
        </div>

        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <button 
                class="btn btn-primary" 
                @click="tambah"
                v-if="divisi !== '11'"
                >
                    <i class="fa fa-plus"></i> Tambah Pengembalian
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
                    <th>No Pengembalian</th>
                    <th>Tgl Pengembalian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(pengembalian, index) in filteredPengembalian" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ pengembalian.no_pengembalian }}</td>
                    <td>{{ formatTanggal(pengembalian.tgl_pengembalian) }}</td>
                    <td>
                        <button class="btn btn-info btn-sm" 
                        @click="terima(pengembalian.id)"
                        v-if="pengembalian.status === 'terima pengembalian'">Ajukan Pengembalian</button>
                        <status :status="pengembalian.status" v-else/>
                    </td>
                    <td>
                        <i class="fas fa-eye text-info" @click="detail(pengembalian.id)"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>