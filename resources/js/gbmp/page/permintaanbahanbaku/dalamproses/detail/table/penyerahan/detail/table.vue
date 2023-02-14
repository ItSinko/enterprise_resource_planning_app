<script>
import detailLot from './detailLot.vue'
export default {
    components: {
        detailLot
    },
    data() {
        return {
            dataTable: [
                {
                    id: 1,
                    kode_part: 'P-001',
                    nama_part: 'Part 1',
                    jumlah_diserahkan: 100,
                    detail: [
                        {
                            lot_number: 'P-001 - Lot 1',
                            jumlah_diserahkan: 100,
                        },
                        {
                            lot_number: 'P-001 - Lot 2',
                            jumlah_diserahkan: 100,
                        }
                    ]
                },
                {
                    id: 2,
                    kode_part: 'P-002',
                    nama_part: 'Part 2',
                    jumlah_diserahkan: 100,
                    detail: [
                        {
                            lot_number: 'P-001 - Lot 1',
                            jumlah_diserahkan: 100,
                        },
                        {
                            lot_number: 'P-001 - Lot 2',
                            jumlah_diserahkan: 100,
                        }
                    ]
                }
            ],
            detail: [],
            modal: false,
        }
    },
    methods: {
        detailOpen(id) {
            let index = this.dataTable.findIndex(data => data.id == id)
            this.detail = this.dataTable[index].detail
            this.modal = true
            this.$nextTick(() => {
                $('.detailLot').modal('show')
            })
        },
        closeLotModal() {
            this.modal = false
            $('.detailLot').modal('hide')
        }
    }
}
</script>
<template>
    <div>
        <detail-lot :dataTable="detail" v-if="modal" @close="closeLotModal"></detail-lot>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Kode Part</th>
                    <th>Nama Part</th>
                    <th>Jumlah Diserahkan</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ data.kode_part }}</td>
                    <td>{{ data.nama_part }}</td>
                    <td>{{ data.jumlah_diserahkan }}</td>
                    <td>
                        <i class="fa fa-eye text-info" aria-hidden="true" @click="detailOpen(data.id)"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>