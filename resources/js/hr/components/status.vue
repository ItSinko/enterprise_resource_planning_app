<script>
export default {
    props: ["status"],
    methods: {
        statusColor(status) {
            if (typeof status == "string") {
                status = status.toLowerCase();
            }
            switch (status) {
                case "draft":
                    return "badge-secondary";
                    break;
                case "menunggu_persetujuan_kepala_bagian":
                    return "badge-info";
                    break;
                case "menunggu_persetujuan_hrga":
                    return "badge-info";
                    break;
                case "aktif":
                    return "badge-primary";
                    break;
                case "selesai":
                    return "badge-success";
                    break;
                case "batal":
                    return "badge-warning";
                    break;
                case "ditolak":
                    return "badge-danger";
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
                case "draft":
                    return "Draft";
                    break;
                case "menunggu_persetujuan_kepala_bagian":
                    return "Menunggu Persetujuan Kepala Bagian";
                    break;
                case "menunggu_persetujuan_hrga":
                    return "Menunggu Persetujuan HRGA";
                    break;
                case "aktif":
                    return "Aktif";
                    break;
                case "selesai":
                    return "Selesai";
                    break;
                case "batal":
                    return "Batal";
                    break;
                case "ditolak":
                    return "Ditolak";
                    break;
                default:
                    if (typeof status == "string") {
                        return status.replace(/_/g, " ")
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
