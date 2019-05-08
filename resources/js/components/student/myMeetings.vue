<template>
  <div>
    <div class="loader">
      <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="150"/>
    </div>
    <div v-if="meetings">
      <h2>Pedidos de Agendamento</h2>
      <b-table striped hover v-if="meetings!=null" :items="meetings" :fields="fields"></b-table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      meetings: null,
      loading: true,
      fields: [
        {
          key: "service",
          label: "Serviço",
          sortable: true
        },
        {
          key: "comment",
          label: "Comentário",
          sortable: true
        },
        {
          key: "info",
          label: "Informacao Adicional",
          sortable: true
        },
        {
          key: "date",
          label: "Data",
          sortable: true
        }
      ],
      meeting: {
        info: null,
        date: null
      }
    };
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  },
  methods: {
    getMyMeetings() {
      axios
        .get("api/getMyMeetings/" + this.user.id)
        .then(response => {
          this.meetings = response.data.data;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  created() {
    this.getMyMeetings();
  }
};
</script>

<style>
</style>
