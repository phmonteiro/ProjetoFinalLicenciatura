<template>
  <div>
    <eneeServiceEvaluation
      :user="currentUser"
      :nee="nee"
      @approve="approve"
      @cancel-edit="cancelEdit()"
    ></eneeServiceEvaluation>
    <div class="container">
      <h2>Lista de aprovação Enee</h2>
        <br>
      <b-table striped hover v-if="requests.supportsForStudent" :items="requests" :fields="fields">
        <template slot="actions" slot-scope="row">
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
      <h4 v-else>Não existem pedidos de parecer pendentes.</h4>
    </div>
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
      supportsForStudent: null,
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
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },
    approve(userId) {
      axios
        .patch("api/approveEneeStatusByServices/" + userId)
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
