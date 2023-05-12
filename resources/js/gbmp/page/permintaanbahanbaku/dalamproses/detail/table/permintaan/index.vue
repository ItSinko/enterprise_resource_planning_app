<script>
export default {
    data() {
        return {
            penyerahan: [
                {
                    id: 1,
                    kode_part: 'P-001',
                    nama_part: 'Bahan Baku 1',
                    jumlah_set: 1,
                    jumlah: 10,
                    total_jumlah: 10,
                    total_diserahkan: 0
                },
                {
                    id: 2,
                    kode_part: 'P-002',
                    nama_part: 'Bahan Baku 2',
                    jumlah_set: 1,
                    jumlah: 10,
                    total_jumlah: 10,
                    total_diserahkan: 0
                }
            ],
            penyerahanChecked: [],
            search: ''
        }
    },
    methods: {
        checkAll() {
            if(this.penyerahanChecked.length === this.penyerahan.length) {
                this.penyerahanChecked = []
                this.$refs.checkAll.checked = false
                this.$refs.cbChild.forEach((item) => {
                    item.checked = false
                })
            } else {
                this.penyerahanChecked = this.penyerahan.map((item) => item)
                this.$refs.checkAll.checked = true
                this.$refs.cbChild.forEach((item) => {
                    item.checked = true
                })
            }
        },
        checkOne(id) {
            let index = this.penyerahanChecked.findIndex((item) => item.id === id)
            if (index === -1) {
                this.penyerahanChecked.push(this.penyerahan.find((item) => item.id === id))
            } else {
                this.penyerahanChecked.splice(index, 1)
            }
        },
    },
    computed: {
        filteredPenyerahan() {
            return this.penyerahan.filter((item) => {
                return Object.keys(item).some((key) => {
                    return String(item[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        }
    },
    watch: {
        penyerahanChecked() {
            if (this.penyerahanChecked.length === this.penyerahan.length) {
                this.$refs.checkAll.checked = true
            } else {
                this.$refs.checkAll.checked = false
            }
        }
    }
}
</script>
<template>
    <div>
        <div class="d-flex bd-highlight">
        <div class="p-2 flex-grow-1 bd-highlight">
            <button class="btn btn-info" v-if="divisi === '11'" >
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
                <th><input type="checkbox" @click="checkAll" ref="checkAll"></th>
                <th>Kode Part</th>
                <th>Nama Part</th>
                <th>Jumlah / set</th>
                <th>Jumlah</th>
                <th>Total Jumlah</th>
                <th>Total Diserahkan</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in filteredPenyerahan" :key="item.id">
                <td><input type="checkbox" :value="item.id" ref="cbChild" @click="checkOne(item.id)"></td>
                <td>{{ item.kode_part }}</td>
                <td>{{ item.nama_part }}</td>
                <td>{{ item.jumlah_set }}</td>
                <td>{{ item.jumlah }}</td>
                <td>{{ item.total_jumlah }}</td>
                <td>{{ item.total_diserahkan }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>