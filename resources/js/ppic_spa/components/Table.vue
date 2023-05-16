<template>
    <div>
        <h1 class="subtitle">{{ getMonthYear() }}</h1>
        <div class="buttons">
            <template v-if="$store.state.user.divisi_id === 24">
                <button v-if="
            this.$store.state.state_ppic === 'pembuatan' ||
            this.$store.state.state_ppic === 'revisi'
          " class="button is-success" @click="showAddProdukModal">
                    <span>Tambah <i class="fas fa-plus"></i></span>
                </button>
                <button v-if="
            this.$store.state.state_ppic === 'pembuatan' ||
            this.$store.state.state_ppic === 'revisi'
          " class="button" :class="{
            'is-loading': this.$store.state.isLoading,
            'is-primary': this.$store.state.state_ppic === 'pembuatan',
            'is-danger': this.$store.state.state_ppic === 'revisi',
          }" :disabled="events.length === 0" @click="sendEvent('persetujuan')">
                    Kirim
                </button>
                <button v-if="
            this.$store.state.state_ppic === 'menunggu' &&
            this.$store.state.state === 'persetujuan'
          " class="button is-warning" @click="sendEvent('pembatalan')">
                    Batal
                </button>
                <button v-if="this.$store.state.state_ppic === 'disetujui'" class="button is-success"
                    :class="{ 'is-loading': this.$store.state.isLoading }" @click="sendEvent('perubahan')">
                    Minta Perubahan
                </button>
            </template>
            <button class="button is-info" :disabled="events.length === 0"
                @click="convertToExcel('export_table', 'W3C Example Table')">
                Export
            </button>
            <button v-if="data_komentar.length > 0 && $store.state.user.divisi_id === 24" class="button is-circle"
                @click="komentarModal = true">
                <i class="fas fa-envelope"></i>
            </button>
        </div>
        <div class="table-container">
            <template v-if="status === 'pelaksanaan' && format_jadwal_rencana.length > 0">
                <h1 class="subtitle">Perencanaan</h1>
                <table class="table has-text-centered is-bordered" style="white-space: nowrap">
                    <thead>
                        <tr>
                            <th rowspan="2">Nama Produk</th>
                            <th rowspan="2">Jumlah</th>
                            <th :colspan="last_date">Tanggal</th>
                        </tr>
                        <tr>
                            <th v-for="i in Array.from(Array(last_date).keys())" :key="i">
                                {{ i + 1 }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in format_jadwal_rencana" :key="item.id">
                            <td>{{ item.title }}</td>
                            <td>{{ item.jumlah }}</td>
                            <td v-for="i in Array.from(Array(last_date).keys())" :key="i" :style="{
                  backgroundColor:
                    weekend_date.indexOf(i + 1) !== -1
                      ? 'black'
                      : isDate(item.events, i + 1)
                      ? 'yellow'
                      : '',
                }"></td>
                        </tr>
                    </tbody>
                </table>
            </template>

            <h1 class="subtitle" v-if="status === 'pelaksanaan' && format_jadwal_rencana.length > 0">
                Pelaksanaan
            </h1>
            <table class="table has-text-centered is-bordered" style="white-space: nowrap" id="export_table">
                <thead>
                    <tr class="is-hidden">
                        <th :colspan="table_header_length" :style="{ fontSize: '2rem' }">
                            {{ table_header_title }}
                        </th>
                    </tr>
                    <tr>
                        <th rowspan="2">Nama Produk</th>
                        <th rowspan="2">Jumlah</th>
                        <th rowspan="2" v-if="status === 'pelaksanaan'">Progres</th>
                        <th v-if="
                $store.state.user.divisi_id === 24 &&
                ($store.state.state_ppic === 'pembuatan' ||
                  $store.state.state_ppic === 'revisi') &&
                !hiddenAction
              " rowspan="2">
                            Aksi
                        </th>
                        <th :colspan="last_date">Tanggal</th>
                    </tr>
                    <tr>
                        <th v-for="i in Array.from(Array(last_date).keys())" :key="i">
                            {{ i + 1 }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in format_events" :key="item.id">
                        <td>{{ item.title }}</td>
                        <td>{{ item.jumlah }}</td>
                        <td v-if="status === 'pelaksanaan'">{{ item.progres }}</td>
                        <td v-if="
                $store.state.user.divisi_id === 24 &&
                ($store.state.state_ppic === 'pembuatan' ||
                  $store.state.state_ppic === 'revisi') &&
                !hiddenAction
              ">
                            <div>
                                <span class="is-clickable" @click="updateEvent(item)">
                                    <i class="fas fa-edit"></i>
                                </span>
                                &nbsp;&nbsp;&nbsp;
                                <span class="is-clickable" @click="deleteEvent(item.events)">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        </td>
                        <td v-for="i in Array.from(Array(last_date).keys())" :key="i" :style="{
                backgroundColor:
                  weekend_date.indexOf(i + 1) !== -1
                    ? 'black'
                    : isDate(item.events, i + 1)
                    ? 'yellow'
                    : '',
              }"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal" :class="{ 'is-active': addProdukModal }">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">
                        {{ this.action === "add" ? "Pilih Produk" : "Ubah Jadwal" }}
                    </p>
                    <button class="delete" @click="addProdukModal = !addProdukModal"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">Tanggal Mulai</label>
                                <div class="control">
                                    <input type="date" :min="dateFormatter(year, month, 1)"
                                        :max="dateFormatter(year, month, last_date)" class="input"
                                        v-model="start_date" />
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">Tanggal Selesai</label>
                                <div class="control">
                                    <input type="date" :min="dateFormatter(year, month, 1)"
                                        :max="dateFormatter(year, month, last_date)" class="input" v-model="end_date" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">No BPPB</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input class="input" type="text" v-model="no_bppb" />
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Produk</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <v-select :options="options" v-model="produk" @input="changeProduk">
                                        <template v-slot:option="option">
                                            <b>{{option.merk}}</b> - {{ option.produk }}
                                        </template>
                                    </v-select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Jumlah</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input class="input" type="number" min="1" v-model="jumlah" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Stok</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <div>GBJ: {{ gbj_stok }}</div>
                                    <div>GK : {{ gk_stok }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button v-if="action === 'add'" class="button is-success"
                        :class="{ 'is-loading': this.$store.state.isLoading }" @click="handleSubmit">
                        Tambah
                    </button>
                    <button v-else-if="action" class="button is-info"
                        :class="{ 'is-loading': this.$store.state.isLoading }" @click="handleSubmit">
                        Ubah
                    </button>
                </footer>
            </div>
        </div>

        <div class="modal" :class="{ 'is-active': deleteProdukModal }">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Hapus Produk</p>
                    <button class="delete" @click="deleteProdukModal = false"></button>
                </header>
                <section class="modal-card-body">
                    <p>
                        Apakah anda yakin ingin menghapus produk ini dari daftar jadwal?
                    </p>
                </section>
                <footer class="modal-card-foot">
                    <div class="buttons is-justify-content-space-between">
                        <button class="button is-success" @click="handleSubmit">Ok</button>
                        <button class="button is-danger" @click="deleteProdukModal = false">
                            Batal
                        </button>
                    </div>
                </footer>
            </div>
        </div>

        <div class="modal" :class="{ 'is-active': editProdukModal }">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">
                        Edit Jadwal ({{ updated_events.title }})
                    </p>
                    <button class="delete" @click="editProdukModal = false"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column is-3">
                            <div class="field">
                                <label class="label">Tanggal Mulai</label>
                                <div v-for="(item, index) in updated_events.events" :key="'start'+index"
                                    class="control">
                                    <input type="date" :min="dateFormatter(year, month, 1)"
                                        :max="dateFormatter(year, month, last_date)" class="input"
                                        v-model="item.start" />
                                </div>
                            </div>
                        </div>
                        <div class="column is-3">
                            <div class="field">
                                <label class="label">Tanggal Selesai</label>
                                <div v-for="(item, index) in updated_events.events" :key="'end'+index" class="control">
                                    <input type="date" :min="dateFormatter(year, month, 1)"
                                        :max="dateFormatter(year, month, last_date)" class="input" v-model="item.end" />
                                </div>
                            </div>
                        </div>
                        <!-- <div class="column is-2">
                            <div class="field">
                                <label class="label">No BPPB</label>
                                <div v-for="(item, index) in updated_events.events" :key="'nobppb'+index"
                                    class="control">
                                    <input class="input" type="text" v-model="item.no_bppb"/>
                                </div>
                            </div>
                        </div> -->
                        <div class="column is-2">
                            <div class="field">
                                <label class="label">Jumlah</label>
                                <div v-for="(item, index) in updated_events.events" :key="'jumlah'+index"
                                    class="control">
                                    <input class="input" type="number" :min="item.progres > 0 ? item.progres : 1"
                                        v-model="item.jumlah" />
                                </div>
                            </div>
                        </div>
                        <div class="column is-2">
                            <div class="field">
                                <label class="label">Progres</label>
                                <div v-for="(item, index) in updated_events.events" :key="'progress'+index"
                                    class="control">
                                    <input class="input" type="number" v-model="item.progres" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="column is-2">
                            <div class="field">
                                <label class="label">Aksi</label>
                                <div v-for="(item, index) in updated_events.events" :key="'aksi'+index" class="control">
                                    <button class="button is-light" @click="deleteSingleEvent(index)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Stok</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <div>GBJ: {{ gbj_stok }}</div>
                                    <div>GK : {{ gk_stok }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <div class="buttons is-justify-content-space-between">
                        <button class="button is-success" @click="handleSubmit">Ok</button>
                        <button class="button is-danger" @click="editProdukModal = false">
                            Batal
                        </button>
                    </div>
                </footer>
            </div>
        </div>

        <!-- modal -->
        <div class="modal" :class="{ 'is-active': komentarModal }">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Komentar</p>
                    <button class="delete" aria-label="close" @click="komentarModal = !komentarModal"></button>
                </header>
                <section class="modal-card-body">
                    <table class="table is-fullwidth has-text-centered">
                        <thead>
                            <tr>
                                <th>hasil</th>
                                <th>komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in data_komentar" :key="item.id">
                                <td>{{ item.hasil ? "disetujui" : "ditolak" }}</td>
                                <td>{{ item.komentar }}</td>
                            </tr>
                        </tbody>
                    </table>
                </section>
                <footer class="modal-card-foot"></footer>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    import vSelect from "vue-select";
    import "vue-select/dist/vue-select.css";

    /**
     * @vue-prop {Array} events - array of schedule data
     * @vue-prop {String} status - status string
     *
     * @vue-data {Number} last_date - variable to store last date of the month
     * @vue-data {Number} month - variable to store month number
     * @vue-data {Number} year - variable to store year number
     * @vue-data {Array} weekend_date - Array of date of weekend
     *
     * @vue-data {Boolean} [addProdukModal=false] - flag to show or hide modal to add product to schedule
     * @vue-data {Boolean} [deleteProdukModal=false] - flag to show or hide modal to delete product from schedule
     * @vue-data {Boolean} [editProdukModal=false] - flag to show or hide modal to edit product schedule
     * @vue-data {Boolean} [komentarModal=false] - flag to show or hide komentar modal
     * @vue-data {Boolean} [hiddenAction=false] - flag to show or hide acttion button from table
     *
     * @vue-data {Array} [data_komentar=[]] - Array to store list of komentar getted from API (url = '/api/ppic/data/komentar')
     * @vue-data {Object} updated_events - Object to store data of product that want to update in schedule
     * @vue-data {Array} deleted_events - Array of schedule item from product that want to deleted
     *
     * @vue-data {String} start_date - string of start date
     * @vue-data {String} end_date - string of end date
     * @vue-data {Object} product - object data of selected product when adding product to schedule
     * @vue-data {Number} jumlah - number of product that want to add to schedule
     *
     * @vue-data {String} action - string as flag to decide what action to implement at submit button
     *
     * @vue-data {Array} data_produk - array of list products get from API (url = '/api/ppic/data/gbj')
     * @vue-data {Number} gk_stok - number of selected product in gk
     * @vue-data {Number} gbj_stok - number of selected product in gbj
     *
     * @vue-data {Array} rencana_jadwal - schedule planning from previous planning
     *
     * @vue-data {Number} table_header_length - count number of row used for header of table
     * @vue-data {String} table_header_title - title of table header
     *
     * @vue-event {Boolean} isDate - check wheater date in date range given
     * @vue-event {Number} changeProduk - show gbj and gk stok of produk
     * @vue-event {Object} handleSubmit - handle submit button for modal
     * @vue-event {Boolean} showAddProdukModal - show modal to add schedule
     * @vue-event {Boolean} updateEvent - show modal to change schedule
     * @vue-event {Boolean} deleteEvent - show modal to delete schedule
     * @vue-event {Object} deleteSingleEvent - function to delete single event
     * @vue-event {Object} sendEvent - sending schedule to get acceptable from manager
     * @vue-event {File} convertToExcel - function to export table to excel file
     * @vue-event {Object} resetData - function to reset data after submiting form
     * @vue-event {String} dateFormatter - change date format to string
     * @vue-event {String} getMonthYear - get current month and year in string form
     *
     * @vue-computed {Array} options - computed options for vue-select
     * @vue-computed {Array} format_events - change structure of schedule to fit table
     * @vue-computed {Array} format_jadwal_rencana - change structure of schedule planning to fit table
     */

    export default {
        name: "table-component",

        props: {
            events: {
                type: Array,
                required: true,
            },
            status: {
                type: String,
                required: true,
            },
        },

        components: {
            vSelect,
        },

        data() {
            return {
                last_date: 1,
                month: 0,
                year: 0,
                weekend_date: [],

                addProdukModal: false,
                deleteProdukModal: false,
                editProdukModal: false,
                komentarModal: false,

                hiddenAction: false,

                data_komentar: [],

                updated_events: {
                    events: [],
                },
                deleted_events: [],

                start_date: "",
                end_date: "",
                // no_bppb: null,
                produk: null,
                jumlah: 1,
                action: "add",

                data_produk: [],
                gk_stok: 0,
                gbj_stok: 0,

                rencana_jadwal: [],

                table_header_length: 0,
                table_header_title: "",
            };
        },

        async created() {
            this.$store.commit("setIsLoading", true);

            await axios.get("/api/ppic/data/gbj",{
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                }
            }).then((response) => {
                this.data_produk = response.data;
            });

            this.getDataKomentar();

            if (this.status === "pelaksanaan") {
                await axios.get("/api/ppic/data/rencana_perakitan",{
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                }
            }).then((response) => {
                    this.rencana_jadwal = response.data;
                this.$store.commit("setIsLoading", false);
                });
            }


            let date = new Date();
            if (this.status === "pelaksanaan") {
                this.month = date.getMonth();
                this.year = date.getFullYear();
            } else if (this.status === "penyusunan") {
                this.month = date.getMonth() + 1;
                this.year = date.getFullYear();
                if (this.month === 12) {
                    this.month = 0;
                    this.year += 1;
                }
            }

            date = new Date(this.year, this.month + 1, 0);
            this.last_date = date.getDate();

            date.setMonth(this.month);
            for (let i = 1; i <= this.last_date; i++) {
                date.setDate(i);
                if (date.getDay() == 6 || date.getDay() == 0) this.weekend_date.push(i);
            }
        },

        methods: {
            async getDataKomentar() {
                await axios
                    .get("/api/ppic/data/komentar", {
                        params: {
                            status: this.status,
                        },
                    })
                    .then((response) => {
                        this.data_komentar = response.data;
                    })
                    .catch((error) => {
                        console.log("error to get data komentar");
                        console.log(error);
                    });
            },

            isDate(tanggal, i) {
                for (const id in tanggal) {
                    let start = new Date(tanggal[id].start);
                    let end = new Date(tanggal[id].end);

                    let start_number = start.getDate();
                    let end_number = end.getDate();

                    // handle end equal to last date
                    if (start.getMonth() !== end.getMonth()) end_number = this.last_date;

                    if (i >= start_number && i <= end_number) return true;
                }

                return false;
            },

            async showAddProdukModal() {
                this.resetData();
                this.action = "add";
                this.addProdukModal = true;
            },

            async changeProduk() {
                if (this.produk === null) return;
                this.$store.commit("setIsLoading", true);

                await axios
                    .get("/api/ppic/data/gbj",{
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                        }
                    }, {
                        params: {
                            id: this.produk.value,
                        },
                    })
                    .then((response) => {
                        if (response.data.length > 0) this.gbj_stok = response.data[0].stok;
                        else this.gk_stok = 0;
                    })
                    .catch((error) => {
                        console.log(error);
                    });

                await axios
                    .get("/api/ppic/data/gk/unit",{
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                        }
                    }, {
                        params: {
                            id: this.produk.value,
                        },
                    })
                    .then((response) => {
                        if (response.data.length > 0) this.gk_stok = response.data[0].jml;
                        else this.gk_stok = 0;
                    })
                    .catch((error) => {
                        console.log(error);
                    });

                this.$store.commit("setIsLoading", false);
            },

            async handleSubmit() {
                this.$store.commit("setIsLoading", true);
                if (this.action === "add") {
                    let start_date = new Date(this.start_date);
                    let end_date = new Date(this.end_date);

                    if (
                        !this.start_date ||
                        !this.end_date ||
                        this.produk === null ||
                        this.jumlah < 1 ||
                        end_date.getDate() < start_date.getDate()
                    ) {
                        let text;
                        if (end_date.getDate() < start_date.getDate())
                            text =
                            "Tanggal mulai harus lebih dahulu dibandingkan tanggal selesai";
                        else text = "Mohon periksa kembali form yang Anda isi !!";

                        this.$swal({
                            icon: "warning",
                            title: "Peringatan",
                            text: text,
                        });
                        this.$store.commit("setIsLoading", false);
                        return;
                    }

                    let data = {
                        produk_id: this.produk.value,
                        // no_bppb: this.no_bppb,
                        jumlah: this.jumlah,
                        tanggal_mulai: this.start_date,
                        tanggal_selesai: this.end_date,
                        status: this.status,
                        state: this.$store.state.state,
                        konfirmasi: this.$store.state.konfirmasi,
                        warna: "#007bff",
                    };

                    await axios
                        .post("/api/ppic/create/perakitan", data)
                        .then((response) => {
                            this.$store.commit("setJadwal", response.data);
                            this.addProdukModal = false;
                        })
                        .catch((err) => {
                            this.$swal({
                                icon: "error",
                                title: "Error",
                                text: "Gagal menambahkan data, hubungi pihak IT untuk memeriksa masalah!!",
                            });
                        });
                } else if (this.action === "update") {
                    for (const index in this.updated_events.events) {
                        let start_date = new Date(this.updated_events.events[index].start);
                        let end_date = new Date(this.updated_events.events[index].end);
                        // let no_bppb = this.updated_events.events[index].no_bppb;

                        if (
                            this.updated_events.events[index].jumlah < 1 ||
                            end_date.getDate() < start_date.getDate() ||
                            this.updated_events.events[index].jumlah <
                            this.updated_events.events[index].progres
                        ) {
                            let text;
                            if (end_date.getDate() < start_date.getDate())
                                text =
                                "Tanggal mulai harus lebih dahulu dibandingkan tanggal selesai";
                            else if (
                                this.updated_events.events[index].jumlah <
                                this.updated_events.events[index].progres
                            )
                                text =
                                "Jumlah tidak boleh kurang dari progres yang telah dirakit";
                            else text = "Mohon periksa kembali form yang Anda isi !!";

                            this.$swal({
                                icon: "warning",
                                title: "Peringatan",
                                text: text,
                            });
                            this.$store.commit("setIsLoading", false);
                            return;
                        }
                    }

                    for (const index in this.updated_events.events) {
                        await axios
                            .post(
                                "/api/ppic/update/perakitan/" +
                                this.updated_events.events[index].id, {
                                    tanggal_mulai: this.updated_events.events[index].start,
                                    tanggal_selesai: this.updated_events.events[index].end,
                                    jumlah: this.updated_events.events[index].jumlah,
                                    // no_bppb: this.updated_events.events[index].no_bppb,
                                    status: this.status,
                                }
                            )
                            .catch((err) => {
                                this.$swal({
                                    icon: "error",
                                    title: "Error",
                                    text: "Gagal mengubah data, hubungi pihak IT untuk memeriksa masalah!!",
                                });
                            });
                    }

                    await axios
                        .get("/api/ppic/data/perakitan/" + this.status,{
                            headers: {
                                Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                            }
                        })
                        .then((response) => {
                            this.$store.commit("setJadwal", response.data);
                            this.editProdukModal = false;
                        });
                } else if (this.action === "delete") {
                    for (const index in this.deleted_events) {
                        await axios
                            .post("/api/ppic/delete/perakitan/" + this.deleted_events[index].id)
                            .catch((error) => {
                                this.$swal({
                                    icon: "error",
                                    title: "Error",
                                    text: "Gagal menghapus jadwal",
                                });
                            });
                    }
                    await axios
                        .get("/api/ppic/data/perakitan/" + this.status,{
                            headers: {
                                Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                            }
                        })
                        .then((response) => {
                            this.$store.commit("setJadwal", response.data);
                            this.deleteProdukModal = false;
                        });
                }
                this.$store.commit("setIsLoading", false);
            },

            async updateEvent(events) {
                this.action = "update";
                this.updated_events = JSON.parse(JSON.stringify(events));

                await axios
                    .get("/api/ppic/data/gbj", {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                        }
                    }, {
                        params: {
                            id: events.produk_id,
                        },
                    })
                    .then((response) => {
                        if (response.data.length > 0) this.gbj_stok = response.data[0].stok;
                        else this.gk_stok = 0;
                    })
                    .catch((error) => {
                        console.log(error);
                    });

                await axios
                    .get("/api/ppic/data/gk/unit",{
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                        }
                    }, {
                        params: {
                            id: events.produk_id,
                        },
                    })
                    .then((response) => {
                        if (response.data.length > 0) this.gk_stok = response.data[0].jml;
                        else this.gk_stok = 0;
                    })
                    .catch((error) => {
                        console.log(error);
                    });

                this.editProdukModal = true;
            },

            deleteEvent(events) {
                this.action = "delete";
                this.deleted_events = events;

                this.deleteProdukModal = true;
            },

            async deleteSingleEvent(index) {
                this.$store.commit("setIsLoading");
                await axios
                    .post(
                        "/api/ppic/delete/perakitan/" + this.updated_events.events[index].id
                    )
                    .then(async (response) => {
                        await axios
                            .get("/api/ppic/data/perakitan/" + this.status,{
                                headers: {
                                    Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                                }
                            })
                            .then((response) => {
                                this.$store.commit("setJadwal", response.data);
                            });

                        this.updated_events.events.splice(index, 1);
                        if (this.updated_events.events.length === 0)
                            this.editProdukModal = false;
                    })
                    .catch((error) => {
                        this.$swal({
                            icon: "error",
                            title: "Error",
                            text: "Gagal menghapus jadwal",
                        });
                    });
            },

            async sendEvent(state) {
                this.$store.commit("setIsLoading", true);
                if (state === "pembatalan") {
                    await axios
                        .post("/api/ppic/update/perakitans/" + this.status, {
                            state: "perencanaan",
                            konfirmasi: 0,
                        })
                        .then((response) => {
                            this.$store.commit("setJadwal", response.data);
                        })
                        .catch((error) => {
                            this.$swal({
                                icon: "error",
                                title: "Error",
                                text: "Terdapat kesalahan saat mengirim pembatalan, silakan coba lagi",
                            });
                            return;
                        });

                    await axios
                        .post("/api/ppic/create/komentar", {
                            status: this.status,
                        })
                        .catch((err) => {
                            this.$swal({
                                icon: "warning",
                                title: "Peringatan",
                                text: "Terdapat kesalahan saat membuat komentar pada database",
                            });
                        });
                } else {
                    await axios
                        .post("/api/ppic/update/perakitans/" + this.status, {
                            state: state,
                            konfirmasi: 0,
                        })
                        .then((response) => {
                            this.$store.commit("setJadwal", response.data);
                        })
                        .catch((error) => {
                            this.$swal({
                                icon: "error",
                                title: "Error",
                                text: "Terdapat kesalahan saat mengirim permintaan, silakan coba lagi",
                            });
                            return;
                        });

                    await axios
                        .post("/api/ppic/create/komentar", {
                            tanggal_permintaan: new Date(),
                            state: this.$store.state.state,
                            status: this.status,
                        })
                        .catch((err) => {
                            this.$swal({
                                icon: "warning",
                                title: "Peringatan",
                                text: "Terdapat kesalahan saat membuat komentar pada database",
                            });
                        });
                }

                if (this.$store.state.enable_notif) {
                    await axios
                        .post("/api/ppic/send_notification", {
                            user: this.$store.state.user,
                            status: this.$store.state.status,
                            state: this.$store.state.state,
                        })
                        .catch((err) => {
                            this.$swal({
                                icon: "warning",
                                title: "Peringatan",
                                text: "Gagal mengirim notifikasi, namun data telah terkirim pada database",
                            });
                        });
                }

                this.$store.commit("setIsLoading", false);
            },

            convertToExcel(table, name) {
                let uri = "data:application/vnd.ms-excel;base64,",
                    template =
                    '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
                    base64 = function (s) {
                        return window.btoa(unescape(encodeURIComponent(s)));
                    },
                    format = function (s, c) {
                        return s.replace(/{(\w+)}/g, function (m, p) {
                            return c[p];
                        });
                    };

                if (!table.nodeType) table = document.getElementById(table);
                let ctx = {
                    worksheet: name || "Worksheet",
                    table: table.innerHTML
                };
                window.location.href = uri + base64(format(template, ctx));
            },

            // helper
            resetData() {
                this.start_date = "";
                this.end_date = "";
                this.produk = null;
                this.jumlah = 1;
                this.gk_stok = 0;
                this.gbj_stok = 0;
            },

            dateFormatter(year, month, date) {
                let real_month = month + 1;
                real_month = real_month.toString();
                if (real_month.length == 1) {
                    real_month = "0" + real_month;
                }

                let real_date = date.toString();
                if (real_date.length == 1) {
                    real_date = "0" + real_date;
                }

                let result = year.toString() + "-" + real_month + "-" + real_date;
                return result;
            },

            getMonthYear() {
                let temp_month = "";
                switch (this.month) {
                    case 0:
                        temp_month = "Januari";
                        break;
                    case 1:
                        temp_month = "Februari";
                        break;
                    case 2:
                        temp_month = "Maret";
                        break;
                    case 3:
                        temp_month = "April";
                        break;
                    case 4:
                        temp_month = "Mei";
                        break;
                    case 5:
                        temp_month = "Juni";
                        break;
                    case 6:
                        temp_month = "Juli";
                        break;
                    case 7:
                        temp_month = "Agustus";
                        break;
                    case 8:
                        temp_month = "September";
                        break;
                    case 9:
                        temp_month = "Oktober";
                        break;
                    case 10:
                        temp_month = "November";
                        break;
                    case 11:
                        temp_month = "Desember";
                        break;
                }

                return temp_month + " " + this.year.toString();
            },
        },

        computed: {
            options: function () {
                let data = this.data_produk.map((data) => ({
                    merk: `${data.produk.merk}`,
                    produk: `${data.produk.nama} ${data.nama}`,
                    label: `${data.produk.merk} - ${data.produk.nama} ${data.nama}`,
                    value: data.id,
                }));
                return data;
            },

            format_events() {
                let data = [];
                let exists = {};
                for (let i = 0; i < this.events.length; i++) {
                    exists = data.find(
                        (item) => item.produk_id === this.events[i].produk_id
                    );
                    if (data.length === 0 || exists === undefined) {
                        data.push({
                            produk_id: this.events[i].produk_id,
                            title: this.events[i].title,
                            jumlah: this.events[i].jumlah,
                            progres: this.events[i].progres,
                            events: [{
                                id: this.events[i].id,
                                start: this.events[i].start,
                                end: this.events[i].end,
                                jumlah: this.events[i].jumlah,
                                progres: this.events[i].progres,
                                // no_bppb: this.events[i].no_bppb
                            }, ],
                        });
                    } else {
                        exists.events.push({
                            id: this.events[i].id,
                            start: this.events[i].start,
                            end: this.events[i].end,
                            jumlah: this.events[i].jumlah,
                            progres: this.events[i].progres,
                            // no_bppb: this.events[i].no_bppb,
                        });
                        exists.jumlah += this.events[i].jumlah;
                        exists.progres += this.events[i].progres;
                    }
                }

                this.table_header_length = this.last_date + 2;
                if (this.status === "pelaksanaan") this.table_header_length += 1;
                if (
                    this.$store.state.user.divisi_id === 24 &&
                    (this.$store.state.state_ppic === "pembuatan" ||
                        this.$store.state.state_ppic === "revisi") &&
                    !this.hiddenAction
                )
                    this.table_header_length += 1;

                this.table_header_title = `(${this.status.toUpperCase()}) ${this.getMonthYear()}`;

                return data;
            },

            format_jadwal_rencana() {
                let data = [];
                let exists = {};
                for (let i = 0; i < this.rencana_jadwal.length; i++) {
                    exists = data.find(
                        (item) =>
                        item.produk_id === this.rencana_jadwal[i].jadwal_perakitan.produk_id
                    );
                    if (data.length === 0 || exists === undefined) {
                        data.push({
                            produk_id: this.rencana_jadwal[i].jadwal_perakitan.produk_id,
                            title: `${this.rencana_jadwal[i].jadwal_perakitan.produk.produk.nama} ${this.rencana_jadwal[i].jadwal_perakitan.produk.nama}`,
                            jumlah: this.rencana_jadwal[i].jadwal_perakitan.jumlah,
                            events: [{
                                id: this.rencana_jadwal[i].jadwal_perakitan.id,
                                start: this.rencana_jadwal[i].jadwal_perakitan.tanggal_mulai,
                                end: this.rencana_jadwal[i].jadwal_perakitan.tanggal_selesai,
                                jumlah: this.rencana_jadwal[i].jadwal_perakitan.jumlah,
                            }, ],
                        });
                    } else {
                        exists.events.push({
                            id: this.rencana_jadwal[i].jadwal_perakitan.id,
                            start: this.rencana_jadwal[i].jadwal_perakitan.tanggal_mulai,
                            end: this.rencana_jadwal[i].jadwal_perakitan.tanggal_selesai,
                            jumlah: this.rencana_jadwal[i].jadwal_perakitan.jumlah,
                        });
                        exists.jumlah += this.rencana_jadwal[i].jadwal_perakitan.jumlah;
                    }
                }

                return data;
            },

            notif() {
                return this.$store.state.notif;
            },
        },

        watch: {
            notif() {
                if (this.$store.state.notif.user.divisi_id === 3) this.getDataKomentar();
            },
        },
    };

</script>

<style scoped>
    .background_yellow {
        background: yellow;
    }

    .background_black {
        background: black;
    }

</style>
