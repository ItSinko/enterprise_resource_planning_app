<template>
  <div>
    <Jadwal :status="'penyusunan'" /> 
  </div>
</template>

<script>
import axios from "axios";
import Jadwal from "./Jadwal.vue";

export default {
  name: "JadwalPerencanaan",

  components: {
    Jadwal,
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);

      await axios
        .get("/api/ppic/data/perakitan/penyusunan",{
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
            }
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data.data);
        });

      this.$store.commit("setStatus", "perencanaan");

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
