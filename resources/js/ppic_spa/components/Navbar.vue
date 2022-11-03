<template>
  <nav class="navbar is-fixed-top is-dark">
    <div class="navbar-brand">
      <a class="navbar-item is-hidden-desktop" @click="showSidebar">
        <span class="icon"><i class="fas fa-angle-double-right"></i></span>
      </a>

      <a
        role="button"
        class="navbar-burger"
        aria-label="menu"
        aria-expanded="false"
        data-target="navbarBasicExample"
        @click="showMobileMenu = !showMobileMenu"
      >
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    <div
      class="navbar-menu"
      id="navbarBasicExample"
      :class="{ 'is-active': showMobileMenu }"
    >
      <div class="navbar-end">
        <div class="navbar-item has-dropdown has-user-avatar is-hoverable">
          <a class="navbar-link">
            <div class="is-user-avatar">
              <img
                :src="`/assets/image/user/${$store.state.user.foto}`"
                alt="not found"
              />
            </div>
            <div class="is-user-name">
              <span>{{ $store.state.user.username }}</span>
            </div>
          </a>

          <div class="navbar-dropdown is-right">
            <a class="navbar-item"> Pengaturan </a>
            <a class="navbar-item"> Bantuan </a>
            <hr class="navbar-divider" />
            <a class="navbar-item" @click="logout"> Keluar </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import axios from "axios";

export default {
  name: "Navbar",

  data() {
    return {
      showMobileMenu: false,
    };
  },

  methods: {
    showSidebar(e) {
      var dropdownIcon = e.currentTarget
        .getElementsByClassName("icon")[0]
        .getElementsByClassName("fas")[0];
      document.documentElement.classList.toggle("has-aside-mobile-expanded");
      dropdownIcon.classList.toggle("fa-angle-double-right");
      dropdownIcon.classList.toggle("fa-angle-double-left");
    },

    async logout() {
      await axios.post("/logout");
      document.location.href = "/";
    },
  },
};
</script>

<style lang="scss">
nav.navbar .navbar-item.has-user-avatar .is-user-avatar {
  margin-right: 0.75rem;
  display: inline-flex;
  width: 1.75rem;
  height: 1.75rem;
}

.is-user-avatar img {
  margin: 0 auto;
  border-radius: 290486px;
}
</style>
