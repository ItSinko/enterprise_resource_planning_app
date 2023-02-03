<script>
import axios from 'axios'
import inputprice from '../../../components/inputpriceabroad.vue'
export default {
    components: {
        inputprice
    },
    props: {
        kurs: '',
    },
    data() {
        return {
            money_kurs: 0,
            api: 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies',
                    formPart: [{
            kode_part: 'SP-0001',
            nama_part: 'Sparepart 1',
            stok: 2,
            jumlah: 45,
            jumlah_beli: 20,
            estimasi_harga: 45000,
            harga_beli: 45000,
            ongkir: 24000,
            biaya_lain: 20000,
            konversi: 0,
        }]
        }
    },
    methods: {
        async getKurs() {
            try {
                const kurs = this.kurs.toLowerCase()
                const { data } = await axios.get(`${this.api}/${kurs}/idr.json`)
                const value = data.idr.toString()
                const toInt = parseInt(value.split('.')[0])
                this.money_kurs = toInt
            } catch (error) {
                console.log(error)
            }
        },
        subtotal(item){
            const jumlah_beli = item.jumlah_beli
            const harga_beli = item.harga_beli
            const ongkir = item.ongkir
            const biaya_lain = item.biaya_lain
            const subtotal = (jumlah_beli * harga_beli) + ongkir + biaya_lain
            return subtotal
        }
    },
    watch: {
        kurs() {
            this.getKurs()
        }
    },
}
</script>
<template>
    <div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Part</h4>
                <div class="card-text">
                    <div class="d-flex justify-content-center">
                        <div class="form-group row">
                            <label for="" class="col-6">Nilai Mata Uang Saat ini</label>
                            <div class="col-6">
                                <inputprice :nilai="money_kurs" :disabled="true" />
                            </div>
                        </div>
                    </div>

                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Kode & Nama Part</th>
                                <th>Jumlah</th>
                                <th>Jumlah Beli</th>
                                <th>Estimasi Harga</th>
                                <th>Harga Beli</th>
                                <th>Ongkir</th>
                                <th>Biaya Lain</th>
                                <th>Subtotal</th>
                                <th>Konversi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(part, index) in formPart" :key="index">
                                <td>{{ index }}</td>
                                <td>
                                    <p>{{ part.kode_part }} - {{ part.nama_part }}</p>
                                    <small class="text-danger">Stok : {{ part.stok }}</small>
                                </td>
                                <td>{{ part.jumlah }}</td>
                                <td>
                                    <input type="text" class="form-control" v-model="part.jumlah_beli">
                                </td>
                                <td>
                                    <inputprice :nilai="part.estimasi_harga" :kurs="kurs" :disabled="true"/>
                                </td>
                                <td>
                                    <inputprice :nilai="part.harga_beli" :kurs="kurs" v-model="part.harga_beli" />
                                </td>
                                <td>
                                    <inputprice :nilai="part.ongkir" :kurs="kurs" v-model="part.ongkir" />
                                </td>
                                <td>
                                    <inputprice :nilai="part.biaya_lain" :kurs="kurs" v-model="part.biaya_lain" />
                                </td>
                                <td>
                                    <inputprice :nilai="subtotal(part)" :kurs="kurs" :disabled="true" />
                                </td>
                                <td>
                                    <inputprice :nilai="subtotal(part) * money_kurs" :disabled="true" v-model="part.konversi"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>