<script>
import moment from 'moment'
export default {
    props: ['detail'],
    methods: {
        formatTgl(tgl) {
            return moment(tgl).format('DD-MM-YYYY')
        }
    }
}
</script>
<template>
    <div class="card">
        <div class="card-body">
        <strong><i class="fas fa-book mr-1 fa-fw"></i> No Retur</strong>
        <p class="text-muted"><i class="fas fa-unknown mr-1 fa-fw"></i> <span id="no_retur">{{ detail?.no_retur }}</span></p>
        <hr>
        <strong><i class="fas fa-calendar-alt mr-1 fa-fw"></i> Tanggal Retur</strong>
        <p class="text-muted"><i class="fas fa-unknown mr-1 fa-fw"></i> <span id="tgl_retur">{{ formatTgl(detail?.tgl_retur) }}</span></p>
        <hr>
        <strong><i class="fas fa-asterisk mr-1 fa-fw"></i> Jenis Retur</strong>
        <p class="text-muted"><i class="fas fa-unknown mr-1 fa-fw"></i> <span id="jenis"><span class="blue-text badge">{{ detail?.jenis }}</span></span></p>
        <hr class="pic">
        <strong class="pic"><i class="fas fa-user mr-1 fa-fw"></i> Penanggung Jawab</strong>
        <p class="pic"><i class="fas fa-unknown mr-1 fa-fw"></i> <span id="karyawan_id">{{ detail?.karyawan_id }}<span class="text-muted"> {{ detail?.telp_pic }}</span></span></p>
        <hr>
        <strong><i class="far fa-file-alt mr-1 fa-fw"></i> Alasan Retur</strong>
        <p class="text-muted"><i class="fas fa-unknown mr-1 fa-fw"></i> <span id="keterangan">{{ detail?.keterangan }}</span></p>
        </div>
    </div>
</template>