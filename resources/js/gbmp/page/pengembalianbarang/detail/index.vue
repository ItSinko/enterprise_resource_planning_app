<script>
import Header from '../../penyerahanbarang/dalamproses/detail/header.vue'
import status from '../../../components/status.vue'
import Table from './table.vue'
export default {
    components: {
        Header,
        status,
        Table
    },
    data() {
        return {
            formHeader: {
                no_pengembalian: '123456789',
                tgl_pengembalian: '2020-01-01',
                status: 'selesai',
            },
            dataTable: [
                {
                    kode_part: '123456789',
                    nama_part: 'Part 1',
                    jumlah_kembali: 10,
                },
                {
                    kode_part: '123456789',
                    nama_part: 'Part 2',
                    jumlah_kembali: 10,
                },
                {
                    kode_part: '123456789',
                    nama_part: 'Part 3',
                    jumlah_kembali: 10,
                },
            ]
        }
    },
}
</script>
<template>
    <div class="modal fade modalPengembalian" id="modelId" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pengembalian Barang</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <Header />

                    <div class="row">
                        <div class="col-1">
                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                        </div>
                        <div class="col-5">
                            <p>No Pengembalian</p>
                        </div>
                        <div class="col-6">
                            <p>{{ formHeader.no_pengembalian }}</p>
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
                            <p>{{ formatTanggal(formHeader.tgl_pengembalian) }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-1">
                            <i class="fa fa-info" aria-hidden="true"></i>
                        </div>
                        <div class="col-5">
                            <p>Status</p>
                        </div>
                        <div class="col-6">
                            <p>
                                <status :status="formHeader.status" />
                            </p>
                        </div>
                    </div>

                    <Table :dataTable="dataTable" />
                </div>
            </div>
        </div>
    </div>
</template>