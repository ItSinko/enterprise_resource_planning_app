<script>
import axios from 'axios'
import detailMemo from './detail.vue'
import produk from './produk.vue'
export default {
    props: ['id'],
    components: {
        detailMemo,
        produk
    },
    data() {
        return {
            detail: null
        }
    },
    methods: {
        async getDetail() {
            try {
                const { data } = await axios.get(`/api/as/retur/data_detail?id=${this.id}`)
                this.detail = data
            } catch (error) {
                console.log(error)
            }
        },
        closeModal() {
            this.$emit('closeModal')
            $('.modalRetur').modal('hide')
        }
    },
    created() {
        this.getDetail()
    }
}
</script>
<template>
    <div class="modal modalRetur" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Memo Retur</h5>
                <button type="button" class="close" @click="closeModal">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <div class="row">
                            <div class="col-lg-12 col-md-6">
                                <div class="info-box bg-navy removeshadow">
                                    <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">No Referensi Transaksi</span>
                                    <span class="info-box-number" id="no_pesanan">{{ detail?.no_pesanan }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="info-box bg-orange removeshadow">
                                    <span class="info-box-icon"><i class="fas fa-user"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Nama Customer</span>
                                    <span class="info-box-number" id="customer_nama">{{ detail?.customer }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="info-box bg-olive removeshadow">
                                    <span class="info-box-icon"><i class="fas fa-map-marker-alt"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Alamat</span>
                                    <span class="info-box-number" id="alamat">{{ detail?.alamat }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="info-box bg-light removeshadow">
                                    <span class="info-box-icon"><i class="fas fa-phone"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Telepon</span>
                                    <span class="info-box-number" id="telepon">{{ detail?.telp }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-memo-tab" data-toggle="pill" data-target="#pills-memo" type="button" role="tab" aria-controls="pills-memo" aria-selected="true">Detail Memo</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-produk-tab" data-toggle="pill" data-target="#pills-produk" type="button" role="tab" aria-controls="pills-produk" aria-selected="false">Data Produk</a>
                            </li>
                            </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-memo" role="tabpanel" aria-labelledby="pills-memo-tab">
                                <detailMemo :detail="detail"></detailMemo>
                            </div>
                            <div class="tab-pane fade" id="pills-produk" role="tabpanel" aria-labelledby="pills-produk-tab">
                                <produk :dataTable="detail?.produk"></produk>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
            </div>
            </div>
        </div>
    </div>
</template>