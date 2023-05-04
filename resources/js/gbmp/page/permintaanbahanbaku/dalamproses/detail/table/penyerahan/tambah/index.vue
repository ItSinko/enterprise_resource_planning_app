<script>
import Header from '../../header.vue'
import Table from './table.vue'
export default {
    components: {
        Header,
        Table
    },
    props: {
        modalTitle: {
            type: String,
            default: 'Buat Penyerahan'
        },
    },
    data() {
        return {
            dataTable: [
                {
                    part: {
                        value: 1,
                        label: 'P-001 - Part 1'
                    },
                    kode_part: 'P-001',
                    nama_part: 'Part 1',
                    jumlah_diminta: 100,
                    jumlah_diserahkan: 100,
                    detail: [
                        {
                            lot_number: {
                                value: 1,
                                label: 'P-001 - Lot 1',
                            },
                            jumlah_tersedia: 100,
                            jumlah_diserahkan: 100,
                        },
                        {
                            lot_number: {
                                value: 2,
                                label: 'P-001 - Lot 2'
                            },
                            jumlah_tersedia: 100,
                            jumlah_diserahkan: 100,
                        }
                    ]
                },
                {
                    part: {
                        value: 2,
                        label: 'P-002 - Part 2'
                    },
                    jumlah_diminta: 100,
                    jumlah_diserahkan: 100,
                    detail: [
                        {
                            lot_number: {
                                value: 1,
                                label: 'P-001 - Lot 1',
                            },
                            jumlah_tersedia: 100,
                            jumlah_diserahkan: 100,
                        },
                        {
                            lot_number: {
                                value: 2,
                                label: 'P-001 - Lot 2'
                            },
                            jumlah_tersedia: 100,
                            jumlah_diserahkan: 100,
                        }
                    ]
                }
            ]
        }
    },
    methods: {
        tambahdraft(data, index){
            this.dataTable[index] = data
        }
    },
}
</script>
<template>
    <div class="modal fade tambahPenyerahan" id="modelId" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ modalTitle }}</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <Header />

                    <div class="card">
                        <div class="card-body">
                                <div class="center">
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-4 text-right col-form-label">No. Penyerahan</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="staticEmail" value="P-001">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-4 text-right col-form-label">Tanggal Penyerahan</label>
                                    <div class="col-sm-5">
                                        <input type="date" class="form-control" id="staticEmail" value="01/01/2020">
                                    </div>
                                </div>
                                </div>

                            <Table :dataTable="dataTable" @tambahdraft="tambahdraft" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>