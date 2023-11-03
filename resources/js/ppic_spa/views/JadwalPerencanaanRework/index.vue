<script>
import moment from 'moment'
import pagination from '../../components/pagination.vue'
import TableKalender from '../../components/TableKalender.vue'
import modalRework from '../modalRework/index.vue'
import axios from 'axios'
export default {
    components: {
        pagination,
        TableKalender,
        modalRework
    },
    data() {
        return {
            search: '',
            dataTable: [],
            renderPaginate: [],
            monthYears: moment().add(1, 'month').format('MMMM YYYY'),
            showModal: false,
            dataProduk: null
        }
    },
    methods: {
        updateFilteredDalamProses(filteredDalamProses) {
            this.renderPaginate = filteredDalamProses
        },
        edit(data) {
            this.dataProduk = JSON.parse(JSON.stringify(data))
            this.showModal = true
        },
        hapus(id) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00d1b2',
                cancelButtonColor: '#ff3860',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.dataTable = this.dataTable.filter((data) => data.id !== id)
                    this.$swal(
                        'Terhapus!',
                        'Data berhasil dihapus.',
                        'success'
                    )
                }
            })
        },
        async simpan(data) {
            const success = () => {
                this.$swal('Berhasil!', 'Data berhasil disimpan!', 'success')
                this.showModal = false
                this.getData()
            }

            const error = () => {
                this.$swal('Gagal!', 'Data gagal disimpan!', 'error')
            }

            try {
                await axios.post('/api/ppic/jadwal_rework/perencanaan', data).then(success).catch(error)
            } catch (error) {
                console.log(error)
            }

        },
        async simpanedit(data) {
            const success = () => {
                this.$swal('Berhasil!', 'Data berhasil disimpan!', 'success')
                this.showModal = false
                this.getData()
            }

            const error = () => {
                this.$swal('Gagal!', 'Data gagal disimpan!', 'error')
            }

            try {
                await axios.put(`/api/ppic/jadwal_rework/perencanaan/`, data).then(success).catch(error)
            } catch (error) {
                console.log(error)
            }
        },
        tambah() {
            this.dataProduk = {
                jumlah: '',
                tanggal_mulai: '',
                tanggal_selesai: '',
            }
            this.showModal = true
        },
        hapus(data) {
            const success = () => {
                this.$swal('Berhasil!', 'Data berhasil dihapus!', 'success')
                this.showModal = false
                this.getData()
            }

            const error = () => {
                this.$swal('Gagal!', 'Data gagal dihapus!', 'error')
            }
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00d1b2',
                cancelButtonColor: '#ff3860',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    try {
                        axios.post('/api/ppic/jadwal_rework/perencanaan/delete', data).then(success).catch(error)
                    } catch (error) {
                        console.log(error)
                    }
                }
            })
        },
        async getData() {
            try {
                this.$store.commit('setIsLoading', true)
                const { data } = await axios.get('/api/ppic/jadwal_rework/perencanaan')
                this.dataTable = data.map((data) => {
                    return {
                        no_urut: `PRD-${data.urutan}`,
                        ...data,
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.commit('setIsLoading', false)
            }
        }
    },
    computed: {
        filteredData() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div v-if="!this.$store.state.isLoading">
        <modalRework v-if="showModal" :dataProduk="dataProduk" :showModal="showModal" @closeModal="showModal = false"
            @tambah="simpan" @edit="simpanedit" @hapus="hapus(data)" :maxDate="monthYears" />
        <h1 class="title">Perencanaan Jadwal Perakitan Rework</h1>
        <div class="notification is-primary">
            Penyusunan jadwal perakitan rework
        </div>
        <div class="card">
            <div class="card-content">
                <h1 class="subtitle">
                    {{ monthYears }}
                </h1>

                <div class="columns">
                    <div class="column">
                        <button class="button is-success" @click="tambah">
                            Tambah &nbsp;<i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="column is-flex-shrink-1">
                        <input type="text" class="input" v-model="search" placeholder="Cari">
                    </div>
                </div>

                <TableKalender :dataTable="renderPaginate" :monthYears="monthYears" @edit="edit" @hapus="hapus" />

                <pagination :filteredDalamProses="filteredData" @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>