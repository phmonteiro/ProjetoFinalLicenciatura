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
    <div class="container mt-3">
      <h2>Pedidos de Horas de Apoio</h2>
        <div v-if="requests!=null && requests.length!==0">
            <b-table striped hover  :items="requests" :fields="fields">
                <template v-slot:cell(status)="{ value }">
                    <p v-if="value==='waiting'">Em avaliação</p>
                </template>
                <template v-slot:cell(actions)="row">
                    <b-row class="text-center">
                        <b-col md="4" sm="12">
                            <button class="btn btn-success" v-on:click.prevent="approve(row.item.id)">Aprovar</button>
                        </b-col>
                        <b-col md="4" sm="12">
                            <!-- Button trigger modal -->
                            <button
                                type="button"
                                class="btn btn-danger"
                                data-toggle="modal"
                                data-target="#modalSupportHoursRequestDecision"
                            >Rejeitar</button>

                            <!-- Modal -->
                            <div
                                class="modal fade"
                                id="modalSupportHoursRequestDecision"
                                tabindex="-1"
                                role="dialog"
                                aria-labelledby="exampleModalCenterTitle"
                                aria-hidden="true"
                            >
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5
                                                class="modal-title"
                                                id="exampleModalLongTitle"
                                            >Justifique a sua decisão (opcional)</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <b-form-textarea
                                                id="txtSupportHoursRequestDecision"
                                                v-model="row.item.decision"
                                                placeholder="Escreva aqui a informação relativa à rejeição do pedido de horas de apoio (opcional)"
                                                rows="5"
                                                max-rows="10"
                                            ></b-form-textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button class="btn btn-danger" v-on:click.prevent="deny(row.item)">Rejeitar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-col>
                    </b-row>
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
        <div v-else>
            <hr>
            <br>
            <h4>Não existem pedidos de horas de apoio.</h4>
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
      requests: null,
      fields: [
        {
          key: "student_email",
          label: "Nome Estudante",
          sortable: true,
        },
        {
          key: "subject_name",
          label: "UC",
          sortable: true,
        },
        {
          key: "hours",
          label: "Horas Solicitadas",
          sortable: true,
        },
        {
          key: "status",
          label: "Estado do Pedido",
          sortable: true,
        },
        {
          key: "actions",
          label: "Ações Utilizador",
        },
      ],
    };
  },
  methods: {
    getRequests(page_url) {
      let pg = this;
      page_url = page_url || "api/getSupportHoursRequests?page=1";
      axios
        .get(page_url)
        .then((response) => {
          this.loading = false;
          console.log(response.data);
          this.requests = response.data;
          pg.makePagination(response.data.data.meta, response.data.data.links);
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
        });
    },
    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev,
      };
      this.pagination = pagination;
    },
    approve(requestId) {
      axios
        .patch("api/approveSupportHoursRequest/" + requestId)
        .then((response) => {
          this.getRequests();
          $('#modalSupportHoursRequestDecision').modal('hide');
          this.$toasted.success("Submetido com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble",
          });
        })
        .catch((error) => {
          console.log(error);
          this.$toasted.error(
            "Erro ao aprovar pedido de horas de apoio. Por favor contacte o administrador da plataforma.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble",
            }
          );
        });
    },
    deny(item) {
      axios
        .patch("api/denySupportHoursRequest", item)
        .then((response) => {
          this.getRequests();
          $('#modalSupportHoursRequestDecision').modal('hide');
          this.$toasted.success("Submetido com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble",
          });
        })
        .catch((error) => {
          console.log(error);
          this.$toasted.error(
            "Erro ao rejeitar pedido de horas de apoio. Por favor contacte o administrador da plataforma.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble",
            }
          );
        });
    },
  },
  created() {
    this.getRequests();
  },
  computed: {
    user: function () {
      return this.$store.state.user;
    },
  },
};
</script>
