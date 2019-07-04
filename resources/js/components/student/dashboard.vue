<template>
  <div v-if="student!=null">
    <div v-if="(student.enee!='awaiting' && student.enee!='reproved')">
      <b-container>
        <b-row>
          <section id="sidebar-menu">
            <a href="#" id="btn-menu" @click.prevent="toggleNav()">
              <font-awesome-icon icon="bars" size="lg" />
            </a>
            <div class="sidenav" :class="{ active: active}">
              <router-link class="nav-link" :to="{name: 'contact'}">{{ $t('contatos') }}</router-link>

              <router-link class="nav-link" :to="{name: 'myMeetings'}">{{ $t('reuniões') }}</router-link>

              <router-link class="nav-link" :to="{name: 'setMeeting'}">{{ $t('agendar_reunião') }}</router-link>

              <router-link
                class="nav-link"
                :to="{name: 'usedServices'}"
              >{{ $t('apoios_usufruidos') }}</router-link>

              <router-link
                class="nav-link"
                :to="{name: 'serviceRequest'}"
              >{{ $t('pedidos_apoios') }}</router-link>

              <router-link class="nav-link" :to="{name: 'editProfile'}">{{ $t('editar_perfil') }}</router-link>

              <router-link class="nav-link" :to="{name: 'supportHours'}">{{ $t('acompanhamento') }}</router-link>
            </div>
          </section>
          <b-col cols="12">
            <router-view></router-view>
          </b-col>
        </b-row>
      </b-container>
    </div>
    <div class="waiting" v-else>
      <h1>{{ $t('mensagem_estudante') }}</h1>
      <h1 v-if="student.enee=='reproved'">{{ $t('reprovado') }}</h1>
      <h1 v-if="student.enee=='awaiting'">{{ $t('aguardar_aprovação') }}</h1>
    </div>
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
    student: function() {
      return this.$store.state.user;
    }
  }
};
</script>
<style>
.waiting {
  text-align: center;
  margin-top: 20%;
}
</style>
