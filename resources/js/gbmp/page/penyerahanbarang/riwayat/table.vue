<script>
    import status from '../../../components/status.vue'
    import detail from '../dalamproses/detail'
    export default {
        components: {
            status,
            detail
        },
        data() {
            return {
                modal: false,
            }
        },
        props: {
            renderPaginate: {
                type: Array,
                default: () => []
            },
        },
        methods: {
            detail(id) {
                this.modal = true
                this.$nextTick(() => {
                    $('.modalDetail').modal('show')
                })
            },
            closeDetail() {
                this.modal = false
                $('.modalDetail').modal('hide')

            },
        },
    }

</script>
<template>
    <div>
        <detail v-if="modal" @close="closeDetail" />
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Permintaan</th>
                    <th>No Penyerahan</th>
                    <th>Tujuan</th>
                    <th>Tanggal Permintaan</th>
                    <th>Tanggal Penyerahan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in renderPaginate" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.no_permintaan }}</td>
                    <td>{{ item.no_penyerahan }}</td>
                    <td>
                        <status :status="item.tujuan" />
                    </td>
                    <td>{{ formatTanggal(item.tanggal_permintaan) }}</td>
                    <td>{{ formatTanggal(item.tanggal_penyerahan) }}</td>
                    <td>
                        <i class="fas fa-eye text-info" @click="detail(item.id)"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
