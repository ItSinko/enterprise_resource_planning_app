<template>
  <div>
    <Jadwal :status="'pelaksanaan'" />
  </div>
</template>

<script>
import axios from "axios";
import Jadwal from "./Jadwal.vue";
import $ from "jquery";


export default {
  name: "JadwalPelaksanaan",

  components: {
    Jadwal,
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);

      await axios
        .get("/api/ppic/data/perakitan/pelaksanaan", {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
            }
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data.data);
          this.$store.commit("setIsLoading", false);
          this.$store.commit("setStatus", "pelaksanaan");
        });

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
    this.loadData();
    this.checkToken();
  },

  computed: {
    notif() {
      return this.$store.state.notif;
    },
  },

  watch: {
    notif() {
      if (this.$store.state.notif.user.divisi_id === 3) this.loadData();
    },
  },
};
</script>
