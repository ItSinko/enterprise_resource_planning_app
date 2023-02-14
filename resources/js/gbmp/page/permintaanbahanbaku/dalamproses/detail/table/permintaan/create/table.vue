<script>
export default {
    props: {
        form: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        tambah(){
            this.form.push({
                    part: {
                        value: 1,
                        label: 'P-001 - Part 1'
                    },
                    jumlah_diminta: 100,
            })
        },
        hapus(idx){
            this.form.splice(idx, 1)
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Daftar Part</h4>
            <div class="card-text">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" @click="tambah">
                        <i class="fa fa-plus"></i>
                        Tambah Part
                    </button>
                </div>

                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Part - Nama Part</th>
                            <th>Jumlah Diminta</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in form" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>
                                <v-select v-model="item.part" />
                            </td>
                            <td>{{ item.jumlah_diminta }}</td>
                            <td>
                                <i class="fa fa-minus text-danger" aria-hidden="true" @click="hapus(index)"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>