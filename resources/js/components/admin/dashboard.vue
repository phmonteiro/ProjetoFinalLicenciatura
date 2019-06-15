<template>
  <div>
    <div class="container pt-3">
      <h4 class="welcome-msg">Bem vindo, {{user.name}}</h4>
      <h3>Sou Admin</h3>
      <button @click="getTeacher">Teachers</button>
    </div>

    <div class="sidenav">
      <a href="#">
        <router-link class="nav-link" :to="{name: 'editUsers'}">Editar Utilizadores</router-link>
      </a>

      <a href="#">
        <router-link class="nav-link" :to="{name: 'manageSupports'}">Gerir Apoios</router-link>
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
  methods: {
    getTeacher() {
      axios
        .get("api/getTeachers")
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error);
        });
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
