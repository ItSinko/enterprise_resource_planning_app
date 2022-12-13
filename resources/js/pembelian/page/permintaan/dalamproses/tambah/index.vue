<script>
    import HeaderFirst from '../../../../components/header'
    import HeaderSecond from './header.vue'
    import Table from './table'
    import moment from 'moment'
    export default {
        components: {
            HeaderFirst,
            HeaderSecond,
            Table
        },
        data() {
            return {
                title: 'Permintaan Pembelian',
                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Permintaan Pembelian',
                        link: '/pembelian/permintaan'
                    },
                    {
                        name: 'Tambah Permintaan Pembelian',
                        link: '#'
                    }
                ],
                headers: {
                    rencana: 'PP',
                    tanggal: '2023-01-01',
                    sisa: 1000000,
                },
                acceptedParts: [
                    {
                        nama_barang: 'Besi',
                        no_perkiraan: '123',
                        supplier: 'PT. ABC',
                        jumlah: 100,
                        estimasi_harga: 100000,
                        pembelian_via: 'Offline',
                        link: 'https://www.google.com'
                    },
                    {
                        nama_barang: 'Besi',
                        no_perkiraan: '123',
                        supplier: 'PT. ABC',
                        jumlah: 100,
                        estimasi_harga: 100000,
                        pembelian_via: 'Online',
                        link: 'https://www.google.com'
                    }  
                ],
                notAcceptedParts: [
                    {
                        merek: 'Besi',
                        no_perkiraan: '123',
                        supplier: 'PT. ABC',
                        jumlah: 100,
                        estimasi_harga: 100000,
                        pembelian_via: 'Offline',
                        link: 'https://www.google.com'
                    },
                    {
                        merek: 'Besi',
                        no_perkiraan: '123',
                        supplier: 'PT. ABC',
                        jumlah: 100,
                        estimasi_harga: 100000,
                        pembelian_via: 'Online',
                        link: 'https://www.google.com'
                    }  
                ]
            }
        },
        methods: {
            trashAccepted(index) {
                this.acceptedParts.splice(index, 1)
            },
            addAccepted() {
                this.acceptedParts.push({
                    nama_barang: 'Besi',
                    no_perkiraan: '123',
                    supplier: 'PT. ABC',
                    jumlah: 100,
                    estimasi_harga: 100000,
                    pembelian_via: 'Online',
                    link: 'https://www.google.com'
                })
            },
            trashNotAccepted(index) {
                this.notAcceptedParts.splice(index, 1)
            },
            addNotAccepted() {
                this.notAcceptedParts.push({
                    merek: 'Besi',
                    no_perkiraan: '123',
                    supplier: 'PT. ABC',
                    jumlah: 100,
                    estimasi_harga: 100000,
                    pembelian_via: 'Online',
                    link: 'https://www.google.com'
                })
            },
            back() {
                this.$router.push('/pembelian/permintaan')
            },
            save() {
                const isFailed = (error) => {
                    this.$swal('Gagal!', error, 'error')
                }

                const isSuccess = () => {
                    this.$swal('Berhasil!', 'Permintaan Pembelian berhasil disimpan', 'success').then(() => {
                        this.$router.push('/pembelian/permintaan')
                    })
                }

                const isSendData = async () => {
                    // try {
                    //     const { data } = await this.$http.post('/pembelian/permintaan/dalamproses/tambah', {
                    //         headers: this.headers,
                    //         acceptedParts: this.acceptedParts,
                    //         notAcceptedParts: this.notAcceptedParts
                    //     }).then((response) => {
                    //         isSuccess()
                    //     })
                    // } catch (error) {
                    //     isFailed(error.response.data.message)
                    // }
                    isSuccess()
                }

                this.$swal({
                    title: 'Apakah anda yakin?',
                    text: 'Permintaan Pembelian akan disimpan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    result.value ? isSendData() : ''
                })
            }
        },
        computed: {
            getTwoWeeksDateBefore() {
                return moment(this.headers.tanggal).subtract(2, 'weeks').format('YYYY-MM-DD')
            }
        }
    }

</script>
<template>
    <div>
        <header-first :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <header-second :headers="headers"/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Detail Permintaan</h4>
                        <div class="card-text">
                            <div class="form-group row">
                                <label for="" class="col-5 text-right">Tanggal Dibutuhkan</label>
                                <div class="col-3"><input type="date" class="form-control" :min="getTwoWeeksDateBefore" :max="headers.tanggal"></div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-5 text-right">Kebutuhan Barang</label>
                                <div class="col-5">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio1" value="umum">
                                        <label class="form-check-label" for="inlineRadio1">Umum</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio2" value="part">
                                        <label class="form-check-label" for="inlineRadio2">Part Produk</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-5 text-right">Tujuan</label>
                                <div class="col-4">
                                    <textarea name="" id="" cols="2" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Table 
                :accepted="acceptedParts" 
                :notAccepted="notAcceptedParts" 
                @trashAccepted="trashAccepted" 
                @addAccepted="addAccepted"
                @trashNotAccepted="trashNotAccepted"
                @addNotAccepted="addNotAccepted"
                />
            </div>
            <div class="card-footer">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-danger" @click="back">
                            Batal
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <button class="btn btn-primary" @click="save">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
