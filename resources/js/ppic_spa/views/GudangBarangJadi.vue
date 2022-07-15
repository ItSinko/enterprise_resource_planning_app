<template>
  <div>
    <h1 class="title">Data Gudang Barang Jadi</h1>
    <div class="columns is-multiline">
      <div class="column is-12">
        <table class="table is-fullwidth has-text-centered" id="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode Produk</th>
              <th>Nama Produk</th>
              <th>Stok</th>
              <th>Kelompok</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(d, index) in data" :key="d.id">
              <td>{{ index + 1 }}</td>
              <td>-</td>
              <td>{{ d.produkk }}</td>
              <td>{{ d.stok }}</td>
              <td>{{ d.kel_produk }}</td>
              <td>
                <button
                  class="button is-small is-light is-info"
                  @click="getDetail(d.id)"
                >
                  <i class="far fa-eye"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- modal -->
    <div v-if="showModal" class="modal" :class="{ 'is-active': showModal }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">
            Detail Produk <strong>{{ detail.data[0].nama }}</strong>
          </p>
          <button
            class="delete"
            aria-label="close"
            @click="showModal = !showModal"
          ></button>
        </header>
        <section class="modal-card-body">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div class="card">
                <img
                  :src="
                    'http://localhost:8000/upload/gbj/' + detail.data[0].gambar
                  "
                  alt="not found"
                />
              </div>
            </div>
            <div class="column is-6">
              <p><b>Nama Produk</b></p>
              <p>{{ detail.data[0].nama }}</p>
              <p><b>Deskripsi Produk</b></p>
              <p>{{ detail.data[0].deskripsi }}</p>
              <p><b>Dimensi</b></p>
              <div class="columns is-multiline">
                <div class="column">Panjang</div>
                <div class="column">Lebar</div>
                <div class="column">Tinggi</div>
              </div>
              <div class="columns is-multiline">
                <div class="column">
                  <span>{{ detail.data[0].dim_p }}</span>
                </div>
                <div class="column">
                  <span>{{ detail.data[0].dim_l }}</span>
                </div>
                <div class="column">
                  <span>{{ detail.data[0].dim_t }}</span>
                </div>
              </div>
              <p><b>Produk</b></p>
              <p>
                {{
                  detail.nama_produk[0].product.nama +
                  " " +
                  detail.nama_produk[0].nama
                }}
              </p>
            </div>
          </div>
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
 * @vue-data {Array} [data=[]] - this data store list of gbj products getted from API (url = '/api/ppic/data/gbj')
 * @vue-data {Object} [detail={}] - this data store product detail that shown in modal when detail button clicked
 * @vue-data {Boolean} [showModal=false] - this data store flag for show or hide modal detail product
 *
 * @vue-event {Array} loadData - function for initialized list of gbj products
 * @vue-event {Object} getDetail - this function calling API to get product detail by id
 */

export default {
  name: "GudangBarangJadi",

  data() {
    return {
      data: [],
      detail: {},
      showModal: false,
    };
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      await axios.get("/api/ppic/data/gbj").then((response) => {
        this.data = response.data;
      });
      this.$store.commit("setIsLoading", false);

      let table = $("#table").DataTable();
    },

    async getDetail(id) {
      this.$store.commit("setIsLoading", true);
      await axios
        .post("/api/gbj/get", {
          id: id,
        })
        .then((response) => {
          this.detail = response.data;
          this.showModal = true;
        });
      this.$store.commit("setIsLoading", false);
    },
  },

  mounted() {
    this.loadData();
  },
};
</script>
