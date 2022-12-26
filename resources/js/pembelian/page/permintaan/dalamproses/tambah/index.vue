<script>
    import HeaderFirst from '../../../../components/header'
    import HeaderSecond from './header.vue'
    import Umum from './umum'
    import Part from './part'
    import moment from 'moment'
    import axios from 'axios'
    export default {
        components: {
            HeaderFirst,
            HeaderSecond,
            Umum,
            Part
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
                    tanggal: moment(new Date()).format('YYYY-MM-DD'),
                    sisa: null,
                },
                formPermintaan: {
                    tanggal: null,
                    kebutuhan: 'umum',
                    tujuan: null,
                },
                umum : {
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
            }
        },
        created() {
            this.getRencana()
        },
        methods: {
            async getRencana() {
                const getDivisi = localStorage.getItem('divisi')
                const { data } = await axios.get(`/api/pembelian/pp/nourut/${getDivisi}`).then(res => res.data)
                this.headers.rencana = data
            },
            trashAccepted(index) {
                this.umum.acceptedParts.splice(index, 1)
            },
            addAccepted() {
                this.umum.acceptedParts.push({
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
                this.umum.notAcceptedParts.splice(index, 1)
            },
            addNotAccepted() {
                this.umum.notAcceptedParts.push({
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
                    // const umum = {
                    //     terdaftar: this.$refs.umum.accepted,
                    //     tidak_terdaftar: this.$refs.umum.notAccepted
                    // }
                    // const part = this.$refs.part.formPart
                    const berhasil = (data) => {
                            this.$swal('Berhasil!', 'Permintaan Pembelian berhasil disimpan', 'success').then(() => {
                            this.$router.push('/pembelian/permintaan')
                            console.log("headers", this.headers.rencana)
                            console.log("detailminta", this.formPermintaan)
                            console.log("dataSend", data)
                        })
                    }
                    if (this.formPermintaan.kebutuhan === 'umum') {
                        const umum = {
                            terdaftar: this.$refs.umum.accepted,
                            tidak_terdaftar: this.$refs.umum.notAccepted
                        }
                        berhasil(umum)
                    }else{
                        const part = this.$refs.part.formPart
                        berhasil(part)
                    }   
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
                                <div class="col-3"><input type="date" v-model="formPermintaan.tanggal" class="form-control" :min="getTwoWeeksDateBefore"></div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-5 text-right">Kebutuhan Barang</label>
                                <div class="col-5">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio1" value="umum" v-model="formPermintaan.kebutuhan">
                                        <label class="form-check-label" for="inlineRadio1">Umum</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio2" value="part" v-model="formPermintaan.kebutuhan">
                                        <label class="form-check-label" for="inlineRadio2">Part Produk</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-5 text-right">Tujuan</label>
                                <div class="col-4">
                                    <textarea name="" id="" cols="2" rows="3" v-model="formPermintaan.tujuan" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <Umum
                ref="umum" 
                v-if="formPermintaan.kebutuhan == 'umum'"
                :accepted="umum.acceptedParts" 
                :notAccepted="umum.notAcceptedParts" 
                @trashAccepted="trashAccepted" 
                @addAccepted="addAccepted"
                @trashNotAccepted="trashNotAccepted"
                @addNotAccepted="addNotAccepted"
                />

                <Part
                ref="part"
                v-else
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
