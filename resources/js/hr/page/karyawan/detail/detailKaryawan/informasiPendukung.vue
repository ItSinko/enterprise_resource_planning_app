<script>
export default {
    data() {
        return {
            dataPendukung: [
                {
                    nama: 'Lorem',
                    link: 'https://www.w3schools.com/html/mov_bbb.mp4',
                },
                {
                    nama: 'Lorem',
                    link: 'https://www.w3schools.com/html/mov_bbb.mp4',
                },
            ]
        }
    },
    methods: {
        openDokumen(link) {
            window.open(link, "_blank");
        },
    },
}
</script>
<template>
    <div>
        <div class="row">
            <div class="col" v-for="(data, index) in dataPendukung" :key="index">
                <div class="card card-hover">
                    <div class="card-body" @click="openDokumen(data.link)">
                        <div class="card-text text-center">
                            <p class="text-secondary">{{ data.nama }}</p>
                            <div class="d-flex justify-content-center">
                                <i class="fa fa-download py-2" aria-hidden="true"></i>
                            </div>
                        </div>
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
    cursor: pointer;
    /* Change the cursor to a pointer on hover */
    transition: background-color 0.3s ease;
    /* Smooth transition for background color change */
}

.card-hover:hover {
    background-color: #ddd;
    /* Change background color on hover */
}
</style>
