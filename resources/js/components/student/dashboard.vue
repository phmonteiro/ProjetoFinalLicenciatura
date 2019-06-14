<template>
  <div v-if="student!=null">
    <div v-if="(student.enee!='awaiting' && student.enee!='reproved')">
      <div class="sidenav">
        <a href="#">
          <router-link class="nav-link" :to="{name: 'contact'}">{{ $t('contatos') }}</router-link>
        </a>
        <a href="#">
          <router-link class="nav-link" :to="{name: 'myMeetings'}">{{ $t('reuniões') }}</router-link>
        </a>
        <a href="#">
          <router-link class="nav-link" :to="{name: 'setMeeting'}">{{ $t('agendar_reunião') }}</router-link>
        </a>
        <a href="#">
          <router-link class="nav-link" :to="{name: 'usedServices'}">{{ $t('apoios_usufruidos') }}</router-link>
        </a>
        <a href="#">
          <router-link class="nav-link" :to="{name: 'serviceRequest'}">{{ $t('pedidos_apoios') }}</router-link>
        </a>
        <a href="#">
          <router-link class="nav-link" :to="{name: 'editProfile'}">{{ $t('editar_perfil') }}</router-link>
        </a>
        <a href="#">
          <router-link class="nav-link" :to="{name: 'supportHours'}">{{ $t('acompanhamento') }}</router-link>
        </a>
      </div>
      <router-view></router-view>
    </div>
    <div v-else>
      <h1>{{ $t('mensagem_estudante') }}</h1>
      <h1 v-if="student.enee=='reproved'">{{ $t('reprovado') }}</h1>
      <h1 v-if="student.enee=='awaiting'">{{ $t('aguardar_aprovação') }}</h1>
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

