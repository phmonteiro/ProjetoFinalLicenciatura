<template>
  <div
    v-if="user.type == 'Services' || user.type == 'SAPE' ||user.type == 'SAS' ||user.type == 'CRID' ||user.type == 'UED' ||user.type == 'DST' ||user.type == 'SA'"
  >
    <b-container>
      <b-row>
        <section id="sidebar-menu">
          <a href="#" id="btn-menu" @click.prevent="toggleNav()">
            <font-awesome-icon icon="bars" size="lg" />
          </a>
          <div class="sidenav" :class="{ active: active}">
            <router-link class="nav-link" :to="{name: 'eneeList'}">Lista Enee</router-link>

            <router-link class="nav-link" :to="{name: 'eneeOpinion'}">Parecer ENEE</router-link>

            <router-link v-if="user.type == 'SA'" class="nav-link" :to="{name: 'eneeAdd'}">
              Adicionar
              Estudante
            </router-link>
          </div>
        </section>
        <b-col cols="12">
          <div class="container pt-3">
            <h4 class="welcome-msg">Bem vindo, {{user.name}}</h4>
            <!-- <h3>Sou dos servicos</h3> -->
          </div>
          <router-view></router-view>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      active: true
    };
  },
  methods: {
    toggleNav() {
      this.active = !this.active;
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
</style>
