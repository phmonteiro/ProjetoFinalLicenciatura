<template>
  <div v-if="user.type=='CaseManager'">
    <b-container>
      <b-row>
        <section id="sidebar-menu">
          <a href="#" id="btn-menu" @click.prevent="toggleNav()">
            <font-awesome-icon icon="bars" size="lg" />
          </a>
          <div class="sidenav" :class="{ active: active}">
            <router-link class="nav-link" :to="{name: 'cmEneeList'}">Meus ENE</router-link>
            <router-link class="nav-link" :to="{name: 'meetingsEneeList'}">Reuniões</router-link>
            <router-link class="nav-link" :to="{name: 'supportRequests'}">Pedidos de Apoio</router-link>
            <router-link class="nav-link" :to="{name: 'calendar'}">Calendário</router-link>
            <router-link class="nav-link" :to="{name: 'substitution'}">Substituição</router-link>
            <router-link class="nav-link" :to="{name: 'statistics'}">Estatísticas</router-link>
          </div>
        </section>
        <b-col cols="12" class="ml-120">
          <div class="container pt-3">
            <h4 class="welcome-msg">Bem vindo, {{user.name}}</h4><br>
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
      currentUser: null,
      active: true
    };
  },
  methods: {
    toggleNav() {
      this.active = !this.active;
      console.log(this.active);
    }
  },
  created() {},
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>
