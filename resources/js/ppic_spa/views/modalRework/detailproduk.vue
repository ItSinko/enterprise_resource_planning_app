<script>
import axios from 'axios';
export default {
    props: ['showModal', 'dataProduk'],
    data() {
        return {
            produkDetail: [],
            detail: JSON.parse(JSON.stringify(this.dataProduk?.detail))
        }
    },
    methods: {
        async getProdukDetail() {
            try {
                const { data } = await axios.get(`/api/ppic/data/produkidgbj`)
                this.produkDetail = data
            } catch (error) {
                console.log(error);
            }
        },
        closeModal() {
            this.$emit('closeModal')
        },
        maksimumInput(stok, jumlah) {
            return stok < jumlah ? true : false
        }
    },
    created() {
        this.getProdukDetail()
    },
}
</script>
<template>
    <div class="modal" :class="{ 'is-active': showModal }">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Detail Produk {{ dataProduk?.nama_produk.label }}</p>
                <button class="delete" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="data in detail">
                            <tr>
                                <td>
                                    <v-select :options="produkDetail" v-model="data.produk" />
                                </td>
                                <td v-if="data.produk">
                                    <input type="text" class="input" @keypress="numberOnly($event)" v-model="data.jumlah"
                                        :class="{ 'is-danger': maksimumInput(data?.produk.stok, data.jumlah) }">
                                    <p v-if="maksimumInput(data?.produk.stok, data.jumlah)" class="help is-danger">Jumlah
                                        Melebihi Stok</p>
                                    <p v-if="data?.produk.stok">
                                        <b>GBJ</b> : {{ data?.produk.stok }}
                                    </p>
                                </td>
                            </tr>
                            <template v-if="data.produk?.gbj && data.produk?.gbj.length > 0">
                                <tr v-for="variasi in data.produk?.gbj">
                                    <td>
                                        <p>
                                            <b>{{ variasi.label }}</b>
                                        </p>
                                    </td>
                                    <td>
                                        <input type="text" class="input" @keypress="numberOnly($event)">
                                        <p>
                                            <b>GBJ</b> : {{ variasi.stok }}
                                        </p>
                                    </td>
                                </tr>
                            </template>
                            
                        </template>

                    </tbody>
                </table>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success">Tambah</button>
            </footer>
        </div>
    </div>
</template>
<style scoped>
.table {
    width: 100%;
}
</style>