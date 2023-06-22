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
        simpan() {
            this.$emit('simpan')
            this.$nextTick(() => {
                this.hide()
            })
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
            <table class="table">
                <thead>
                    <tr>
                        <th>No Seri</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in produk.no_seri" :key="index">
                        <td>
                            <autocomplete 
                            @input="getNoSeri(item.text)"
                            :options="no_seri" v-model="item.text" :id="index"></autocomplete>
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