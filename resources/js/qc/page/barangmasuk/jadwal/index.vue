<script>
import headerJadwal from './header.vue'
import moment from 'moment'
import Table from './table.vue'
    export default {
        components: {
            headerJadwal,
            Table
        },
        data() {
            return {
                monthYears: `${new Date().getMonth() + 1}-${new Date().getFullYear()}`,
                jadwals: [{
                    'tanggal' : '2022-11-01',
                    'no_po' : 'PO-0001',
                    'nama_supplier' : 'PT. ABC',
                    'status' : 'menunggu kedatangan barang',
                    'keterangan' : 'Estimasi kedatangan barang 10 November 2022',
                },{
                    'tanggal' : '2022-11-01',
                    'no_po' : 'PO-0002',
                    'nama_supplier' : 'PT. DEF',
                    'status' : 'progress pemeriksaan',
                    'progress': {
                        'total' : {
                            'class' : 'bg-success',
                            'width' : 50
                        },
                        'sudah' : {
                            'class' : 'bg-danger',
                            'width' : 25
                        },
                        'belum' : {
                            'class' : 'bg-warning',
                            'width' : 25
                        },
                    },
                }, 
                {
                    'tanggal' : '2022-11-02',
                    'no_po' : 'PO-0003',
                    'nama_supplier' : 'PT. GHI',
                    'status' : 'progress pemeriksaan',
                    'progress': {
                        'total' : {
                            'class' : 'bg-success',
                            'width' : 50
                        },
                        'sudah' : {
                            'class' : 'bg-danger',
                            'width' : 25
                        },
                        'belum' : {
                            'class' : 'bg-warning',
                            'width' : 25
                        },
                    },
                }
                ],
                statusSelected: [],
            }
        },
        methods: {
            moment(date) {
                return moment(date).lang("ID").format('DD MMMM YYYY')
            },
            clickFilterdalamProses(status){
                if (this.statusSelected.includes(status)) {
                    this.statusSelected = this.statusSelected.filter(item => item !== status)
                } else {
                    this.statusSelected.push(status)
                }
            }
        },
        computed: {
            filterJadwals() {
                if (this.statusSelected.length > 0) {
                    return this.jadwals.filter(jadwal => this.statusSelected.includes(jadwal.status))
                }
                return this.jadwals
            },
            groupDateJadwals() {
                return this.filterJadwals.reduce((acc, jadwal) => {
                    const date = moment(jadwal.tanggal).format('YYYY-MM-DD')
                    if (!acc[date]) {
                        acc[date] = []
                    }
                    acc[date].push(jadwal)
                    return acc
                }, {})
            },
            getUniqueStatus() {
                return this.jadwals.reduce((acc, jadwal) => {
                    if (!acc.includes(jadwal.status)) {
                        acc.push(jadwal.status)
                    }
                    return acc
                }, [])
            },
        }
    }

</script>
<template>
    <div class="card">
        <div class="card-body">
                <header-jadwal
                :statusData="getUniqueStatus" 
                :month-years="monthYears"
                @clickFilterdalamProses="clickFilterdalamProses"
                ></header-jadwal>
                <Table :dataTable="groupDateJadwals" />
        </div>
    </div>
</template>

