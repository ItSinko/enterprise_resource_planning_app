<script>
import Detail from './detailPart'
export default {
    components: {
        Detail
    },
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
    },
    data() {
        return {
            title: ''
        }
    },
    methods: {
        detail(data) {
            $('.modalDetail').modal('show')
            this.title = data
        },
        analisa(data) {
            this.$emit('analisa', data)
        },
        klasifikasi(data) {
            this.$emit('klasifikasi', data)
        },
    },
}
</script>
<template>
    <div>
        <Detail :title="title"/>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(table, idx) in dataTable" :key="idx">
                    <td>{{ table.nama }}</td>
                    <td>{{ table.jumlah }}</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" v-for="(progres, idx) in table.progress" :key="idx" :class="progres.class" role="progressbar" :style="{width: progres.width + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{ progres.width }}%</div>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm" @click="analisa(idx)" v-if="table.aksi == 'analisa'">
                            Analisa Pemeriksaan Barang
                        </button>
                        <button class="btn btn-warning btn-sm" @click="klasifikasi(table.nama)" v-if="table.aksi == 'klasifikasi'">
                            Klasifikasi Part
                        </button>
                        <button class="btn-sm btn-outline-info btn" @click="detail(table.nama)">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>