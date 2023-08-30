<script>
export default {
    props: ["jenis", "link", "nama"],
    methods: {
        openDokumen(link) {
            window.open(link, "_blank");
        },
    },
};
</script>
<template>
    <div>
        <!-- image -->
        <img :src="link" v-if="jenis == 'foto'" width="100%" height="315" class="mt-4" />
        <!-- video -->
        <!-- <video :src="link" v-if="jenis == 'video'" width="100%" height="315" controls></video> -->
        <!-- video link -->
        <iframe
            :src="link"
            v-if="jenis == 'video'"
            width="100%"
            height="315"
            frameborder="0"
            allowfullscreen
        ></iframe>
        <!-- rekaman -->
        <audio controls v-if="jenis == 'rekaman'">
            <source :src="link" type="audio/mpeg" />
        </audio>
        <!-- dokumen -->
        <div class="card card-hover" v-if="jenis == 'dokumen'">
            <div class="card-body" @click="openDokumen(link)">
                <div class="card-text text-center">
                    <p class="text-secondary">{{ nama }}</p>
                    <div class="d-flex justify-content-center">
                        <i class="fa fa-download py-2" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.card-hover {
    /* Card styling */
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 20px;

    /* Apply the hover effect */
    cursor: pointer; /* Change the cursor to a pointer on hover */
    transition: background-color 0.3s ease; /* Smooth transition for background color change */
}

.card-hover:hover {
    background-color: #ddd; /* Change background color on hover */
}
</style>
