<script>
    import Status from '../../../components/status'
    import moment from 'moment'
    import mix from '../../../mix'
    export default {
        mixins: [mix],
        props: {
            filteredDatatables: {
                type: Array,
                required: true
            }
        },
        components: {
            Status
        },
        methods: {
            formatDateIndonesia(date) {
                return moment(date).lang('id').format('LL')
            },
            detail(id) {
                this.$router.push({
                    name: 'purchaseorderDetal',
                    params: {
                        id: id,
                        open: 'dalamproses'
                    }
                })
            },
            edit(id) {
                this.$router.push({
                    name: 'purchaseorderEdit',
                    params: {
                        id: id,
                        open: 'dalamproses'
                    }
                })
            },
            batal(id) {
                this.$swal({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan data ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, batalkan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // this.$axios.delete(`/api/purchaseorder/${id}`)
                        //     .then(res => {
                        //         this.$swal('Dibatalkan!', 'Data berhasil dibatalkan.', 'success')
                        //         this.$emit('refresh')
                        //     })
                        //     .catch(err => {
                        //         this.$swal('Gagal!', 'Data gagal dibatalkan.', 'error')
                        //     })
                        this.$swal('Dibatalkan!', 'Data berhasil dibatalkan.', 'success')
                        this.$emit('refresh')
                    }
                })
            },
            hapus(id) {
                                this.$swal({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan data ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // this.$axios.delete(`/api/purchaseorder/${id}`)
                        //     .then(res => {
                        //         this.$swal('Dihapus!', 'Data berhasil dihapus.', 'success')
                        //         this.$emit('refresh')
                        //     })
                        //     .catch(err => {
                        //         this.$swal('Gagal!', 'Data gagal dihapus.', 'error')
                        //     })
                        this.$swal('Dihapus!', 'Data berhasil dihapus.', 'success')
                        this.$emit('refresh')
                    }
                })
            }
        },
    }

</script>
<template>
    <div>
        <table class="table dalamProsesTable text-center">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No PO</th>
                    <th>Divisi</th>
                    <th>Supplier</th>
                    <th>Tanggal Dimitra</th>
                    <th>Estimasi Kedatangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(purchaseorder, idx) in renderPaginate" :key="purchaseorder.id">
                    <td>{{ idx + 1 }}</td>
                    <td>{{purchaseorder.no_po}}</td>
                    <td>{{purchaseorder.divisi}}</td>
                    <td>{{purchaseorder.supplier}}</td>
                    <td>{{formatDateIndonesia(purchaseorder.tanggal)}}</td>
                    <td>{{formatDateIndonesia(purchaseorder.estimasi)}}</td>
                    <td>
                        <div class="progress" v-if="purchaseorder.status === 'selesai'">
                            <div class="progress-bar bg-success" role="progressbar" :style="{width: purchaseorder.progress + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{ purchaseorder.progress }}%</div>
                        </div>
                        <status :status="purchaseorder.status" />
                    </td>
                    <td>
                                                <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true"
                            aria-expanded="true">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="bottom-start"
                            style="
                                position: absolute;
                                transform: translate3d(-117px, 30px, 0px);
                                top: 0px;
                                left: 0px;
                                will-change: transform;
                            ">
                            <button 
                            @click="detail(purchaseorder.id)" class="dropdown-item" type="button">
                                <i class="fas fa-eye"></i>
                                Detail
                            </button>

                            <button 
                            @click="edit(purchaseorder.id)" class="dropdown-item" type="button">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>

                            <button 
                            @click="batal(purchaseorder.id)" class="dropdown-item" type="button">
                                <i class="fas fa-times"></i>
                                Batal
                            </button>

                            <button 
                            @click="hapus(purchaseorder.id)" class="dropdown-item" type="button">
                                <i class="fas fa-trash"></i>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="renderPaginate.length == 0">
                    <td colspan="7" class="text-center">Tidak Ditemukan</td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex flex-row-reverse bd-highlight">
            <nav aria-label="...">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                    </li>
                    <li class="page-item" :class="paginate == currentPage ? 'active' : ''" v-for="paginate in pages"
                        :key="paginate">
                        <a class="page-link" @click="nowPage(paginate)">{{paginate}}</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" :disabled="currentPage == pages[pages.length - 1]"
                            @click="nextPage">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
