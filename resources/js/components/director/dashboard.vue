<template>
  <div>
    <b-container>
      <b-row>
        <section id="sidebar-menu">
          <a href="#" id="btn-menu" @click.prevent="toggleNav()">
            <font-awesome-icon icon="bars" size="lg" />
          </a>
          <div class="sidenav" :class="{ active: active}">
            <router-link class="nav-link" :to="{name: 'manageRequestENEE'}">Candidatos a ENEE</router-link>

            <router-link class="nav-link" :to="{name: 'manageENEE'}">ENEE</router-link>

            <router-link class="nav-link" :to="{name: 'supportRequests'}">Pedidos de Apoio</router-link>
          </div>
        </section>
        <b-col cols="12">
          <div class="container pt-3 content">
            <h4 class="welcome-msg">Bem vindo, {{user.name}}</h4>
            <h3>Sou Diretor</h3>
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

<style>
.sidenav {
  width: 130px;
  position: fixed;
  z-index: 1;
  top: 90px;
  left: 10px;
  background: #6c757d;
  overflow-x: hidden;
  padding: 8px 0;
}

.sidenav a {
  padding: 6px 8px 6px 16px;
  text-decoration: none;
  font-size: 16px;
  color: white;
  display: block;
}

.sidenav a:hover {
  color: black;
}

.main {
  margin-left: 140px;
  /* Same width as the sidebar + left position in px */
  padding: 0px;
}

@media screen and (max-height: 450px) {
  .sidenav {
    padding-top: 15px;
  }

  .sidenav a {
    font-size: 18px;
  }
}
</style>
