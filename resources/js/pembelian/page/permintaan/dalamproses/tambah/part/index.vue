<script>
import Modal from './modal'
export default {
    components: {
        Modal
    },
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
        nopp: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            formPart: [],
            detailData: null,
            modal: false
        }
    },
    methods: {
        addPart() {
            this.detailData = null
            this.modal = true
            setTimeout(() => {
                $('.modalPart').modal('show')
            }, 100);
        },
        simpanPart(data){
            const { form, detail, idx } = data
            if(idx !== null){
                this.formPart.splice(idx, 1, {
                    ...form,
                    detail
                })
            }else{
                const mapData = {
                ...form,
                detail
            }
            this.formPart.push(mapData)
            }
            this.close()
        },
        detail(idx) {
            const data = {
                ...this.formPart[idx],
                idx
            }
            this.detailData = data
            this.modal = true
            setTimeout(() => {
                $('.modalPart').modal('show')
            }, 100);
        },
        close() {
            this.modal = false
            $('.modalPart').modal('hide')
        },
    }
}
</script>
<template>
    <div class="card">
        <Modal v-if="modal" :detail="detailData" @simpan="simpanPart" @close="close"/>
        <div class="card-body">
            <h4 class="card-title text-bold">Daftar Part</h4>
            <div class="card-text">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" @click="addPart">
                        <i class="fa fa-plus"></i>
                        Tambah Part</button>
                </div>

                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Versi BOM</th>
                            <th>Jumlah</th>
                            <th>Detail</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody v-if="formPart.length > 0">
                        <tr v-for="(item, idx) in formPart" :key="idx">
                            <td>{{ idx + 1 }}</td>
                            <td>{{ item.produk.label }}</td>
                            <td>{{ item.versi.label }}</td>
                            <td>{{ item.jumlah }}</td>
                            <td>
                                <button class="btn btn-outline-info btn-sm" @click="detail(idx)">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </td>
                            <td>
                                <i class="fa fa-minus text-danger" aria-hidden="true"></i>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>