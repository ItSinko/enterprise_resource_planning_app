<script>
export default {
    props: {
        header: {
            type: Object,
            default: () => {
                return {
                    no_lpb: '',
                    tgl_kedatangan: '',
                    no_surat_jalan: '',
                    supplier: '',
                    nama_bom: '',
                }
            }
        }
    }
}
</script>
<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="card card-danger">
                            <div class="card-body">
                                <h4 class="card-title">
                                    No LPB
                                </h4>
                                <p class="card-text text-bold">
                                    {{ header.no_lpb }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-success">
                            <div class="card-body">
                                <div class="card-title">
                                    Tanggal Kedatangan
                                </div>
                                <p class="card-text text-bold">
                                    {{ formatTanggal(header.tgl_kedatangan) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-danger">
                            <div class="card-body">
                                <div class="card-title">
                                    No Surat Jalan
                                </div>
                                <div class="card-text text-bold">
                                    {{ header.no_surat_jalan }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card card-primary">
                            <div class="card-body">
                                <div class="card-title">
                                    Supplier
                                </div>
                                <div class="card-text text-bold">
                                    {{ header.supplier }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-success">
                            <div class="card-body">
                                <div class="card-title">
                                    Nama BOM
                                </div>
                                <div class="card-text text-bold">
                                    {{ header.nama_bom }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>