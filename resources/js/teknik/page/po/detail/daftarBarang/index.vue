<script>
import Table from './table.vue'
export default {
    components: {
        Table
    },
    data() {
        return {
            produks: [
                {
                    'nama' : 'Produk 1',
                    'jumlah' : 10,

                }
            ]
        }
    },
}
</script>
<template>
<div class="card">
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
                <Table :dataTable="produks" />
            </div>
        </div>
    </div>
</div>
</template>
<style>
    .box-ket {
        width: 20px;
        height: 20px;
        border-radius: 5px;
        margin-right: 5px;
    }

    .box-grey {
        background-color: #D9D9D9;
    }

    .box-orange {
        background-color: #F06414;
    }

    .box-yellow {
        background-color: #FFC107;
    }

    .box-green {
        background-color: #28A745;
    }

</style>