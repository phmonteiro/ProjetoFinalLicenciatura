<template>
  <div>
    <div class="container pt-3">
      <h4 class="welcome-msg">Bem vindo, {{user.name}}</h4>
      <h3>Sou dos servicos</h3>
    </div>

    <section id="sidebar-menu">
        <a href="#" id="btn-menu" @click.prevent="toggleNav()">
          <font-awesome-icon icon="bars" size="lg"/>
        </a>
        <div class="sidenav" :class="{ active: active}">
        <router-link class="nav-link" :to="{name: 'meetings'}">Pedidos reuniao</router-link>
        
        <router-link class="nav-link" :to="{name: 'eneeList'}">Lista Enee</router-link>
        
        <router-link class="nav-link" :to="{name: 'eneeOpinion'}">Parecer ENEE</router-link>
        
        <router-link class="nav-link" :to="{name: 'eneeAdd'}">Adicionar Estudante</router-link>
        
    </div>
    </section>
    <router-view></router-view>
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
    toggleNav(){
      this.active = !this.active;
      console.log(this.active);
      
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
