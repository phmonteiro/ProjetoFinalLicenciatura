<template>
  <div>
    <div class="container pt-3">
      <h4 class="welcome-msg">Bem vindo, {{user.name}}</h4>
      <h3>Sou dos servicos</h3>
    </div>

    <div class="sidenav">
      <a href="#">
        <router-link class="nav-link" :to="{name: 'meetings'}">Pedidos reuniao</router-link>
      </a>
      <a href="#">
        <router-link class="nav-link" :to="{name: 'eneeList'}">Lista Enee</router-link>
      </a>
      <a href="#">
        <router-link class="nav-link" :to="{name: 'eneeOpinion'}">Parecer ENEE</router-link>
      </a>
      <a href="#" v-if="user.type == 'SA'">
        <router-link class="nav-link" :to="{name: 'eneeAdd'}">Adicionar Estudante</router-link>
      </a>
    </div>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
  methods: {},
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>

<style>
</style>
