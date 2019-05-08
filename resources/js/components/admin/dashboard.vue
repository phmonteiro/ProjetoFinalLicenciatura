<template>
  <div>
    <div class="loader">
      <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="150"/>
    </div>
    <div class="container" v-if="users">
      <edit-user :user="currentUser" @save-user="saveUser()" @cancel-edit="cancelEdit()"></edit-user>
      <div class="container">
        <h2>Lista de utilizadores</h2>
        <b-table striped hover v-if="users!=null" :items="users" :fields="fields">
          <template slot="actions" slot-scope="row">
            <button
              class="btn btn-secondary"
              v-on:click.prevent="editUser(row.item)"
              v-if="row.item.number != user.number"
            >Editar utilizador</button>
          </template>
        </b-table>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      loading: true,
      users: null,
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
          key: "type",
          label: "Tipo de utilizador",
          sortable: true
        },
        {
          key: "loginExpirationDate",
          label: "Data expiração login",
          sortable: true
        },
        {
          key: "actions",
          label: "Editar Utilizador"
        }
      ],
      currentUser: null
    };
  },
  methods: {
    getUsers() {
      axios
        .get("api/getUsers/")
        .then(response => {
          this.users = response.data.data;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
        });
    },
    editUser(row) {
      this.currentUser = Object.assign({}, row);
    },
    cancelEdit: function() {
      this.currentUser = null;
    },
    saveUser() {
      axios
        .post("api/editUser/" + this.currentUser.id, this.currentUser)
        .then(response => {
          this.getUsers();
          this.currentUser = null;
          this.$toasted.success("Utilizador editado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(
            "Erro ao editar utilizador. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    }
  },
  created() {
    this.getUsers();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>

