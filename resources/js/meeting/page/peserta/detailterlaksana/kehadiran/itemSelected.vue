<script>
import DataTable from "../../../../components/DataTable.vue";
import kehadiran from "../../../../components/kehadiran.vue";
export default {
    components: {
        kehadiran,
        DataTable,
    },
    data() {
        return {
            search: "",
            headers: [
                { text: 'Nama Peserta', value: 'nama' },
                { text: 'Divisi', value: 'divisi' },
                { text: 'Kehadiran', value: 'kehadiran', sortable: false },
                { text: 'Alasan', value: 'alasan' },
                { text: 'Dokumen Pendukung Ketidakhadiran', value: 'dokumen_pendukung', sortable: false },
            ]
        };
    },
    props: ["meeting"],
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                    </div>
                </div>
            </div>
            <DataTable :headers="headers" :items="meeting" :search="search">
                <template #item.kehadiran="{ item }">
                    <div>
                        <kehadiran :kehadiran="item.kehadiran"></kehadiran>
                    </div>
                </template>
                <template #item.dokumen_pendukung="{ item }">
                    <div>
                        <div v-if="item.dokumen_pendukung">
                            <a v-for="(dokumen, idx) in item.dokumen_pendukung" :key="idx" :href="dokumen.link">
                                {{ dokumen.nama }}<span v-if="idx != item.dokumen_pendukung.length - 1">, </span>
                            </a>
                        </div>
                        <span v-else>-</span>
                    </div>
                </template>
            </DataTable>
        </div>
    </div>
</template>
