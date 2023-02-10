<script>
export default {
    props: {
        dataTable: {
            type: Object,
            default: () => {}
        },
    },
    data() {
        return {
            detailData: JSON.parse(JSON.stringify(this.dataTable))
        }
    },
    methods: {
        tambahdraft() {
            this.$emit('tambahdraft', this.detailData)
        },
        removeLot(idx) {
            this.detailData.detail.splice(idx, 1)
        },
    },
}
</script>
<template>
    <div class="modal fade modalPartDetail" id="modelId" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Part</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Detail Part</h4>
                            <div class="card-text">
                                <div class="row">
                                    <div class="col">
                                        <p>Kode Part</p>
                                    </div>
                                    <div class="col">
                                        <p>{{ dataTable.kode_part }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p>Nama Part</p>
                                    </div>
                                    <div class="col">
                                        <p>{{ dataTable.nama_part }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p>Jumlah Diminta</p>
                                    </div>
                                    <div class="col">
                                        <p>{{ dataTable.jumlah_diminta }}</p>
                                    </div>
                                </div>

                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Lot Number</th>
                                            <th>Jumlah Tersedia</th>
                                            <th>Jumlah Diserahkan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in detailData.detail" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>
                                                <v-select v-model="item.lot_number" />
                                            </td>
                                            <td>{{ item.jumlah_tersedia }}</td>
                                            <td>
                                                <input type="text" 
                                                @keypress="inputNumberOnly($event)" 
                                                v-model.number="item.jumlah_diserahkan"
                                                class="form-control">
                                            </td>
                                            <td>
                                                <i @click="removeLot(index)" class="fa fa-minus text-danger" aria-hidden="true"></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Batal</button>
                    <button type="button" @click="tambahdraft" class="btn btn-warning">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Tambahkan ke Draft
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>