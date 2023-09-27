<script>
import dokumentasi from "../../aksi/dokumentasi.vue";
export default {
    components: {
        dokumentasi,
    },
    data() {
        return {
            form: {
                foto: [],
                video: [],
                rekaman: [],
                lainnya: [],
            },
        };
    },
    methods: {
        save() {
            // detected form data is empty or not this.form.file = null
            const isFormEmpty = Object.values(this.form).some((field) => {
                if (Array.isArray(field)) {
                    // If the field is an array, check if any of its items have a null file
                    return field.some((item) => item.file === null);
                } else {
                    // If the field is not an array, check if it has a null file
                    return field.file === null;
                }
            });
            if (isFormEmpty) {
                this.$swal("Gagal", "Form tidak boleh kosong", "error");
                return;
            }
            this.closeModal();
        },
        closeModal() {
            this.$nextTick(() => {
                $(".modalDokumenPendukung").modal("hide");
            });
            this.$emit("closeModal");
        },
        resetForm() {
            this.form = {
                foto: [],
                video: [],
                rekaman: [],
                lainnya: [],
            };
        },
    },
    mounted() {
        this.resetForm();
    },
};
</script>
<template>
    <div
        class="modal fade modalDokumenPendukung"
        id="modelId"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modelTitleId"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dokumen Pendukung</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"
                            >Dokumentasi</label
                        >
                        <div class="col-sm-10">
                            <ul
                                class="nav nav-pills mb-3"
                                id="pills-tab"
                                role="tablist"
                            >
                                <li class="nav-item" role="presentation">
                                    <a
                                        class="nav-link active"
                                        id="pills-foto-tab"
                                        data-toggle="pill"
                                        data-target="#pills-foto"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-foto"
                                        aria-selected="true"
                                        >Foto</a
                                    >
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a
                                        class="nav-link"
                                        id="pills-video-tab"
                                        data-toggle="pill"
                                        data-target="#pills-video"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-video"
                                        aria-selected="false"
                                        >Video</a
                                    >
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a
                                        class="nav-link"
                                        id="pills-rekaman-tab"
                                        data-toggle="pill"
                                        data-target="#pills-rekaman"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-rekaman"
                                        aria-selected="false"
                                        >Rekaman Suara</a
                                    >
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a
                                        class="nav-link"
                                        id="pills-lainnya-tab"
                                        data-toggle="pill"
                                        data-target="#pills-lainnya"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-rekaman"
                                        aria-selected="false"
                                        >Dokumen Lainnya</a
                                    >
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div
                                    class="tab-pane fade show active"
                                    id="pills-foto"
                                    role="tabpanel"
                                    aria-labelledby="pills-foto-tab"
                                >
                                    <dokumentasi
                                        :file="form.foto"
                                        format="image"
                                    />
                                </div>
                                <div
                                    class="tab-pane fade"
                                    id="pills-video"
                                    role="tabpanel"
                                    aria-labelledby="pills-video-tab"
                                >
                                    <dokumentasi
                                        :file="form.video"
                                        format="video"
                                    />
                                </div>
                                <div
                                    class="tab-pane fade"
                                    id="pills-rekaman"
                                    role="tabpanel"
                                    aria-labelledby="pills-rekaman-tab"
                                >
                                    <dokumentasi
                                        :file="form.rekaman"
                                        format="audio"
                                    />
                                </div>
                                <div
                                    class="tab-pane fade"
                                    id="pills-lainnya"
                                    role="tabpanel"
                                    aria-labelledby="pills-lainnya-tab"
                                >
                                    <dokumentasi :file="form.lainnya" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="closeModal"
                    >
                        Keluar
                    </button>
                    <button type="button" class="btn btn-primary" @click="save">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
