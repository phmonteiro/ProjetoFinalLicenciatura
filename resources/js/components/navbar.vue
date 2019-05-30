<template>
  <nav id="navbar" class="navbar navbar-expand-md navbar-light navbar-laravel">
    <a class="navbar-brand">
      <img src="/imagens/logo-ipl.png">
    </a>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav mr-auto"></ul>

      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav ml-auto">
        <!-- Authentication Links -->
        <li class="nav-item">
          <a class="nav-link" v-if="!user" href>Login</a>
          <a class="nav-link" v-else>{{user.name}}</a>
        </li>
        <li class="nav-item dropdown">
          <a
            id="navbarDropdown"
            class="nav-link dropdown-toggle"
            href="#"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            v-pre
          >
            <span class="caret"></span>
          </a>

          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" v-on:click.prevent="logout()">Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</template>
<script>
export default {
  methods: {
    logout() {
      this.$store.commit("clearUserAndToken");
      this.$router.push("/");
    }
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>
