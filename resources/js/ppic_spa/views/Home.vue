<template>
  <div>
    <h1 class="title">Dashboard</h1>
    <div class="columns is-multiline">
      <div class="column is-6">
        <article class="message is-primary">
          <div class="message-header">
            <p>Permintaan</p>
          </div>
          <div class="message-body has-text-centered">
            <p class="subtitle is-1">{{ jumlah_permintaan }}</p>
          </div>
        </article>
      </div>
      <div class="column is-6">
        <article class="message is-warning">
          <div class="message-header">
            <p>Proses</p>
          </div>
          <div class="message-body has-text-centered">
            <p class="subtitle is-1">{{ jumlah_proses }}</p>
          </div>
        </article>
      </div>
      <div class="column is-6">
        <div class="box">
          <table
            class="table is-fullwidth has-text-centered"
            id="table_so_detail"
          >
            <thead>
              <tr>
                <th>No</th>
                <th>Nomor SO</th>
                <th>Nomor PO</th>
                <th>Tanggal Order</th>
                <th>Customer</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in salesOrder" :key="'so' + item.DT_RowIndex">
                <td v-html="item.DT_RowIndex"></td>
                <td v-html="item.so"></td>
                <td v-html="item.no_po"></td>
                <td v-html="item.tgl_po"></td>
                <td v-html="item.nama_customer"></td>
                <td v-html="item.status_prd"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="column is-6">
        <div class="box">
          <table class="table is-fullwidth has-text-centered" id="table_so">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Stok</th>
                <th>Pesanan</th>
                <th>Jumlah Terkirim</th>
                <th>Selisih stok dengan pesanan</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in data_so" :key="'table_so' + item.id">
                <td>{{ item.DT_RowIndex }}</td>
                <td v-html="item.nama_produk"></td>
                <td>{{ item.stok }}</td>
                <td>{{ item.jumlah }}</td>
                <td>{{ item.jumlah_pengiriman }}</td>
                <td>{{ item.stok - item.jumlah }}</td>
                <!-- <td>{{ item.DT_RowIndex }}</td>
                <td v-html="item.nama_produk"></td>
                <td>{{ item.stok }}</td>
                <td>{{ item.total }}</td>
                <td v-text="item.jumlah_kirim"></td>
                <td><span :class="{ 'has-text-danger' : item.penjualan < 0 }">{{ item.penjualan }}</span></td> -->
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="column is-6">
        <div class="box">
          <table class="table is-fullwidth has-text-centered" id="table_gbj">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Stok</th>
                <th>Kelompok</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(d, index) in data_gbj" :key="'table_gbj' + d.id">
                <td>{{ index + 1 }}</td>
                <td>{{ d.produk.product.kode }}</td>
                <td>{{ d.produk.nama + " " + d.nama }}</td>
                <td>{{ d.stok }}</td>
                <td>{{ d.produk.kelompok_produk.nama }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="column is-6">
        <div class="box">
          <div class="tabs is-centered">
            <ul>
              <li :class="{ 'is-active': !tabs }" @click="tabs = false">
                <a>
                  <span class="icon is-small"
                    ><i class="fab fa-whmcs" aria-hidden="true"></i
                  ></span>
                  <span>Sparepart</span>
                </a>
              </li>
              <li :class="{ 'is-active': tabs }" @click="tabs = true">
                <a>
                  <span class="icon is-small"
                    ><i class="fas fa-tools" aria-hidden="true"></i
                  ></span>
                  <span>Unit</span>
                </a>
              </li>
            </ul>
          </div>
          <!-- sparepart -->
          <div :class="{ 'is-hidden': tabs }">
            <table
              class="table is-fullwidth has-text-centered"
              id="table_sparepart"
            >
              <thead>
                <tr>
                  <th>Kode Sparepart</th>
                  <th>Nama</th>
                  <th>Unit</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in data_sparepart"
                  :key="'table_sparepart' + item.id"
                >
                  <td>{{ item.kode }}</td>
                  <td>{{ item.nama }}</td>
                  <td>{{ item.unit }}</td>
                  <td>{{ item.jml }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- unit -->
          <div :class="{ 'is-hidden': !tabs }">
            <table class="table is-fullwidth has-text-centered" id="table_unit">
              <thead>
                <tr>
                  <th>Kode Unit</th>
                  <th>Nama</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in data_unit" :key="index">
                  <td>
                    <span v-if="item.kode_produk == null">-</span>
                    <span v-else>{{ item.kode_produk }}</span>
                  </td>
                  <td v-html="item.nama_produk"></td>
                  <td>
                    <span v-if="item.jml == null">0</span>
                    <span v-else>{{ item.jml }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueApexCharts from "vue-apexcharts";
import $ from "jquery";
import axios from "axios";

/**
 * @vue-data {Array} [data_gbj=[]] - this data store list of gbj products getted from API (url = '/api/ppic/data/gbj')
 * @vue-data {Array} [data_so=[]] - this data store list of sales order data getted from API (url = '/api/ppic/data/so')
 * @vue-data {Array} [data_unit=[]] - this data store list of gk unit items getted from API (url = '/api/ppic/data/gk/unit')
 * @vue-data {Array} [data_sparepart=[]] - this data store list of gk sparepart items getted from API (url = '/api/ppic/data/gk/sparepart')
 * @vue-data {Number} [jumlah_permintaan=0] - this data store number of schedule change request from PPIC to manager
 * @vue-data {Number} [jumlah_proses=0] - this data store number of schedule change process from manager to PPIC
 *
 * @vue-event {Array} loadData - this function is used to initialized data by calling the APIs
 */

export default {
  name: "Home",

  components: {
    apexcharts: VueApexCharts,
  },

  data() {
    return {
      data_gbj: [],
      data_so: [],
      salesOrder: [],
      data_unit: [],
      data_sparepart: [],

      jumlah_permintaan: 0,
      jumlah_proses: 0,

      tabs: false,
    };
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      const body = {};
      await axios
        .post("/api/prd/so", body, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("lokal_token"),
          },
        })
        .then((response) => {
          this.salesOrder = response.data.data;
        });
      $("#table_so_detail").DataTable({
        pagingType: "simple_numbers_no_ellipses",
      });

      await axios
        .post("/api/ppic/master_pengiriman/data", body, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("lokal_token"),
          },
        })
        .then((response) => {
          this.data_so = response.data.data;
        });
      $("#table_so").DataTable({
        pagingType: "simple_numbers_no_ellipses",
      });

      await axios
        .get("/api/ppic/data/gbj", {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("lokal_token"),
          },
        })
        .then((response) => {
          this.data_gbj = response.data;
        });
      $("#table_gbj").DataTable({
        pagingType: "simple_numbers_no_ellipses",
      });

      await axios
        .get("/api/ppic/data/gk/sparepart", {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("lokal_token"),
          },
        })
        .then((response) => {
          this.data_sparepart = response.data;
        });

      await axios
        .get("/api/ppic/data/gk/unit", {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("lokal_token"),
          },
        })
        .then((response) => {
          this.data_unit = response.data.data;
        });
      $("#table_sparepart").DataTable({
        pagingType: "simple_numbers_no_ellipses",
      });
      $("#table_unit").DataTable({
        pagingType: "simple_numbers_no_ellipses",
      });

      await axios.get("/api/ppic/counting/komentar").then((response) => {
        this.jumlah_permintaan = response.data[0];
        this.jumlah_proses = response.data[1];
      });

      this.$store.commit("setIsLoading", false);
    },
    checkToken() {
      if (localStorage.getItem("lokal_token") == null) {
        // event.preventDefault();
        this.$swal({
          title: "Session Expired",
          text: "Silahkan login kembali",
          icon: "warning",
          showCancelButton: false,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "OK",
        }).then((result) => {
          if (result.value) {
            this.logout();
          }
        });
      }
    },

    async logout() {
      await axios.post("/logout");
      document.location.href = "/";
    },
  },

  created() {
    this.checkToken();
  },

  mounted() {
    this.loadData();
  },
};
</script>
