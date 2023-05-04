<script>
export default {
    data() {
        return {
            headers: {
                no_permintaan: 'PRD-0001',
                divisi: 'Divisi Produksi',
                tgl_permintaan: '2020-01-01',
                tgl_dibutuhkan: '2020-01-01',
            }
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="row row-whith-wide-cards">
            <div class="col d-flex align-items-stretch">
                <div class="card card-danger">
                    <div class="card-body">
                        <h4 class="card-title">No Permintaan</h4>
                        <p class="card-text text-bold">
                            {{ headers.no_permintaan }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div class="card card-success">
                    <div class="card-body">
                        <h4 class="card-title">Divisi</h4>
                        <p class="card-text text-bold">
                            {{ headers.divisi }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">Tgl Permintaan</h4>
                        <p class="card-text text-bold">
                            {{ formatTanggal(headers.tgl_permintaan) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div class="card card-secondary">
                    <div class="card-body">
                        <h4 class="card-title">Tgl Dibutuhkan</h4>
                        <p class="card-text text-bold">
                            {{ formatTanggal(headers.tgl_dibutuhkan) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</template>