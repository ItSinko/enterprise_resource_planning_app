<script>
import Header from '../../../../components/header.vue';
import pengajuan from './pengajuan.vue';
import batal from './batal.vue';
import riwayat from './riwayat.vue';
export default {
    components: {
        Header,
        pengajuan,
        batal,
        riwayat,
    },
}
</script>
<template>
    <div>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                            Pengajuan
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-riwayat-tab" data-toggle="pill" data-target="#pills-riwayat"
                            type="button" role="tab" aria-controls="pills-riwayat" aria-selected="false">
                            Riwayat
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                            Batal
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <pengajuan />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <batal />
                    </div>
                    <div class="tab-pane fade" id="pills-riwayat" role="tabpanel" aria-labelledby="pills-riwayat-tab">
                        <riwayat />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>