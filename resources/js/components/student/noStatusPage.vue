<template>
  <div class="center">
    <b-container fluid>
      <b-row>
        <b-col>
          <h2>{{$t('bem_vindo')}}</h2>
            <br>
            <b v-if="isExpired">{{$t('mensagem_renovar_estatuto')}}</b>
            <br>
            <div v-if="student.directorComment">
                <b>{{$t('msg_diretor_estatuto_rejeitado')}}</b>
                <br>
                <span class="jumbotron-fluid">{{student.directorComment}}</span>
            </div>
            <br>
          <a v-if="isExpired" class="btn btn-secondary" v-on:click.prevent="subscribe()" href>{{$t('renovar_estatuto')}}</a>
          <a v-else class="btn btn-secondary" v-on:click.prevent="subscribe()" href>{{$t('pedir_estatuto')}}</a>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
    import moment from 'moment';

    export default {
  data() {
    return {
        isExpired:null,
    };
  },
  methods: {
    subscribe() {
      this.$router.push("/subscription");
    },
  },
    mounted() {
      this.isExpired = this.student.enee == "expired";
    },
    computed: {
    student: function() {
        return this.$store.state.user;
    }
    }
};
</script>
<style>
.center {
  text-align: center;
  margin-top: 20%;
}
</style>

