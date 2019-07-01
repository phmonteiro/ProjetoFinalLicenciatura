<template>
  <div>
    <b-navbar id="navbar" toggleable="lg">
      <b-navbar-brand href="/">
        <img
          alt="Logotipo do Instituto Politécnico de Leiria"
          width="175"
          heigth="75"
          src="/imagens/logo-ipl.png"
        />
      </b-navbar-brand>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav class="ml-auto">
          <h1 id="logout" v-if="user">{{user.name}}</h1>
          <a class="nav-link" href="#" v-on:click.prevent="logout()">Logout</a>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
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
<style>
.navbar-nav {
  text-align: right !important;
}

#logout {
  font-size: 15px !important;
  font-weight: normal !important;
  padding-top: 10px !important;
}
</style>
