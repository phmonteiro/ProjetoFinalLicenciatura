<template>
  <div v-if="user.type=='Administrador'">
    <b-container>
      <b-row>
        <section id="sidebar-menu">
          <a href="#" id="btn-menu" @click.prevent="toggleNav()">
            <font-awesome-icon icon="bars" size="lg" />
          </a>
          <div class="sidenav" :class="{ active: active}">
            <router-link class="nav-link" :to="{name: 'editUsers'}">Editar Tipo de Utilizadores</router-link>

            <router-link class="nav-link" :to="{name: 'addResponsibleCM'}">Adicionar Responsável de Gestores de Caso</router-link>

            <router-link class="nav-link" :to="{name: 'manageSupports'}">Gerir Apoios</router-link>

            <router-link class="nav-link" :to="{name: 'definirHorasApoio'}">Definir Horas de Apoio</router-link>

            <router-link
              class="nav-link"
              :to="{name: 'addCoordinator'}"
            >Adicionar Coordenador de curso</router-link>
          </div>
        </section>
        <b-col cols="12">
          <div class="container pt-3">
            <h4 class="welcome-msg">Bem vindo, {{user.name}}</h4>
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
