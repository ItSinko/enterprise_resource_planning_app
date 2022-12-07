<script>
import Table from './table.vue'
import Header from './header.vue'
export default {
    components: {
        Table,
        Header
    },
    data() {
        return {
            dataTable : [
                {
                    nama_produk : 'Produk 1',
                    jumlah_diterima : 10,
                    jumlah_sample : 5,
                    jumlah_ok : 5,
                    jumlah_nok : 0,
                    permasalahan: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.',
                    keterangan: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.'
                },
                {
                    nama_produk : 'Produk 2',
                    jumlah_diterima : 10,
                    jumlah_sample : 5,
                    jumlah_ok : 5,
                    jumlah_nok : 0,
                    permasalahan: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.',
                    keterangan: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.'
                }
            ]
        }
    },
}
</script>
<template>
    <div class="modal fade modalAnalisa" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Analisa Teknik</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <Header />
                    <div class="row">
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Analisis Pemeriksaan Barang</h4>
                                    <div class="card-text">
                                        <label for="">Tanggal Analisis</label>
                                        <input type="date" class="form-control">
                                        <label for="">Hasil Analisa</label>
                                        <textarea name="" id="" cols="5" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <Table :dataTable="dataTable"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .card-danger{
        background-color: #f5efef;
        color: #985d61;
    }
    .card-purple{
        background-color: #ece8eb;
        color: #7f6377;
    }
    .card-secondary{
        background-color: #e7e7e7;
        color: #616161;
    }
    .card-primary{
        background-color: #dee4e9;
        color: #5b7a8f;
    }
    .card-success{
        background-color: #e0e2df;
        color: #616e61;
    }
</style>