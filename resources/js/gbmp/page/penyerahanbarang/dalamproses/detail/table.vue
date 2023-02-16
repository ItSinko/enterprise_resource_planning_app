<script>
import status from '../../../../components/status'
export default {
    components: {
        status
    },
    data() {
        return {
            formHeader: {
                    no_penyerahan: 'PS-0001',
                    tgl_penyerahan: '2020-01-01',
                    status: 'Menunggu Permintaan',
                },
            dataTable: [
                {
                    kode_part: 'P-0001',
                    nama_part: 'Part 1',
                    jumlah_diserahkan: 10,
                },
            ]
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-5">Detail Penyerahan</h4>
            <div class="card-text">
                <div class="row">
                    <div class="col-1">
                        <i class="fa fa-credit-card" aria-hidden="true"></i>
                    </div>
                    <div class="col-5">
                        <p>No Penyerahan</p>
                    </div>
                    <div class="col-6">
                        <p>
                            {{ formHeader.no_penyerahan }}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                    <div class="col-5">
                        <p>Tanggal Penyerahan</p>
                    </div>
                    <div class="col-6">
                        <p>
                            {{ formatTanggal(formHeader.tgl_penyerahan) }}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                        <i class="fa fa-info" aria-hidden="true"></i>
                    </div>
                    <div class="col-5">
                        <p>Status</p>
                    </div>
                    <div class="col-6">
                        <status :status="formHeader.status" />
                    </div>
                </div>

                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Jumlah Diserahkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(data, index) in dataTable" :key="index">
                            <td>{{ data.kode_part }}</td>
                            <td>{{ data.nama_part }}</td>
                            <td>{{ data.jumlah_diserahkan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>