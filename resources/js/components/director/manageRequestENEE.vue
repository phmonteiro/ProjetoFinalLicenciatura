<template>
  <div>
    <b-container>
      <b-row>
        <b-col></b-col>
        <b-col>
          <div class="loader">
            <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50" />
          </div>
        </b-col>
        <b-col></b-col>
      </b-row>
    </b-container>
    <b-container>
      <h2>Lista de candidatos a ENE</h2>
      <b-table striped hover v-if="enee!=null" :items="enee" :fields="fields">
        <template v-slot:cell(enee)="{ value }">
          <p v-if="value==='awaiting'">A aguardar</p>
          <p v-if="value==='denied'">Reprovado</p>
          <p v-if="value==='approved'">Aprovado</p>
        </template>
        <template v-slot:cell(actions)="row">
          <b-row class="text-center">
            <b-col sm="12">
              <button
                class="btn btn-info m-1"
                v-on:click.prevent="editUser(row.item)"
                v-if="row.item.enee!=='denied'"
              >Avaliar</button>
            </b-col>
            <b-col sm="12">
              <button
                class="btn btn-danger m-1"
                v-on:click.prevent="reprovedSubscription(row.item)"
                v-if="row.item.enee!='reproved'"
              >Reprovar</button>
            </b-col>
          </b-row>
        </template>
      </b-table>
        <h4 v-else>Não existem pedidos de candidatura ao estatuto ENE</h4>

      <nav aria-label="Page navigation" v-if="enee">
        <ul class="pagination">
          <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
            <a
              class="page-link"
              href="#"
              @click.prevent="getEnee(pagination.prev_page_url)"
            >Anterior</a>
          </li>

          <li class="page-item disabled">
            <a class="page-link text-dark" href="#">
              Página {{ pagination.current_page }} de
              {{ pagination.last_page }}
            </a>
          </li>

          <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
            <a class="page-link" href="#" @click.prevent="getEnee(pagination.next_page_url)">Próximo</a>
          </li>
        </ul>
      </nav>
    </b-container>
      <eneeOptions
          v-if="currentUser"
          :user="currentUser"
          :teachers="teachers"
          :nee="nee"
          @refresh="getEnee"
          @save-user="saveUser"
          @cancel-edit="cancelEdit"
      ></eneeOptions>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pagination: {},
      loading: true,
      enee: null,
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
          key: "number",
          label: "Número",
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
          key: "enee",
          label: "Estatuto NEE",
          sortable: true
        },
        {
          key: "actions",
          label: "Ações Utilizador"
        }
      ],
      currentUser: null,
      supportsForStudent: null,
      nee: null,
      teachers: null,
    };
  },
  methods: {
    getEnee(page_url) {
      let pg = this;
      page_url = page_url || "api/getEnee?page=1";
      axios
        .get(page_url)
        .then(response => {
          this.loading = false;
          this.enee = response.data.data;
          pg.makePagination(response.data.meta, response.data.links);
          this.loading = false;
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
      this.currentUser = Object.assign({}, row);

      let user = row.id;
      axios
        .get("api/getNee/" + row.id)
        .then(response => {
          this.getTeachersStudent(user);
          this.nee = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getTeachersStudent(user) {
        console.log("api/getTeachersStudent/"+user)
      axios
        .get("api/getTeachersStudent/" + user)
        .then(response => {
          console.log(response.data);

          this.teachers = response.data;
        })
        .catch(error => {console.log(error)});
    },
    cancelEdit: function() {
      this.currentUser = null;
    },
    reprovedSubscription(row) {
      axios
        .patch("api/reproveSubscription/" + row.id)
        .then(response => {
          this.getEnee();
          this.$toasted.success("Candidatura reprovada.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(
            "Erro ao rejeitar candidatura. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    },
    saveUser(data) {
      axios
        .put("api/updateStudentSupports/", data)
        .then(response => {
          this.getEnee();
          this.currentUser = null;
          this.$toasted.success("Estudante aprovado com sucesso.", {
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

  },
  created() {
    this.getEnee();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>
