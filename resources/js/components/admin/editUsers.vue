<template>
  <div>
    <div class="loader">
      <ClipLoader sizeUnit="px" v-if="loading" :size="50"/>
    </div>
    <div class="container" v-if="users">
      <edit-user :user="currentUser" @save-user="saveUser()" @cancel-edit="cancelEdit()"></edit-user>
      <div class="container">
        <h2>Lista de utilizadores</h2>
        <b-table striped hover v-if="users!=null" :items="users" :fields="fields">
          <template slot="type" slot-scope="row">
            <p v-if="row.item.type=='Services'">Serviços</p>
            <p v-if="row.item.type=='CaseManagerResponsible'">Responsável Gestor de Caso</p>
            <p v-if="row.item.type=='CaseManager'">Gestor de Caso</p>
            <p v-if="row.item.type=='Coordinator'">Coordenador de Curso</p>
            <p v-if="row.item.type=='Director'">Diretor</p>
            <p v-if="row.item.type=='Estudante'">Estudante</p>
            <p v-if="row.item.type=='Administrador'">Administrador</p>
            <p v-if="row.item.type=='SAPE'">Serviços de Apoio ao Estudante</p>
            <p v-if="row.item.type=='CRIDE'">Centro de Recursos para a Inclusão Digital</p>
            <p v-if="row.item.type=='SAS'">Serviços de Ação Social</p>
            <p v-if="row.item.type=='DST'">Direção de Serviços Técnicos</p>
            <p v-if="row.item.type=='UED'">Unidade de Ensino à Distância</p>
          </template>
          <template slot="actions" slot-scope="row">
            <button
              class="btn btn-secondary"
              v-on:click.prevent="editUser(row.item)"
              v-if="row.item.number != user.number"
            >Editar utilizador</button>
          </template>
        </b-table>
        <nav aria-label="Page navigation" v-if="users">
          <ul class="pagination">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
              <a
                class="page-link"
                href="#"
                @click.prevent="getUsers(pagination.prev_page_url)"
              >Anterior</a>
            </li>

            <li class="page-item disabled">
              <a class="page-link text-dark" href="#">
                Página {{ pagination.current_page }} de
                {{ pagination.last_page }}
              </a>
            </li>

            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
              <a
                class="page-link"
                href="#"
                @click.prevent="getUsers(pagination.next_page_url)"
              >Próximo</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pagination: {},
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
    getUsers(page_url) {
      let pg = this;
      page_url = page_url || "api/getUsers?page=1";
      axios
        .get(page_url)
        .then(response => {
          this.loading = false;
          this.users = response.data.data;
          pg.makePagination(response.data.meta, response.data.links);
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },
    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };
      this.pagination = pagination;
    },
    editUser(row) {
      console.log(row);
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