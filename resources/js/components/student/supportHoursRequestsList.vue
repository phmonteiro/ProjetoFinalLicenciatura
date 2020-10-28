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
      <h2>{{$t('pedidos_horas_apoio')}}</h2>
      <div v-if="requests!=null && requests.length!==0">
      <b-table striped hover :items="requests" :fields="fields">
        <template v-slot:cell(status)="{ value }">
          <p v-if="value==='waiting'">{{ $t('em_avaliação') }}</p>
          <p v-if="value==='approved'">{{ $t('aprovado') }}</p>
          <p v-if="value==='denied'">{{ $t('reprovado') }}</p>
        </template>
        <template v-slot:cell(decision)="row">
          <b-row v-if="row.item.decision!=null" class="text-center">
            <b-col md="4" sm="12">
              <!-- <button class="btn btn-success" v-on:click.prevent="approve(row.item.id)">{{ $t('aceitar')}}</button> -->
              <!-- Button trigger modal -->
              <button
                type="button"
                class="btn btn-primary"
                data-toggle="modal"
                data-target="#modalSupportHoursRequestDecision"
                @click.prevent="updateModal(row.item.decision)"
              >{{ $t('ver_decisao') }}</button>

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
                      >Decisão tomada</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      {{decision}}
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                  >{{$t('anterior')}}</a>
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
                  >{{$t('próximo')}}</a>
              </li>
          </ul>
      </nav>
      </div>
      <div v-else>
          <hr>
          <br>
          <h4>{{$t('sem_pedidos_apoio')}}</h4>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      decision: "",
      pagination: {},
      loading: false,
      requests: null,
      fields: [
        {
          key: "student_name",
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
          key: "date",
          label: "Data do Pedido",
          sortable: true,
        },
        {
          key: "status",
          label: "Estado do Pedido",
          sortable: true,
        },
        {
          key: "decision",
          label: "Decisão",
          sortable: true,
        }
      ],
    };
  },
  methods: {
    getRequests(page_url) {
      this.loading=true;
      let pg = this;
      page_url = page_url || "api/getSupportHoursRequests?page=1";
      axios
        .get(page_url)
        .then((response) => {
          this.loading = false;
          console.log(response);
          this.requests = response.data;
          pg.makePagination(response.data.data.meta, response.data.data.links);
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
        });
    },
    updateModal(decision) {
      this.decision = decision
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
