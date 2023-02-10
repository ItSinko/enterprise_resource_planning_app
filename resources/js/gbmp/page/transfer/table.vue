<script>
    import axios from 'axios'
    import status from '../../components/status.vue'
    export default {
        components: {
            status
        },
        props: {
            transfers: {
                type: Array,
                required: true,
                default: () => []
            }
        },
        methods: {

            divisiClass(status) {
                switch (status) {
                    case 'masuk':
                        return 'text-danger'
                    default:
                        return 'text-success'
                }
            },

            buttonColor(status, jenis) {
                if (status == 'Draft' && jenis == 'masuk') {
                    return 'btn-primary'
                } else if (status == 'Draft' && jenis == 'keluar') {
                    return 'btn-warning'
                } else {
                    return 'btn-primary'
                }
            },
            detail(id) {
                this.$emit('detail', id)
            },

            condition(status, jenis, id) {
                if (status == 'Draft' && jenis == 'masuk') {
                    return console.log(status)
                } else if (status == 'Draft' && jenis == 'keluar') {
                    this.$swal({
                        title: 'Terima Barang',
                        text: "Anda yakin ingin menerima barang dengan benar?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const kirim = {
                                id: id,
                                status: 'post'
                            }

                            const success = () => {
                                this.$swal('Berhasil!', 'Barang berhasil diterima.', 'success')
                            }

                            const error = () => {
                                this.$swal('Gagal!', 'Barang gagal diterima.', 'error')
                            }

                            const {
                                data
                            } = axios.post('/api/gbmp/store', kirim)
                                .then(success)
                                .catch(error)
                            this.$nextTick(() => {
                                this.$emit('refresh')
                            })
                        }
                    })
                } else {
                    return console.log(status)
                }
            },

            keterangan(status, jenis) {
                if (status == 'Draft' && jenis == 'masuk') {
                    return 'Transfer Barang'
                } else if (status == 'Draft' && jenis == 'keluar') {
                    return 'Terima Barang'
                } else {
                    return 'detail'
                }
            }
        },
    }

</script>
<template>
    <div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Transaksi</th>
                    <th>Divisi</th>
                    <th>Tanggal Transfer</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="transfers.length > 0">
                <tr v-for="(trf, idx) in transfers" :key="idx">
                    <td>{{ idx+1 }}</td>
                    <td>{{ trf.no_transaksi }}</td>
                    <td :class="divisiClass(trf.jenis)">{{ trf.divisi }}</td>
                    <td>{{ formatTanggal(trf.tanggal_transfer) }}</td>
                    <td>
                        {{ trf.ket }}
                    </td>
                    <td>
                        <status :status="trf.status" />
                    </td>
                    <td>
                        <button class="btn btn-sm" :class="buttonColor(trf.status, trf.jenis)"
                            @click="condition(trf.status, trf.jenis, trf.id)"
                            v-show="keterangan(trf.status, trf.jenis) != 'detail'">{{ keterangan(trf.status, trf.jenis) }}</button>
                        <button class="btn btn-sm btn-outline-info" @click="detail(trf.id)">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="7">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
