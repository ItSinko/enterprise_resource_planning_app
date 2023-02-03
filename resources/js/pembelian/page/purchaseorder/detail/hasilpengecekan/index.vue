<script>
import detailModal from './detail'
export default {
    components: {
        detailModal
    },
    data() {
        return {
            dataTable: [
                {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    progress: {
                        total : {
                            class : 'bg-success',
                            width : 50
                        },
                        sudah : {
                            class : 'bg-danger',
                            width : 25
                        },
                        belum : {
                            class : 'bg-warning',
                            width : 25
                        },
                    },
                },
            ],
            modalDetail: false,
        }
    },
    methods: {
        close() {
            this.modalDetail = false
            $('.modalDetail').modal('hide')
        },
        detail(index) {
            this.modalDetail = true
            setTimeout(() => {
                $('.modalDetail').modal('show')
            }, 50);
        }
    },
}
</script>
<template>
    <div>
        <detail-modal v-if="modalDetail" @close="close"></detail-modal>
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

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ data.nama_produk }}</td>
                    <td>{{ data.jumlah }}</td>
                    <td>
                                                      <div class="progress">
                                    <div class="progress-bar" v-for="(progress, key) in data.progress" :key="key"
                                        :class="progress.class" role="progressbar"
                                        :style="{width: `${progress.width}%`}" :aria-valuenow="progress.width"
                                        aria-valuemin="0" aria-valuemax="100">{{ progress.width }}%</div>
                                </div>
                    </td>
                    <td>
                        <button class="btn btn-outline-info btn-sm" @click="detail(index)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
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