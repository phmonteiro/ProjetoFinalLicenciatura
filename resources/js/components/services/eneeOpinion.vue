<template>
  <div>
    <div class="container">
      <h2>Lista de pedidos</h2>
      <b-table striped hover v-if="requests!=null" :items="requests" :fields="fields">
        <template slot="actions" slot-scope="row">
          <button
            type="button"
            class="btn btn-secondary"
            data-toggle="modal"
            data-target="#exampleModal"
          >
            <font-awesome-icon icon="eye"/>
          </button>
          <button
            class="btn btn-success"
            v-on:click.prevent="approve(row.item.id)"
            v-if="row.item.number != user.number"
          >Aprovar</button>
          <button
            class="btn btn-danger"
            v-on:click.prevent="deny(row.item.id)"
            v-if="row.item.number != user.number"
          >Rejeitar</button>
          <!-- modal da info -->
          <div
            class="modal fade"
            id="exampleModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Estudante: {{row.item.name}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">Nascido em: {{row.item.birthDate}}</div>

                  <div class="form-group">Escola: {{row.item.school}}</div>

                  <div class="form-group">Curso: {{row.item.course}}</div>

                  <div class="form-group">Ano curricular: {{row.item.curricularYear}}</div>

                  <div class="form-group">Entrou na escola em: {{row.item.enruledYear}}</div>

                  <div class="form-group">Incapacidade: {{row.item.functionalAnalysis}}</div>

                  <div class="form-group">Pedido: {{row.item.educationalSupport}}</div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <!-- <button type="submit" class="btn btn-primary" data-dismiss="modal"
                    v-on:click.prevent="setContact(row.item)">Confirmar</button>-->
                  </div>
                </div>
              </div>
            </div>
          </div>
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
      supportsForStudent: null
    };
  },
  methods: {
    getRequests(page_url) {
      let pg = this;
      page_url = page_url || "api/getServicesRequests?page=1";
      axios
        .get(page_url)
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
