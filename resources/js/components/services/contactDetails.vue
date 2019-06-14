<template>
  <div>
    <b-container>
      <b-row>
        <b-col></b-col>
        <b-col>
          <div class="loader">
            <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50"/>
          </div>
        </b-col>
        <b-col></b-col>
      </b-row>
    </b-container>
    <div class="container" v-if="user && history">
      <b-table striped hover :items="history" :fields="fields"></b-table>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      userId: this.$route.params.id,
      user: null,
      history: null,
      nextContact: null,
      loading: true,
      fields: [
        {
          key: "description",
          label: "Descrição",
          sortable: true
        },
        {
          key: "date",
          label: "Data",
          sortable: true
        }
      ]
    };
  },
  created() {
    this.getUser();
    this.getHistory();
  },
  methods: {
    getUser() {
      axios
        .get("api/getUser/" + this.userId)
        .then(response => {
          this.user = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getHistory() {
      axios
        .get("api/getHistory/" + this.userId)
        .then(response => {
          console.log(response.data);
          this.history = response.data;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
        });
    },
    changeNextContact() {
      let contactDate = {
        nextContact: this.nextContact
      };
      axios
        .post("api/changeNextContact/" + this.contact[0].id, contactDate)
        .then(response => {
          this.getContact();
          this.$toasted.success(
            "Alteração da próxima interação feita com sucesso.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(
            "Erro ao alterar a data da próxima interação. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    }
  }
};
</script>

<style>
</style>
