<script>
import dokumentasi from "./dokumentasi.vue";
import axios from "axios";
import VueSelect from "vue-select";
export default {
    components: {
        VueSelect,
        dokumentasi,
    },
    props: ["meeting"],
    data() {
        return {
            cloneMeeting: JSON.parse(JSON.stringify(this.meeting)),
            form: {
                dokumentasi: {
                    foto: [],
                    video: [],
                    rekaman: [],
                    lainnya: [],
                },
                notulensi: [
                    {
                        pic: "",
                        isi: "",
                    },
                ],
                hasil: [
                    {
                        isi: "",
                    },
                ],
            },
            karyawan: [],
        };
    },
    methods: {
        closeModal() {
            this.$nextTick(() => {
                $(".modalterlaksana").modal("hide");
            });
            this.$emit("closeModal");
        },
        async getDataKaryawan() {
            try {
                const response = await axios.get("/api/karyawan_all");
                this.karyawan = response.data;
            } catch (error) {
                console.log(error);
            }
        },
        tambahpic() {
            this.form.notulensi.push({
                pic: "",
                isi: "",
            });
        },
        tambahhasil() {
            this.form.hasil.push({
                isi: "",
            });
        },
        save() {
            // detected form data is empty or not this.form.file = null
            const isFormEmpty = Object.values(this.form.dokumentasi).some(
                (field) => {
                    if (Array.isArray(field)) {
                        // If the field is an array, check if any of its items have a null file
                        return field.some((item) => item.file === null);
                    } else {
                        // If the field is not an array, check if it has a null file
                        return field.file === null;
                    }
                }
            );
            if (isFormEmpty) {
                this.$swal("Gagal", "Form tidak boleh kosong", "error");
                return;
            }
        },
    },
    mounted() {
        this.getDataKaryawan();
    },
    computed: {
        showKeteranganKetidaksesuaian() {
            if (
                this.meeting.tanggal != this.cloneMeeting.tanggal ||
                this.meeting.lokasi != this.cloneMeeting.lokasi ||
                this.meeting.mulai != this.cloneMeeting.mulai ||
                this.meeting.selesai != this.cloneMeeting.selesai
            ) {
                return true;
            }
            return false;
        },
    },
};
</script>
<template>
    <div
        class="modal fade modalterlaksana"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ meeting.nama }}</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="tanggal" class="col-form-label"
                                >Tanggal</label
                            >
                            <input
                                type="date"
                                class="form-control"
                                v-model="meeting.tanggal"
                            />
                        </div>
                        <div class="col-sm-6">
                            <label for="tanggal" class="col-form-label"
                                >Lokasi</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                v-model="meeting.lokasi"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mulai" class="col-sm-2 col-form-label"
                            >Jam</label
                        >
                        <div class="col-sm-4">
                            <input
                                type="time"
                                class="form-control"
                                v-model="meeting.mulai"
                            />
                        </div>
                        -
                        <div class="col-sm-4">
                            <input
                                type="time"
                                class="form-control"
                                v-model="meeting.selesai"
                            />
                        </div>
                    </div>
                    <div
                        class="form-group"
                        v-if="showKeteranganKetidaksesuaian"
                    >
                        <label for="">Keterangan Ketidaksesuaian</label>
                        <textarea
                            class="form-control"
                            v-model="form.keteranganketidaksesuaian"
                        ></textarea>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"
                            >Hasil Notulensi</label
                        >
                        <div class="col-sm-10">
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <div class="p-2 bd-highlight">
                                    <button
                                        class="btn btn-primary"
                                        @click="tambahpic"
                                    >
                                        Tambah
                                    </button>
                                </div>
                            </div>
                            <div
                                v-for="(notulen, idx) in form.notulensi"
                                class="row mb-1"
                            >
                                <div class="col-sm-4">
                                    <vue-select
                                        v-model="notulen.pic"
                                        :options="karyawan"
                                        label="nama"
                                        :reduce="(karyawan) => karyawan.id"
                                        placeholder="penanggung jawab"
                                    />
                                </div>
                                <div class="col-sm-6">
                                    <textarea
                                        class="form-control"
                                        v-model="notulen.isi"
                                        placeholder="Isi Notulensi"
                                    ></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <button
                                        class="btn btn-danger"
                                        @click="form.notulensi.splice(idx, 1)"
                                    >
                                        x
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"
                            >Hasil Rapat</label
                        >
                        <div class="col-sm-10">
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <div class="p-2 bd-highlight">
                                    <button
                                        class="btn btn-primary"
                                        @click="tambahhasil"
                                    >
                                        Tambah
                                    </button>
                                </div>
                            </div>
                            <div v-for="(hasil, idx) in form.hasil" class="row">
                                <div class="col-10">
                                    <textarea
                                        class="form-control mb-2"
                                        v-model="hasil.isi"
                                    ></textarea>
                                </div>
                                <div class="col-2">
                                    <button
                                        class="btn btn-danger"
                                        @click="form.hasil.splice(idx, 1)"
                                    >
                                        x
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                        :file="form.dokumentasi.foto"
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
                                        :file="form.dokumentasi.video"
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
                                        :file="form.dokumentasi.rekaman"
                                        format="audio"
                                    />
                                </div>
                                <div
                                    class="tab-pane fade"
                                    id="pills-lainnya"
                                    role="tabpanel"
                                    aria-labelledby="pills-lainnya-tab"
                                >
                                    <dokumentasi
                                        :file="form.dokumentasi.lainnya"
                                    />
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
