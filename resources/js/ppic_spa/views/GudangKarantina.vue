<template>
  <div>
    <h1 class="title">Data Gudang Karantina</h1>
    <div class="columns is-multiline">
      <div class="column is-12">
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
            class="table is-fullwidth"
            id="table-sparepart"
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
              <tr v-for="item in data_sparepart" :key="item.id">
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
          <table class="table is-fullwidth" id="table-unit">
            <thead>
              <tr>
                <th>Kode Unit</th>
                <th>Nama</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in data_unit" :key="item.id">
                <td>{{ item.kode_produk }}</td>
                <td>{{ item.nama_produk }}</td>
                <td>{{ item.jumlah }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import axios from "axios";

/**
 * @vue-data {Boolean} [tabs=false] - flag for switch tabs unit or sparepart
 * @vue-data {Array} [data_sparepart=[]] - array to store data sparepart gk that getted from API (url = '/api/ppic/data/gk/sparepart')
 * @vue-data {Array} [data_sparepart=[]] - array to store data unit gk that getted from API (url = '/api/ppic/data/gk/unit')
 *
 * @vue-event {Array} loadData - function to initialized data unit and sparepart when this component rendered
 */

export default {
  name: "GudangKarantina",

  data() {
    return {
      tabs: false,
      data_sparepart: [],
      data_unit: [],
    };
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      await axios.get("/api/ppic/data/gk/sparepart",{
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
            }
        }).then((response) => {
        this.data_sparepart = response.data;
      });
      $("#table-sparepart").DataTable();

      await axios.get("/api/ppic/data/gk/unit",{
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
            }
        }).then((response) => {
        this.data_unit = response.data.data;
      });

      $("#table-unit").DataTable();
      this.$store.commit("setIsLoading", false);
    },

    checkToken(){
        if(localStorage.getItem('lokal_token') == null){
            // event.preventDefault();
            this.$swal({
                title: 'Session Expired',
                text: 'Silahkan login kembali',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    this.logout()
                }
            })
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
