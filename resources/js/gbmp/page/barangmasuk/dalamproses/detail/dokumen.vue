<script>
import DownloadFile from '../../../../components/downloadFile.vue'
export default {
    props: {
        dokumens: {
            type: Array,
            default: () => []
        }
    },
    components: {
        DownloadFile
    },
    computed: {
        groupingDokumen() {
            let dokumens = this.dokumens;
            let grouping = dokumens.reduce((r, a) => {
                r[a.jenis_dokumen] = [...r[a.jenis_dokumen] || [], a];
                return r;
            }, {});
            return grouping;
        }
    }
}
</script>
<template>
<div>
    <div class="card" v-for="(group, name) in groupingDokumen" :key="name">
    <img class="card-img-top" src="holder.js/100x180/" alt="">
    <div class="card-body">
        <h4 class="card-title text-bold">{{ name }}</h4>
        <div class="card-text pt-3">
            <DownloadFile :filename="group" :url="'/'" />
        </div>
    </div>
</div>
</div>
</template>