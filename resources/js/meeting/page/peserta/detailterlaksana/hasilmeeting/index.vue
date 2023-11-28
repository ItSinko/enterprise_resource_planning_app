<script>
import DataTable from '../../../../components/DataTable.vue';
export default {
    props: ["meeting", "status"],
    components: {
        DataTable,
    },
    data() {
        return {
            search: "",
            formhasilmeeting: {
                isi: "",
            },
            showModal: false,
            headers: [
                { text: "No", value: "no" },
                { text: "Hasil Rapat", value: "isi" },
            ]
        };
    },
    methods: {
        saveHasilMeeting() {
            this.showModal = false;
            if (this.formhasilmeeting?.idx) {
                this.meeting[this.formhasilmeeting.idx - 1] =
                    this.formhasilmeeting;
            } else {
                this.meeting.push(this.formhasilmeeting);
            }
            this.$nextTick(() => {
                $(".modalHasilMeeting").modal("hide");
            });
        },
        close() {
            this.showModal = false;
        },
    },
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <div class="mr-auto p-2"></div>
                <div class="p-2">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari..."
                        v-model="search"
                    />
                </div>
            </div>
           <DataTable :headers="headers" :items="meeting">
            <template #item.no="{item, index}">
                <div>
                    {{ index + 1 }}
                </div>
            </template>
            </DataTable>
        </div>
    </div>
</template>
