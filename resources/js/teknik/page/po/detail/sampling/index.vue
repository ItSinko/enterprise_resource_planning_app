<script>
    import Table from './table.vue'
    import Detail from './detail.vue'
    export default {
        components: {
            Table,
            Detail
        },
        data() {
            return {
                dataTable : [
                    {
                        id: 1,
                        nama_produk: 'Produk 1',
                        no_laporan: 'Laporan 1',
                        tgl_sampling: '2020-01-01',
                        jumlah_sampling: '10',
                        keterangan: 'Keterangan 1',
                        status: 'selesai',
                    },
                    {
                        id: 2,
                        nama_produk: 'Produk 2',
                        no_laporan: 'Laporan 2',
                        tgl_sampling: '2020-01-02',
                        jumlah_sampling: '20',
                        keterangan: 'Keterangan 2',
                        status: 'belum selesai',
                    }
                ],
                detail_headers: ''
            }
        },
        methods: {
            detail(idx) {
                this.detail_headers = this.dataTable[idx].nama_produk
                setTimeout(() => {
                    $('.modalDetailSampling').modal('show')
                }, 100);
            }
        }
    }
</script>
<template>
    <div class="card">
        <detail :headers="detail_headers"/>
        <div class="card-body">
            <div class="card-text">
                <div class="card-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex">
                                    <div class="box-ket box-grey"></div>
                                    <p class="pr-2">Jumlah Barang yang <strong>belum</strong> diperiksa</p>
                                </div>
                                <div class="d-flex">
                                    <div class="box-ket box-orange"></div>
                                    <p class="pr-2">Jumlah Barang yang belum diperiksa <strong>Gudang</strong></p>
                                </div>
                                <div class="d-flex">
                                    <div class="box-ket box-yellow"></div>
                                    <p class="pr-2">Jumlah Barang yang belum diperiksa <strong>QC</strong></p>
                                </div>
                                <div class="d-flex">
                                    <div class="box-ket box-green"></div>
                                    <p class="pr-2">Jumlah Barang yang <strong>telah</strong> diperiksa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Table :dataTable="dataTable" @detail="detail"/>
                </div>
            </div>
        </div>
    </div>
</template>
