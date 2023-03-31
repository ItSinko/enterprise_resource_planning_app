<script>
import axios from 'axios'
export default {
    props: {
        formSupplier: {
            supplier: '',
            alamat: '',
            telepon: '',
            kurs: '',
        },
    },
    data() {
        return {
            supplierSelect: [],
        }
    },
    created() {
        this.getSuppliers()
    },
    methods: {
        async getSuppliers() {
            try {
                const { data } = await axios.get('/api/supplier').then(res => res.data)
                data.forEach(item => {
                    this.supplierSelect.push({
                        value: item.id,
                        label: item.nama,
                        alamat: item.alamat,
                        telepon: item.telepon,
                        kurs: item.kurs,
                    })
                })
            } catch (error) {
                console.log(error)
            }
        },
        mappingSelect(event){
            this.formSupplier.supplier = event.id
            this.formSupplier.alamat = event.alamat
            this.formSupplier.telepon = event.telepon
            this.formSupplier.kurs = event.kurs
        }
    },

}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Informasi Supplier</h4>
            <div class="card-text mt-5">
                <div class="form-group row">
                    <label for="supplier" class="col-sm-4 col-form-label">Supplier</label>
                    <div class="col-sm-8">
                        <v-select :options="supplierSelect" @input="mappingSelect($event)"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="alamat" class="col-sm-4 col-form-label">Alamat</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" disabled id="alamat" rows="3" v-model="formSupplier.alamat"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="telepon" class="col-sm-4 col-form-label">Telepon</label>
                    <div class="col-sm-8">
                        <input type="text" disabled class="form-control" id="telepon" v-model="formSupplier.telepon">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kurs" class="col-sm-4 col-form-label">Kurs</label>
                    <div class="col-sm-8">
                        <input type="text" disabled class="form-control" id="kurs" v-model="formSupplier.kurs">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .card {
        width: 100%;
    }
</style>