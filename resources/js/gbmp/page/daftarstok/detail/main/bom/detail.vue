<script>
import status from '../../../../../components/status.vue'
export default {
    components: {
        status
    },
    data() {
        return {
            formHeader: {
                nama_produk: 'FOX',
                nama_bom: 'FOX',
                kode_bom: 'FOX',
                tahun : '2020',
                status: 'aktif',
            },
            dataTable: [
                {
                    id: 1,
                    kode_part: 'SONO',
                    nama_part: 'SONO',
                    jumlah: 100,
                }
            ]
        }
    },
}
</script>
<template>
    <div class="modal fade modalDetailBOM" id="modelId" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail BOM</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                            <div class="card">
            <div class="card-body">
                
                <div class="row">
            <div class="col-6">
                <div class="card card-danger">
                    <div class="card-body">
                        <h4 class="card-title">Title</h4>
                        <p class="card-text">Text</p>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-success">
                    <div class="card-body">
                        <h4 class="card-title">Title</h4>
                        <p class="card-text">Text</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">Title</h4>
                        <p class="card-text">Text</p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                                <div class="card card-danger">
                    <div class="card-body">
                        <h4 class="card-title">Title</h4>
                        <p class="card-text">Text</p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                                <div class="card card-secondary">
                    <div class="card-body">
                        <h4 class="card-title">Title</h4>
                        <p class="card-text">Text</p>
                    </div>
                </div>
            </div>
        </div>

            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-bold pb-2">Part Bill Of Material</h4>
                <div class="card-text">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Part</th>
                                <th>Nama Part</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
                </div>

            </div>
        </div>
    </div>
</template>