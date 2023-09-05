<script>
import dokumen from '../../meeting/aksi/dokumentasi.vue';
export default {
    props: ["kehadiran"],
    components: {
        dokumen,
    },
    data() {
        return {
            dokumenPendukung: []
        }
    },
    methods: {
        closeModal() {
            this.$nextTick(() => {
                $(".modalKehadiran").modal("hide");
            });
            this.$emit("closeModal");
        },
        save(){
            if(this.kehadiran.kehadiran == 'tidak_hadir'){
                if(this.dokumenPendukung.length == 0){
                    this.$swal("Gagal", "Form tidak boleh kosong", "error");
                    return;
                }
            }
             // detected form data is empty or not this.form.file = null
                const isFormEmpty = Object.values(this.dokumenPendukung).some((field) => {
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
        }
    },
    watch: {
        kehadiran: {
            handler: function (val) {
                if (val.kehadiran == 'hadir') {
                    this.dokumenPendukung = [];
                }
            },
            deep: true,
        },
    }
};
</script>
<template>
    <div
        class="modal fade modalKehadiran"
        id="modelId"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modelTitleId"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kehadiran</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-4">Kehadiran</label>
                        <div class="col-8">
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    v-model="kehadiran.kehadiran"
                                    id="inlineCheckbox1"
                                    value="hadir"
                                />
                                <label
                                    class="form-check-label"
                                    for="inlineCheckbox1"
                                    >Hadir</label
                                >
                            </div>
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    v-model="kehadiran.kehadiran"
                                    id="inlineCheckbox2"
                                    value="tidak_hadir"
                                />
                                <label
                                    class="form-check-label"
                                    for="inlineCheckbox2"
                                    >Tidak Hadir</label
                                >
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" v-if="kehadiran.kehadiran == 'tidak_hadir'">
                      <label for="" class="col-4">Dokumen Pendukung</label>
                      <div class="col-8">
                        <dokumen :file="dokumenPendukung"/>
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
