<script>
import axios from 'axios'
import VueSelect from 'vue-select'
export default {
    props: ['meeting'],
    components: {
        VueSelect
    },
    data() {
        return {
            catatan: [
                {
                    pic: {
                        id: 1,
                        nama: 'Sri Wahyuni'
                    },
                    catatan: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.',
                }
            ]
        }
    },
    methods: {
        closeModal() {
            $('.modalCatatan').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        async getDataKaryawan() {
            try {
                const response = await axios.get('/api/karyawan_all')
                this.karyawan = response.data
            } catch (error) {
                console.log(error)
            }
        },
    },
    mounted() {
        this.getDataKaryawan()
    }
}
</script>
<template>
    <!-- Modal -->
    <div class="modal fade modalCatatan" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Catatan Peserta Meeting</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div v-for="(notulen, idx) in catatan" class="row mb-1">
                        <div class="col-sm-4">
                            <!-- find pic by id -->
                            <vue-select disabled v-model="notulen.pic" :options="karyawan" :reduce="karyawan => karyawan.id" label="nama" />
                        </div>
                        <div class="col-sm-8">
                            <textarea class="form-control" disabled v-model="notulen.isi" placeholder="Isi Notulensi"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>