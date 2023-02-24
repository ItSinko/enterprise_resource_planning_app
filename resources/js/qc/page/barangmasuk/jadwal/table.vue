<script>
    import moment from 'moment'
    export default {
        props: {
            dataTable: {
                type: Object,
                required: true,
                default: () => ({})
            }
        },
        methods: {
            momentfullDay(date) {
                return moment(date).lang("ID").format('dddd')
            },
            detail(key) {
                this.$router.push({
                    name: 'barangmasukdalamproses',
                    params: {
                        id: key,
                        urlBefore: 'jadwalpenerimaan'
                    }
                })
            }
        },
    }

</script>
<template>
    <div>
        <div class="card" v-for="(tables, idx) in dataTable" :key="idx">
            <div class="card-body bg-secondary">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <h4 class="text-bold">{{ momentfullDay(idx) }}</h4>
                    </div>
                    <div class="p-2 bd-highlight">
                        <h4 class="text-bold">{{ formatTanggal(idx) }}</h4>
                    </div>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item" v-for="(table, key) in tables" :key="key">
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight text-bold">
                            {{ table.no_po }}
                            <button class="btn btn-sm btn-outline-info" @click="detail(key)">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                            <p class="text-muted">
                                                            <i class="fas fa-user-alt"></i>
                            {{ table.nama_supplier }}
                            </p>
                        </div>
                        <div class="p-2 bd-highlight" style="width: 20%">
                            <div v-if="table.progress == undefined">
                                <span class="badge badge-secondary">{{ table.status }}</span>
                                <p>
                                    <small class="text-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ table.keterangan }}
                                </small>
                                </p>
                            </div>
                            <div v-else>
                                <div class="progress">
                                    <div class="progress-bar" v-for="(progress, key) in table.progress" :key="key"
                                        :class="progress.class" role="progressbar"
                                        :style="{width: `${progress.width}%`}" :aria-valuenow="progress.width"
                                        aria-valuemin="0" aria-valuemax="100">{{ progress.width }}%</div>
                                </div>
                                <p class="text-center">
                                    <strong>{{ table.status }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
