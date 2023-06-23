<script>
import autocomplete from '../../../components/autocomplete.vue';
import axios from 'axios';
export default {
    props: ['produk'],
    components: {
        autocomplete
    },
    data() {
        return {
            no_seri: []
        }
    },
    methods: {
        show() {
            $('#exampleModal').modal('show')
        },
        hide() {
            this.$emit('hide')
            $('#exampleModal').modal('hide')
        },
        async getNoSeri(term) {
            try {
                const { data } = await axios.post('/api/as/list/no_seri_lama', { term })
                this.no_seri = data.map(produk => produk.noseri)
            } catch (error) {
                console.log(error)
            }
        },
        addNoSerialNumber() {
            this.produk.no_seri.push({
                text: '',
            })
        },
        simpan() {
            if (this.checknoserinotempty) {
                this.$emit('simpan')
                this.hide()
            } else {
                this.$swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No Seri tidak boleh kosong!',
                })
            }
        }
    },
    computed: {
        checknoserinotempty() {
            return this.produk.no_seri.every(noseri => noseri.text != '')
        }
    },
}
</script>
<template>
    <div class="modal fade" id="exampleModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">No Seri</h5>
            <button type="button" class="close" @click="hide">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="d-flex flex-row-reverse">
                <div class="p-2 bd-highlight">
                    <button type="button" class="btn btn-primary" @click="addNoSerialNumber">Tambah No Seri</button>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>No Seri</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in produk.no_seri" :key="index">
                        <td>
                            <autocomplete 
                            @input="getNoSeri(item.text)"
                            :options="no_seri" v-model="item.text" :id="index"></autocomplete>
                        </td>
                        <td>
                            <button class="btn btn-danger" @click="produk.no_seri.splice(index, 1)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="hide">Keluar</button>
            <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
        </div>
        </div>
    </div>
    </div>
</template>