<script>
export default {
    props: ["jenis", "link", "nama"]
}
</script>
<template>
    <div>
        <!-- image -->
        <img :src="link" v-if="jenis == 'foto'" width="100%" height="315">
        <!-- video -->
        <!-- <video :src="link" v-if="jenis == 'video'" width="100%" height="315" controls></video> -->
        <!-- video link -->
        <iframe :src="link" v-if="jenis == 'video'" width="100%" height="315" frameborder="0" allowfullscreen></iframe>
        <!-- rekaman -->
        <audio controls v-if="jenis == 'rekaman'">
            <source :src="link" type="audio/mpeg">
        </audio>
        <!-- dokumen -->
        <div v-if="jenis == 'dokumen'">
            <a :href="link" target="_blank">
                <button class="btn btn-outline-primary btn-sm mr-2 mb-2">
                    {{ nama }}
                </button>
            </a>
        </div>
    </div>
</template>