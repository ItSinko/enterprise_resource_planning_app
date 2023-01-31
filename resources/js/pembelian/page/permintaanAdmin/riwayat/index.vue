<script>
    import Table from './table.vue'
    export default {
        components: {
            Table,
        },
        data() {
            return {
                riwayatPO: [{
                        no_po: 'PO-001',
                        divisi: 'IT',
                        supplier: 'PT. ABC',
                        tanggal_diminta: '2020-01-01',
                        tanggal_kedatangan: '2020-01-02',
                        ekspedisi: 'JNE',
                    },
                    {
                        no_po: 'PO-002',
                        divisi: 'IT',
                        supplier: 'PT. ABC',
                        tanggal_diminta: '2020-01-01',
                        tanggal_kedatangan: '2020-01-02',
                        ekspedisi: 'JNE',
                    },
                ],
            }
        },
    }

</script>
<template>
    <div>
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                                <span class="float-left filter">
                    <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="form-group">
                                    <label for="jenis_penjualan">Tanggal PO</label>
                                </div>
                                <div class="form-group"><label for="">Mulai Tanggal</label><input type="date" class="form-control"></div>
                                <div class="form-group"><label for="">Hingga Tanggal</label><input type="date" class="form-control"></div>
                            </div>
                        </div>
                    </form>
                </span>
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" placeholder="Search" class="form-control">
            </div>
        </div>
        <Table :dataTable="riwayatPO" />
    </div>
</template>
