<script>
import dokumen from "../../../components/dokumen.vue";
export default {
    props: ["meeting"],
    components: {
        dokumen,
    },
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="card-body">
                <div class="margin" v-for="meet in meeting">
                    <h4 class="my-4 text-capitalize">{{ meet.jenis }}</h4>
                    <div class="row">
                        <div v-for="dokumen in meet.dokumen" class="col-3">
                            <dokumen
                                :jenis="meet.jenis"
                                :link="dokumen.link"
                                :nama="dokumen.nama"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
