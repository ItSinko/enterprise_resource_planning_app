<script>
import axios from "axios";
import VueSelect from "vue-select";
export default {
    props: ["formnotulen"],
    components: {
        VueSelect,
    },
    data() {
        return {
            karyawan: [],
        };
    },
    methods: {
        save() {
            this.$emit("save");
        },
        async getDataKaryawan() {
            try {
                const response = await axios.get("/api/karyawan_all");
                this.karyawan = response.data;
            } catch (error) {
                console.log(error);
            }
        },
        closeModal() {
            this.$nextTick(() => {
                $(".modalNotulen").modal("hide");
            });
            this.$emit("closeModal");
        },
    },
    mounted() {
        this.getDataKaryawan();
    },
};
</script>
<template>
    <div
        class="modal fade modalNotulen"
        id="modelId"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modelTitleId"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Hasil Notulensi</h5>
                    <button
                        type="button"
                        class="close"
                        @click="closeModal"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Penanggung Jawab</label>
                        <vue-select
                            :options="karyawan"
                            label="nama"
                            :reduce="(karyawan) => karyawan.id"
                            v-model="formnotulen.penanggungjawab"
                        />
                    </div>

                    <div class="form-group">
                        <label for="">Uraian</label>
                        <textarea class="form-control" v-model="formnotulen.isi"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="">Kesesuaian</label>
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="inlineRadioOptions"
                                id="inlineRadio1"
                                v-model="formnotulen.kesesuaian"
                                value="sesuai"
                            />
                            <label class="form-check-label" for="inlineRadio1"
                                >Sesuai</label
                            >
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="inlineRadioOptions"
                                id="inlineRadio2"
                                v-model="formnotulen.kesesuaian"
                                value="tidak_sesuai"
                            />
                            <label class="form-check-label" for="inlineRadio2"
                                >Tidak Sesuai</label
                            >
                        </div>
                    </div>

                    <div class="form-group" v-if="formnotulen.kesesuaian == 'tidak_sesuai'">
                      <label for="">Catatan</label>
                      <textarea class="form-control" v-model="formnotulen.catatan"></textarea>
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
                    <button type="button" class="btn btn-primary" @click="save">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>
