<script>
    import Table from './table.vue'
    import Detail from './detail'
    export default {
        components: {
            Table,
            Detail
        },
        data() {
            return {
                dataTables: [{
                        id: 1,
                        no_pp: 'PP-001',
                        jenis_barang: 'umum',
                        tanggal_diminta: '2021-01-01',
                        tanggal_dibutuhkan: '2021-01-01',
                        pp: 'upload',
                        status: 'draft',
                    },
                    {
                        id: 2,
                        no_pp: 'PP-002',
                        jenis_barang: 'part',
                        tanggal_diminta: '2021-01-01',
                        tanggal_dibutuhkan: '2021-01-01',
                        pp: 'lihat',
                        status: 'minta persetujuan',
                    },
                    {
                        id: 3,
                        no_pp: 'PP-003',
                        jenis_barang: 'umum',
                        tanggal_diminta: '2021-01-01',
                        tanggal_dibutuhkan: '2021-01-01',
                        pp: 'upload',
                        status: 'menunggu persetujuan',
                    },
                    {
                        id: 4,
                        no_pp: 'PP-004',
                        jenis_barang: 'part',
                        tanggal_diminta: '2021-01-01',
                        tanggal_dibutuhkan: '2021-01-01',
                        pp: 'upload',
                        status: 'proses pembelian',
                    },
                    {
                        id: 5,
                        no_pp: 'PP-005',
                        jenis_barang: 'umum',
                        tanggal_diminta: '2021-01-01',
                        tanggal_dibutuhkan: '2021-01-01',
                        pp: 'lihat',
                        status: 'batal',
                    },
                    {
                        id: 6,
                        no_pp: 'PP-006',
                        jenis_barang: 'part',
                        tanggal_diminta: '2021-01-01',
                        tanggal_dibutuhkan: '2021-01-01',
                        pp: 'upload',
                        status: 'ditolak pembelian',
                    }
                ],
                modalDetail: false,
            }
        },
        methods: {
            detail(id) {
                this.modalDetail = true
                setTimeout(() => {
                    $('.modalDetail').modal('show')
                }, 100);
            },
            closeDetail() {
                this.modalDetail = false
                $('.modalDetail').modal('hide')
            },
            hapus(id) {
                const deletePermintaan = async () => {
                    // try {
                    //     const { data } = await axios.delete(`/api/permintaan/${id}`)
                    //     this.$swal('Berhasil', data.message, 'success')
                    // } catch (error) {
                    //     console.log(error)
                    // }
                    this.$swal('Berhasil', 'Data berhasil dihapus', 'success')
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
                    // try {
                    //     const { data } = await axios.put(`/api/permintaan/${id}/batal`)
                    //     this.$swal('Berhasil', data.message, 'success')
                    // } catch (error) {
                    //     console.log(error)
                    // }
                    this.$swal('Berhasil', 'Data berhasil dibatalkan', 'success')
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
            }
        }
    }

</script>
<template>
    <div>
    <Detail v-if="modalDetail" @close="closeDetail"/>
    <Table
        :dataTables="dataTables" 
        @edit="edit" 
        @detail="detail" 
        @hapus="hapus" 
        @batal="batal" />
    </div>
</template>
