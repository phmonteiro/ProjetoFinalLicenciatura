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
      <b-table striped hover v-if="requests!=null" :items="requests" :fields="fields">
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
      <nav aria-label="Page navigation" v-if="requests">
        <ul class="pagination">
          <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
            <a
              class="page-link"
              href="#"
              @click.prevent="getRequests(pagination.prev_page_url)"
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
              @click.prevent="getRequests(pagination.next_page_url)"
            >Próximo</a>
          </li>
        </ul>
      </nav>
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
    getRequests(page_url) {
      let pg = this;
      page_url = page_url || "api/getServicesRequests?page=1";
      axios
        .get(page_url, this.user.type)
        .then(response => {
          this.loading = false;
          this.requests = response.data.data;
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
    approve(userId) {
      axios
        .post("api/approveEneeStatusByServices/" + userId)
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
        .post("api/denyEneeStatusByServices/" + userId)
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
