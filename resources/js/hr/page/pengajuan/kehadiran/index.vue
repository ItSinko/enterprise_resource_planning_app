<script>
import Header from '../../../components/header.vue';
import atasan from './atasan'
import hrd from './hrd'
export default {
    components: {
        Header,
        atasan,
        hrd,
    },
    data() {
        return {
            title: 'Pengajuan Kehadiran',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Pengajuan Kehadiran',
                    link: '#'
                },
            ],
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-atasan-tab" data-toggle="pill" data-target="#pills-atasan"
                    type="button" role="tab" aria-controls="pills-atasan" aria-selected="true">Atasan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-hrd-tab" data-toggle="pill" data-target="#pills-hrd" type="button" role="tab"
                    aria-controls="pills-hrd" aria-selected="false">HRD</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-atasan" role="tabpanel" aria-labelledby="pills-atasan-tab">
                <atasan />
            </div>
            <div class="tab-pane fade" id="pills-hrd" role="tabpanel" aria-labelledby="pills-hrd-tab">
                <hrd />
            </div>
        </div>
    </div>
</template>