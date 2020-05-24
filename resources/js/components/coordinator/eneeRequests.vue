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
    <div class="container">
      <h2>Lista de pedidos</h2>
      <b-table striped hover v-if="requests!=null" :items="requests" :fields="fields">
        <template v-slot:cell(actions)="row">
          <b-row class="text-center">
            <b-col md="4" sm="12">
              <b-form-checkbox v-model="row.detailsShowing" @change="row.toggleDetails"></b-form-checkbox>
              <div v-if="row.detailsShowing" style="margin-left: -8px;">
                <font-awesome-icon icon="eye" />
              </div>
              <div v-if="!row.detailsShowing" style="margin-left: -8px;">
                <font-awesome-icon icon="eye-slash" />
              </div>
            </b-col>
            <b-col md="4" sm="12">
              <button
                class="btn btn-success"
                v-on:click.prevent="approve(row.item.id)"
                v-if="row.item.number != user.number"
              >Aprovar</button>
            </b-col>
            <b-col md="4" sm="12">
              <button
                class="btn btn-danger"
                v-on:click.prevent="deny(row.item.id)"
                v-if="row.item.number != user.number"
              >Rejeitar</button>
            </b-col>
          </b-row>
        </template>
        <template slot="row-details" slot-scope="row">
          <b-card>
            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Nascido em:</b>
                {{row.item.birthDate}}
              </b-col>
              <b-col>
                <b-row class="mb-2">
                  <b-col sm="4" class="text">
                    <b>Ano curricular:</b>
                    {{row.item.curricularYear}}
                  </b-col>
                </b-row>
              </b-col>
            </b-row>

            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Escola:</b>
                {{row.item.school}}
              </b-col>
              <b-col>
                <b-row class="mb-2">
                  <b-col sm="4" class="text">
                    <b>Entrou na escola em:</b>
                    {{row.item.enruledYear}}
                  </b-col>
                </b-row>
              </b-col>
            </b-row>

            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Curso:</b>
                {{row.item.course}}
              </b-col>
              <b-col>
                <b-row class="mb-2">
                  <b-col sm="4" class="text">
                    <b>Incapacidade:</b>
                    {{row.item.functionalAnalysis}}
                  </b-col>
                </b-row>
              </b-col>
            </b-row>

            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Sexo:</b>
                <span v-if="row.item.gender === 'masculino'">Masculino</span>
                <span v-if="row.item.gender === 'feminino'">Feminino</span>
                <span v-if="row.item.gender === 'outro'">Outro</span>
              </b-col>
              <b-col>
                <b-row class="mb-2">
                  <b-col sm="4" class="text">
                    <b>1ª matricula:</b>
                    {{row.item.enruledYear}}
                  </b-col>
                </b-row>
              </b-col>
            </b-row>

            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Estatuto:</b>
                <span v-if="row.item.enee === 'approved'">Aprovado</span>
                <span v-if="row.item.enee === 'awaiting'">A espera</span>
                <span v-if="row.item.enee == null">Nao pedido</span>
              </b-col>
              <b-col>
                <b-row class="mb-2">
                  <b-col sm="4" class="text">
                    <b>Estatuto pedido a:</b>
                    {{row.item.dateEneeRequested}}
                  </b-col>
                </b-row>
              </b-col>
            </b-row>
            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Estatuto aprovado a: {{row.item.dateEneeApproval}}</b>
              </b-col>
            </b-row>
            <b-button size="sm" @click="row.toggleDetails">Esconder</b-button>
          </b-card>
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
      page_url = page_url || "api/getRequests?page=1";
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
        .patch("api/approveEneeStatus/" + userId)
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
        .patch("api/denyEneeStatus/" + userId)
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
