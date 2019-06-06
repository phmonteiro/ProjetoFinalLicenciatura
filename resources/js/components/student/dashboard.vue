<template>
  <div v-if="student!=null">
    <div v-if="(student.enee!='awaiting' && student.enee!='reproved')">
      <div class="sidenav">
        <a href="#">
          <router-link class="nav-link" :to="{name: 'contact'}">Contactos</router-link>
        </a>
        <a href="#">
          <router-link class="nav-link" :to="{name: 'myMeetings'}">Reuniões</router-link>
        </a>
        <a href="#">
          <router-link class="nav-link" :to="{name: 'setMeeting'}">Agendar reunião</router-link>
        </a>

        <a href="#">
          <router-link class="nav-link" :to="{name: 'usedServices'}">Apoios usufruidos</router-link>
        </a>
        <a href="#">
          <router-link class="nav-link" :to="{name: 'serviceRequest'}">Pedido de apoios</router-link>
        </a>
        <a href="#">
          <router-link class="nav-link" :to="{name: 'editProfile'}">Editar perfil</router-link>
        </a>
        <a href="#">
          <router-link class="nav-link" :to="{name: 'supportHours'}">Acompanhamento</router-link>
        </a>
      </div>
      <router-view></router-view>
    </div>
    <div v-else>
      <h1>O seu pedido ao estatuto de estudante com necessidades educativas especais está no estado de:</h1>
      <h1 v-if="student.enee=='reproved'">Reprovado</h1>
      <h1 v-if="student.enee=='awaiting'">A aguardar aprovação</h1>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {};
  },
  methods: {},
  computed: {
    student: function() {
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
  margin-left: 140px; /* Same width as the sidebar + left position in px */
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

