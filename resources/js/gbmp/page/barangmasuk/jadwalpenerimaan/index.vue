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
            }
        },
        methods: {
            moment(date) {
                return moment(date).lang("ID").format('DD MMMM YYYY')
            },
        },
        computed: {
            groupDateJadwals() {
                return this.jadwals.reduce((acc, jadwal) => {
                    const date = moment(jadwal.tanggal).format('YYYY-MM-DD')
                    if (!acc[date]) {
                        acc[date] = []
                    }
                    acc[date].push(jadwal)
                    return acc
                }, {})
            },
        }
    }

</script>
<template>
    <div class="card">
        <div class="card-body">
                <header-jadwal :month-years="monthYears"></header-jadwal>
                <Table :dataTable="groupDateJadwals" />
        </div>
    </div>
</template>

