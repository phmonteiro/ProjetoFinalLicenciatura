<template>
  <div>
    <div class="container">
      <h2>Pedidos de parecer</h2>
        <hr>
      <div v-if="requests!=null && requests.length!==0">
          <b-table striped hover :items="requests" :fields="fields">
              <template v-slot:cell(actions)="row">
                  <button
                      class="btn btn-info"
                      v-on:click.prevent="editUser(row.item)"
                      v-if="row.item.enee!='approved'"
                  >Avaliar</button>
                  <button
                      class="btn btn-danger"
                      v-on:click.prevent="deny(row.item.id)"
                      v-if="row.item.number != user.number"
                  >Rejeitar</button>
              </template>
          </b-table>
      </div>
      <div v-else>
          <br>
          <h4>Não existem pedidos de parecer pendentes.</h4>
      </div>

    </div>
      <eneeServiceEvaluation
          :user="currentUser"
          :nee="nee"
          @approve="approve"
          @cancel-edit="cancelEdit()"
      ></eneeServiceEvaluation>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pagination: {},
      loading: true,
      requests: null,
      fields: [
        {
          key: "name",
          label: "Nome",
          sortable: true
        },
        {
          key: "email",
          label: "Email",
          sortable: true
        },
        {
          key: "course",
          label: "Curso",
          sortable: true
        },
        {
          key: "school",
          label: "Escola",
          sortable: true
        },
        {
          key: "actions",
          label: "Ações Utilizador"
        }
      ],
      currentUser: null,
      // supportsForStudent: null,
      nee: null
    };
  },
  methods: {
    editUser(row) {
      this.currentUser = Object.assign({}, row);
      axios
        .get("api/getNee/" + row.id)
        .then(response => {
          this.nee = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getRequests() {
      axios
        .get("api/getServicesRequests", this.user.type)
        .then(response => {
          this.loading = false;
          console.log(response.data);
          this.requests = response.data;

          if(this.requests==[]){
              this.requests=null;
          }
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },
    approve(userId,information) {
      axios
        .patch("api/approveEneeStatusByServices/" + userId,{'information':information})
        .then(response => {
          this.getRequests();
          this.currentUser = null;
          this.$toasted.success("Submetido com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error("Erro ao guardar. Por favor tente novamente.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        });
    },
    deny(userId) {
      axios
        .patch("api/denyEneeStatusByServices/" + userId)
        .then(response => {
          this.getRequests();
          this.currentUser = null;
          this.$toasted.success("Submetido com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error("Erro ao guardar. Por favor tente novamente.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        });
    },
    cancelEdit: function() {
      this.currentUser = null;
    }
  },
  created() {
    this.getRequests();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>
