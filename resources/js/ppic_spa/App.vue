<template>
  <div>
    <Navbar />
    <Sidebar />

    <div
      class="is-loading-bar has-text-centered"
      v-bind:class="{ 'is-loading': $store.state.isLoading }"
    >
      <div class="lds-dual-ring"></div>
    </div>

    <section class="section">
      <router-view />
    </section>

    <footer class="footer">
      <p class="has-text-centered">
        Copyright by IT team Sinko Prima Alloy {{ yearNow }}
      </p>
    </footer>
  </div>
</template>

<script>
import Navbar from "./components/Navbar.vue";
import Sidebar from "./components/Sidebar.vue";

import axios from "axios";

export default {
  components: {
    Navbar,
    Sidebar,
  },

  beforeCreate() {
    axios.get("/api/user").then((response) => {
      this.$store.commit("setUser", response.data);
    });
  },

  computed: {
    yearNow() {
      return new Date().getFullYear();
    },
  },
};
</script>

<style lang="scss">
@import "~bulma";
@import "~sweetalert2/src/sweetalert2";

/* loading animation */
.lds-dual-ring {
  display: inline-block;
  width: 80px;
  height: 80px;
}
.lds-dual-ring:after {
  content: " ";
  display: block;
  width: 64px;
  height: 64px;
  margin: 8px;
  border-radius: 50%;
  border: 6px solid #ccc;
  border-color: #ccc transparent #ccc transparent;
  animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.is-loading-bar {
  height: 0;
  overflow: hidden;

  -webkit-transition: all 0.3s;
  transition: all 0.3s;

  &.is-loading {
    height: 80px;
  }
}
/* loading animation */

/* costum */
.menu-list a {
  color: #fff;
}

#calendar-component a {
  color: #000;
}

.modal-card-title {
  font-size: 1rem;
}

@media screen and (min-width: 769px) {
  .modal-content,
  .modal-card {
    width: 900px;
  }
}
/* costum */
</style>
