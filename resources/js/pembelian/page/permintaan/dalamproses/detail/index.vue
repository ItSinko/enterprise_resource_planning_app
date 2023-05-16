<script>
    import Header from './header'
    import DaftarBarangUmum from './daftarbarang/umum'
    import DaftarBarangPart from './daftarbarang/part'
    import DaftarPO from './daftarPO'
    import axios from 'axios'
    export default {
        components: {
            Header,
            DaftarBarangUmum,
            DaftarBarangPart,
            DaftarPO
        },
        props: {
            detail: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                headers: {},
                dataTableUmum: [],
                dataTablePart: [],
                dataTablePO: [],
            }
        },
        mounted() {
            this.getDetail()
        },
        methods: {
            async getDetail(){
                this.$store.dispatch('setLoading', true)
                if (this.detail.jenis == 'umum') {
                    const { data } = await axios.get(`/api/pembelian/pp/${this.detail.id}`).then(res => res.data)
                    const { no_pp, tgl_dibutuhkan, tgl_diminta, jenis, tujuan, aset, po} = data
                    this.headers = {
                        no_pp,
                        tgl_dibutuhkan,
                        tgl_diminta,
                        jenis,
                        tujuan
                    }
                    this.dataTableUmum = aset
                    this.dataTablePO = po ?? []
                }else{
                    const { data } = await axios.get(`/api/pembelian/pp/${this.detail.id}`).then(res => res.data)
                    const { no_pp, tgl_dibutuhkan, tgl_diminta, jenis, tujuan, part, po} = data
                    this.headers = {
                        no_pp,
                        tgl_dibutuhkan,
                        tgl_diminta,
                        jenis,
                        tujuan
                    }
                    this.dataTablePart = part
                    this.dataTablePO = po ?? []
                }
                this.$store.dispatch('setLoading', false)
            },
            close() {
                this.$emit('close')
            },
            deletePO(id) {
                this.$swal({
                    title: 'Hapus Permintaan',
                    text: "Anda tidak akan dapat melihat dan merubah Permintaan Pembelian ini lagi",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        try {
                        // axios.delete(`/api/pembelian/pp/${id}/umum`)
                        this.$swal(
                            'Terhapus!',
                            'Data berhasil dihapus.',
                            'success'
                        )   
                        this.getDetail()
                        } catch (error) {
                            this.$swal(
                                'Gagal!',
                                'Data gagal dihapus.',
                                'error'
                            )
                            console.log(error)
                        }
                    }
                })
            },
            terima(id){
                this.$swal({
                    title: 'Terima Barang',
                    text: "Anda yakin ingin menerima barang dengan benar?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, terima!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        this.$swal(
                            'Terima Barang!',
                            'Barang berhasil diterima.',
                            'success'
                        )
                    }
                })
            }
        }
    }

</script>
<template>
    <div class="modal fade modalDetail" id="modelId" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Permintaan Pembelian</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-if="!$store.state.loading">
                    <Header :header="headers" />
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-barang-tab" data-toggle="pill"
                                data-target="#pills-barang" type="button" role="tab" aria-controls="pills-barang"
                                aria-selected="true">
                                Daftar Barang
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-po-tab" data-toggle="pill" data-target="#pills-po"
                                type="button" role="tab" aria-controls="pills-po" aria-selected="false">
                                Daftar PO
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-barang" role="tabpanel"
                            aria-labelledby="pills-barang-tab">
                            <DaftarBarangUmum :dataTable="dataTableUmum" @deleteBarang="deletePO" v-if="detail.jenis == 'umum'"/>
                            <DaftarBarangPart :dataTable="dataTablePart" v-else/>
                        </div>
                        <div class="tab-pane fade" id="pills-po" role="tabpanel" aria-labelledby="pills-po-tab">
                            <DaftarPO :dataTable="dataTablePO" @del="deletePO" @terima="terima"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
