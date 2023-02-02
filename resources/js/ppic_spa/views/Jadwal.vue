<template>
  <div>
    <h1 v-if="this.status === 'penyusunan'" class="title">
      Perencanaan Jadwal Perakitan
    </h1>
    <h1 v-if="this.status === 'pelaksanaan'" class="title">
      Pelaksanaan Jadwal Perakitan
    </h1>

    <div
      v-if="this.$store.state.state_ppic === 'pembuatan'"
      class="notification is-primary"
    >
      Penyusunan jadwal perakitan
    </div>
    <div
      v-else-if="this.$store.state.state_ppic === 'menunggu'"
      class="notification is-warning"
    >
      Menunggu persetujuan manajer
    </div>
    <div
      v-else-if="this.$store.state.state_ppic === 'disetujui'"
      class="notification is-success"
    >
      Jadwal telah disetujui
    </div>
    <div
      v-else-if="this.$store.state.state_ppic === 'revisi'"
      class="notification is-danger"
    >
      Jadwal harus direvisi
    </div>

    <div class="tabs is-centered">
      <ul>
        <li :class="{ 'is-active': view === 'table' }" @click="view = 'table'">
          <a>
            <span class="icon is-small"
              ><i class="fas fa-table" aria-hidden="true"></i
            ></span>
            <span>Tabel</span>
          </a>
        </li>
        <li
          :class="{ 'is-active': view === 'calendar' }"
          @click="view = 'calendar'"
        >
          <a>
            <span class="icon is-small"
              ><i class="fas fa-calendar" aria-hidden="true"></i
            ></span>
            <span>Kalender</span>
          </a>
        </li>
        <li :class="{ 'is-active': view === 'chart' }" @click="view = 'chart'">
          <a>
            <span class="icon is-small"
              ><i class="fas fa-chart-bar" aria-hidden="true"></i
            ></span>
            <span>Chart</span>
          </a>
        </li>
      </ul>
    </div>
    <template v-if="view === 'calendar'">
      <div class="columns">
        <div class="column is-12">
          <div class="box">
            <Calendar :events="events" :status="status" />
          </div>
        </div>
      </div>
      <div class="columns">
        <div class="column is-12">
          <div class="box">
            <div class="table-container">
              <table class="table is-fullwidth" id="table-jadwal">
                <thead>
                  <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in sorting_jadwal" :key="item.id">
                    <td>
                      {{ `${item.produk.produk.nama} ${item.produk.nama}` }}
                    </td>
                    <td>{{ item.jumlah }}</td>
                    <td>{{ item.tanggal_mulai }}</td>
                    <td>{{ item.tanggal_selesai }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-if="view === 'chart'">
      <div class="columns">
        <div class="column is-12">
          <div class="box">
            <Chart :events="events" :status="status" />
          </div>
        </div>
      </div>
    </template>

    <template v-if="view === 'table'">
      <div class="columns">
        <div class="column is-12">
          <div class="box">
            <Table :events="events" :status="status" />
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import $ from "jquery";

import Calendar from "../components/Calendar.vue";
import Table from "../components/Table.vue";
import Chart from "../components/Chart.vue";

import mixins from "../mixins";

/**
 * @vue-prop {String} status - status value
 *
 * @vue-data {String} [view='table'] - string as flag to choose which component to display
 *
 * @vue-computed {Array} sorting_jadwal - sorted array of jadwal from store state which is global data
 * @vue-computed {Array} events - array of data that get from re-structured array of jadwal
 */

export default {
  name: "Jadwal",

  props: {
    status: {
      type: String,
      required: true,
    },
  },

  components: {
    Calendar,
    Chart,
    Table,
  },

  data() {
    return {
      view: "table",
    };
  },

  computed: {
    sorting_jadwal() {
      return this.$store.state.jadwal.sort(
        (a, b) => new Date(a.tanggal_mulai) - new Date(b.tanggal_mulai)
      );
    },
    events() {
      if(this.$store.state.jadwal.length > 0){
        let data = mixins.convertJadwal(this.$store.state.jadwal);
        return data;
      }
      return [];
    },
  },

  updated() {
    if (this.view === "calendar") {
      if (this.sorting_jadwal !== null) {
        $("#table-jadwal").DataTable();
      }
    }
  },
};
</script>
