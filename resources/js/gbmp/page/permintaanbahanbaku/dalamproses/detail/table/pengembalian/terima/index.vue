<script>
import Header from '../../header.vue'
import TablePengembalian from './tablePengembalian.vue'
import TableBuat from './tableBuat.vue'
export default {
    components: {
        Header,
        TablePengembalian,
        TableBuat
    },
    props: {

    },
    data() {
        return {
            formDetail: {
                no_pengembalian: 'P-001',
                tgl_pengembalian: new Date().toISOString().slice(0, 10),
                dataTablePengembalian: [
                    {
                        part: 'P-001 - Part 1',
                        jumlah_diserahkan: 22,
                        jumlah_ok: 0,
                        jumlah_nok: 0,
                        keterangan: '-', 
                    }
                ],
                dataTableNew: [
                    {
                        part: 'P-001 - Part 1',
                        jumlah_diserahkan: 22,
                        jumlah_kembali: 0,
                        keterangan: '-', 
                    }
                ]
            }
        }
    },
    methods: {
        tambahPart() {
            this.formDetail.dataTableNew.push({
                part: 'P-001 - Part 1',
                jumlah_diserahkan: 22,
                jumlah_ok: 0,
                jumlah_nok: 0,
                keterangan: '-', 
            })
        },
    },
}
</script>
<template>
<div class="modal fade modalTerima" id="modelId" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-keyboard="false" data-backdrop="static" style="display: block">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terima Pengembalian</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">

                <Header />

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-5">Detail Pengembalian</h4>
                        <div class="card-text">
                            <div v-if="divisi === '11'">
                                <div class="row">
                                    <div class="col-1">
                                        <i class="fa fa-credit-card" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-5">
                                        <p>No Pengembalian</p>
                                    </div>
                                    <div class="col-6">
                                        {{ formDetail.no_pengembalian }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-1">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-5">
                                        <p>Tanggal Pengembalian</p>
                                    </div>
                                    <div class="col-6">
                                        {{ formatTanggal(formDetail.tgl_pengembalian) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row" v-else>
                                <div class="col-5">
                                    <p class="text-right">Tanggal Pengembalian</p>
                                </div>
                                <div class="col-6">
                                    <input type="date" v-model="formDetail.tgl_pengembalian" class="form-control col-6">
                                </div>
                            </div>
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <div class="p-2 bd-highlight">
                                    <button class="btn btn-info" v-if="divisi !== '11'" @click="tambahPart">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        Tambah Part
                                    </button>
                                </div>
                            </div>
                        </div>

                        <TablePengembalian v-if="divisi === '11'" :dataTable="formDetail.dataTablePengembalian" />
                        <TableBuat v-else :dataTable="formDetail.dataTableNew" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
</template>