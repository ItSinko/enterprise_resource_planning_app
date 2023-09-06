<script>
export default {
    props: ["meeting"],
    data() {
        return {
            kesesuaian: JSON.parse(JSON.stringify(this.meeting)),
        }
    },
    methods: {
        closeModal() {
            this.$nextTick(() => {
                $(".modalNotulen").modal("hide");
            });
            this.$emit("closeModal");
        },
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kesesuaian</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="">Uraian</label>
                      <textarea class="form-control" v-model="kesesuaian.isi" disabled></textarea>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2">Hasil</label>
                        <div class="col-10">
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="inlineRadioOptions"
                                    v-model="kesesuaian.kesesuaian"
                                    id="inlineRadio1"
                                    value="sesuai"
                                />
                                <label
                                    class="form-check-label"
                                    for="inlineRadio1"
                                    >Sesuai</label
                                >
                            </div>
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="inlineRadioOptions"
                                    v-model="kesesuaian.kesesuaian"
                                    id="inlineRadio2"
                                    value="tidak_sesuai"
                                />
                                <label
                                    class="form-check-label"
                                    for="inlineRadio2"
                                    >Tidak Sesuai</label
                                >
                            </div>
                        </div>
                    </div>
                    <div class="form-group" v-if="kesesuaian.kesesuaian == 'tidak_sesuai'">
                      <label for="">Catatan</label>
                      <textarea class="form-control" v-model="kesesuaian.catatan"></textarea>
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
                    <button type="button" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
