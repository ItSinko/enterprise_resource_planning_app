<script>
export default {
    props: {
        headers: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            dataTable: [
                {
                    id: 1,
                    seri: '123456789',
                    hasil: true,
                },
                {
                    id: 2,
                    seri: '987654321',
                    hasil: false,
                }
            ]
        }
    },
    methods: {
        hasilIcon(hasil) {
            if (hasil) {
                return 'fas fa-check-circle text-success'
            } else {
                return 'fas fa-times-circle text-danger'
            }
        }
    },
}
</script>
<template>
    <div class="modal fade modalDetailSampling" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ headers }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <table class="table text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No Seri</th>
                                <th>Hasil Pengecekan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, index) in dataTable" :key="data.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ data.seri }}</td>
                                <td>
                                    <i :class="hasilIcon(data.hasil)"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>