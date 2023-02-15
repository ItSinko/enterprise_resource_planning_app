<script>
    import Status from '../../../components/status.vue'
    import Pagination from '../../../components/pagination.vue'
    export default {
        components: {
            Status,
            Pagination
        },
        data() {
            return {
                dataTables: [
                    {
                        no_permintaan: 'PMB-0001',
                        no_penyerahan: 'PSH-0001',
                        tujuan: 'Perakitan',
                        tanggal_permintaan: '2020-01-01',
                        tanggal_penyerahan: '2020-01-01',
                        status: 'menunggu penerimaan',
                    },
                    {
                        no_permintaan: 'PMB-0002',
                        no_penyerahan: 'PSH-0002',
                        tujuan: 'Perbaikan',
                        tanggal_permintaan: '2020-01-01',
                        tanggal_penyerahan: '2020-01-01',
                        status: 'kirim barang',
                    }
                ],
                renderPaginate: [],
                search: '',
            }
        },
        methods: {
            updatePenyerahanRender(data) {
                this.renderPaginate = data
            },
            detail(id){
                
            }
        },
        computed: {
            filteredPenyerahanBarang() {
                const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "-";
                return this.dataTables.filter((penyerahanBarang) => {
                    return Object.keys(penyerahanBarang).some((key) => {
                        return String(dataIsNotNull(penyerahanBarang[key])).toLowerCase().includes(this.search
                            .toLowerCase())
                    })
                })
            },
        }
    }

</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input v-model="search" type="text" class="form-control" placeholder="Cari" />
            </div>
        </div>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Permintaan</th>
                    <th>No Penyerahan</th>
                    <th>Tujuan</th>
                    <th>Tanggal Permintaan</th>
                    <th>Tanggal Penyerahan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in renderPaginate" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_permintaan }}</td>
                    <td>{{ data.no_penyerahan }}</td>
                    <td><status :status="data.tujuan" /></td>
                    <td>{{ formatTanggal(data.tanggal_permintaan) }}</td>
                    <td>{{ formatTanggal(data.tanggal_penyerahan) }}</td>
                    <td>
                        <button class="btn btn-info btn-sm" v-if="data.status === 'kirim barang'" >Kirim Barang</button>
                        <Status :status="data.status" v-else/>
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
                            @click="detail(data.id)"
                            class="dropdown-item" 
                            type="button">
                                <i class="fas fa-eye"></i>
                                Detail
                            </button>

                            <button
                             class="dropdown-item" 
                             type="button">
                                <i class="fas fa-pencil-alt"></i>
                                Ubah
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <pagination 
            :filteredDalamProses="filteredPenyerahanBarang" 
            @updateFilteredDalamProses="updatePenyerahanRender"/>
    </div>
</template>
