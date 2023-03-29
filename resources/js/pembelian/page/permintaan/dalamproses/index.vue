<script>
    import Table from './table.vue'
    import Detail from './detail'
    import Upload from './upload'
    import axios from 'axios'
    export default {
        components: {
            Table,
            Detail,
            Upload
        },
        data() {
            return {
                dataTables: [],
                modalDetail: false,
                detailData: null,

                modalUpload: false,
                detailUpload: null,
            }
        },
        mounted() {
            this.getPP()
        },
        methods: {
            async getPP() {
                const { data } = await axios.get('/api/pembelian/pp/')
                this.dataTables = data.data
            },  
            detail(id, jenis) {
                this.modalDetail = true
                this.detailData = {
                    id,
                    jenis
                }
                this.$nextTick(() => {
                    $('.modalDetail').modal('show')
                })
            },
            closeDetail() {
                this.modalDetail = false
                $('.modalDetail').modal('hide')
            },
            hapus(id, jenispp) {
                const jenis = jenispp.toLowerCase()
                const deletePermintaan = async () => {
                    try {
                        if(jenis == 'umum'){
                            await axios.delete(`/api/pembelian/pp/${id}/umum`)
                        } else {
                            await axios.delete(`/api/pembelian/pp/${id}/part`)
                        }
                        this.$swal('Berhasil', 'Data berhasil dihapus', 'success')
                        this.getPP()
                    } catch (error) {
                        console.log(error)
                        this.$swal('Gagal', 'Data gagal dihapus', 'error')
                    }
                }

                this.$swal({
                    title: 'Hapus Permintaaan',
                    text: "Anda tidak akan dapat melihat dan mengubah permintaan ini lagi!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    result.value ? deletePermintaan() : ''
                })
            },
            edit(id){

            },
            batal(id) {
                const batalPermintaan = async () => {
                    try {
                        const { data } = await axios.put(`/api/pembelian/pp/${id}/status`, {
                            status: 'tolak'
                        })
                        this.$swal('Berhasil', data.message, 'success')
                        this.getPP()
                    } catch (error) {
                        console.log(error)
                    }
                }

                this.$swal({
                    title: 'Batalkan Permintaaan',
                    text: "Apakah anda yakin ingin membatalkan permintaan pembelian?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    result.value ? batalPermintaan() : ''
                })
            },
            upload(id, no_pp){
                console.log(id, no_pp)
                this.modalUpload = true
                this.detailUpload = {
                    id,
                    no_pp
                }
                this.$nextTick(() => {
                    $('.modalUpload').modal('show')
                })
            }
        }
    }

</script>
<template>
    <div>
    <Detail :detail="detailData" v-if="modalDetail" @close="closeDetail"/>
    <Upload 
    v-if="modalUpload"
    :dataUpload="detailUpload"
    />
    <Table
        :dataTables="dataTables" 
        @edit="edit" 
        @detail="detail" 
        @hapus="hapus" 
        @batal="batal" 
        @upload="upload"
        />
    </div>
</template>
