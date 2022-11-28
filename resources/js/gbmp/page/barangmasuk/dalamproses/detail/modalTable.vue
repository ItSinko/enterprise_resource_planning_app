<script>
export default {
    props: {
        // parts: {
        //     type: Array,
        //     required: true
        // }
        nama_produk: {
            type: String,
            required: true
        },
    },
    data() {
        return {
            parts: [{
                'kode_part': 'PRT001',
                'nama_part': 'Part 1',
                'layout': 'Layout 1',
                'dipesan': 10,
                'diterima': 5,
                'jumlah': 10,
                'total': 250,
            }]   
        }
    },
}
</script>
<template>
    <div class="modal fade modalDetail" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-bold">{{ nama_produk }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                            <table class="table tableDaftarBarang">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Kode & Nama Part</th>
                    <th>Layout</th>
                    <th>Jumlah Dipesan</th>
                    <th>Jumlah Diterima</th>
                    <th>Jumlah <small>/ set</small></th>
                    <th>Total Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(part, index) in parts" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ part.kode_part }} - {{ part.nama_part }}</td>
                    <td>{{ part.layout }}</td>
                    <td>{{ part.dipesan }}</td>
                    <td>{{ part.diterima }}</td>
                    <td>{{ part.jumlah }}</td>
                    <td>{{ part.total }}</td>
                </tr>
            </tbody>
        </table>
                </div>
            </div>
        </div>
    </div>
</template>