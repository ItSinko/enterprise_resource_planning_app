<script>
import axios from 'axios'
import inputprice from '../../../components/inputpriceabroad.vue'
export default {
    components: {
        inputprice
    },
    props: {
        kurs: '',
        formPart: [{
            kode_part: '',
            nama_part: '',
            stok: 0,
            jumlah: 0,
            jumlah_beli: 0,
            estimasi_harga: 0,
            harga_beli: 0,
            ongkir: 0,
            biaya_lain: 0,
            konversi: 0,
        }]
    },
    data() {
        return {
            money_kurs: 0,
            api: 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies',
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
                                <inputprice :nilai="money_kurs" v-model="money_kurs" :disabled="false" />
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
                                <td>{{ index + 1 }}</td>
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