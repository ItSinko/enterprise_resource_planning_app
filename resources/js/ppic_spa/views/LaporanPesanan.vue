<template>
  <div>
    <div class="is-flex is-justify-content-space-between">
      <h1 class="title">Laporan Pesanan</h1>
    </div>
    <div class="columns is-multiline">
      <div class="column is-12">
        <table class="table is-fullwidth has-text-centered" id="table_so">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Produk</th>
                    <th colspan="2">Stok</th>
                    <th colspan="6">Penjualan</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>GBJ</th>
                    <th>Sisa</th>
                    <th>Permintaan</th>
                    <th>Sepakat</th>
                    <th>Nego</th>
                    <th>Batal</th>
                    <th>Draft</th>
                    <th>PO</th>
                </tr>
            </thead>
          <tbody>
            <tr v-for="item in data" :key="item.id">
              <td>{{ item.DT_RowIndex }}</td>
              <td v-html="item.nama_produk"></td>
              <td>{{ item.stok }}</td>
              <td v-html="item.penjualan"></td>
              <td>{{ item.total }}</td>
              <td>{{ item.sepakat }}</td>
              <td>{{ item.nego }}</td>
              <td>{{ item.batal }}</td>
              <td>{{ item.draft }}</td>
              <td>{{ item.po }}</td>
              <td>
                <button
                  class="button is-light"
                  @click="getDetail(item.id, item.nama_produk)"
                >
                  <i class="fas fa-search"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- modal -->
    <div class="modal" :class="{ 'is-active': showModal }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title" v-html="nama_produk"></p>
          <button
            class="delete"
            aria-label="close"
            @click="showModal = false"
          ></button>
        </header>
        <section class="modal-card-body">
          <table class="table is-fullwidth" id="detailtable">
            <thead>
              <tr>
                <th>SO</th>
                <th>Tanggal order</th>
                <th>Tanggal pengiriman</th>
                <th>Jumlah</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in detail" :key="item.id">
                <td v-html="item.so"></td>
                <td v-html="item.tgl_order"></td>
                <td v-html="item.tgl_delivery"></td>
                <td v-html="item.jumlah"></td>
                <td v-html="item.status"></td>
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
import $ from "jquery";
import axios from "axios";

/**
 * @vue-data {Array} [data=[]] - Array to store data sales order get from API (url = '/api/ppic/data/so')
 * @vue-data {Object} [detail={}] - Object to store detail sales order when detail button clicked
 * @vue-data {String} [nama_produk=""] - variable to store product name that use as header modal of detail sales order
 * @vue-data {Boolean} [showModal=false] - flag used to show or hide detail sales order modal
 *
 * @vue-event {Array} loadData - this function is used to initialized data by calling APIs
 * @vue-event {Object} getDetail - function to get product sales order detail and get product name
 */

export default {
  name: "SalesOrder",

  data() {
    return {
      data: [],
      detail: {},
      nama_produk: "",

      showModal: false,
    };
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      await axios.post("/api/ppic/master_stok/data").then((response) => {
        this.data = response.data.data;
      });
      $("#table_so").DataTable();

      this.$store.commit("setIsLoading", false);
    },

    async getDetail(id, nama) {
      this.$store.commit("setIsLoading", true);
      await axios.post("/api/ppic/master_stok/detail/" + id).then((response) => {
        this.detail = response.data.data;
      });
      $("#detailtable").DataTable();
      this.$store.commit("setIsLoading", false);

      this.nama_produk = nama;

      this.showModal = true;
    },
  },

  mounted() {
    this.loadData();
  },
};
</script>
