<script>
export default {
    props: ["status"],
    methods: {
        statusColor(status) {
            if (typeof status == "string") {
                status = status.toLowerCase();
            }
            switch (status) {
                case "terlaksana":
                    return "badge-success";
                    break;
                case "belum_terlaksana":
                    return "badge-danger";
                    break;
                case "menyusun_hasil_meeting":
                    return "badge-warning";
                    break;
                case "batal":
                    return "badge-danger";
                    break;
                case "hadir":
                    return "badge-success";
                    break;
                case "tidak_hadir":
                    return "badge-danger";
                    break;
                case "belum_mengisi_daftar_hadir":
                    return "Belum Mengisi Daftar Hadir";
                    break;
                default:
                    return "badge-warning";
            }
        },
        statusText(status) {
            if (typeof status == "string") {
                status = status.toLowerCase();
            }
            switch (status) {
                case "terlaksana":
                    return "Terlaksana";
                    break;
                case "belum_terlaksana":
                    return "Belum Terlaksana";
                    break;
                case "menyusun_hasil_meeting":
                    return "Menyusun Hasil Meeting";
                    break;
                case "batal":
                    return "Batal";
                    break;
                case "hadir":
                    return "Hadir";
                    break;
                case "tidak_hadir":
                    return "Tidak Hadir";
                    break;
                case "belum_mengisi_daftar_hadir":
                    return "Belum Mengisi Daftar Hadir";
                    break;
                default:
                    if (typeof status == "string") {
                        return status.replace(/_/g, " ");
                    }
                    return status;
            }
        },
    },
};
</script>
<template>
    <div>
        <span :class="'badge ' + statusColor(status)">{{
            statusText(status)
        }}</span>
    </div>
</template>
