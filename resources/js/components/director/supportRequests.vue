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
      <h2>Lista de pedidos a apoios</h2>
      <b-table striped hover v-if="supportRequests!=null" :items="supportRequests" :fields="fields">
        <template v-slot:cell(actions)="row">
          <b-row class="text-center">
            <b-col md="4" sm="12">
<!--              <b-form-checkbox v-model="row.detailsShowing" @change="row.toggleDetails"></b-form-checkbox>-->
              <div v-if="row.detailsShowing" style="margin-left: -8px;">
                  <b-button @click="row.toggleDetails" >Ocultar</b-button>
              </div>
              <div v-if="!row.detailsShowing" style="margin-left: -8px;">
                  <b-button @click="row.toggleDetails" >Detalhes</b-button>
              </div>
            </b-col>
            <b-col md="4" sm="12">
              <button class="btn btn-info" v-on:click.prevent="approve(row.item)">Aceitar</button>
            </b-col>
            <b-col md="4" sm="12">
              <button class="btn btn-danger" v-on:click.prevent="reject(row.item)">Rejeitar</button>
            </b-col>
          </b-row>
        </template>
        <template slot="row-details" slot-scope="row">
          <b-card>
            <b-row class="mb-2">
              <b-col class="text">
                <b>Informação:</b>
                {{row.item.reason}}
              </b-col>
            </b-row>
            <b-button size="sm" @click="row.toggleDetails">Esconder</b-button>
          </b-card>
        </template>
      </b-table>
        <h4 v-else>De momento não existe nenhum pedido de apoio</h4>
      <nav aria-label="Page navigation" v-if="supportRequests">
        <ul class="pagination">
          <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
            <a
              class="page-link"
              href="#"
              @click.prevent="getsupportRequests(pagination.prev_page_url)"
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
              @click.prevent="getsupportRequests(pagination.next_page_url)"
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
      supportRequests: null,
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
          key: "text",
          label: "Apoio",
          sortable: true
        },
        {
          key: "actions",
          label: "Ações Utilizador"
        }
      ]
    };
  },
  methods: {
    getSupportRequests(page_url) {
      let pg = this;
      page_url = page_url || "api/getSupportRequests?page=1";
      axios
        .get(page_url)
        .then(response => {
          this.loading = false;
          this.supportRequests = response.data.data;
          pg.makePagination(response.data);
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },
    makePagination(data) {
      let pagination = {
        current_page: data.current_page,
        last_page: data.last_page,
        next_page_url: data.next_page_url,
        prev_page_url: data.prev_page_url
      };
      this.pagination = pagination;
    },
    approve(row) {
      axios
        .post("api/addStudentSupport/" + row.id)
        .then(response => {
          this.getSupportRequests();
          this.$toasted.success("Apoio adicionado com sucesso.", {
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
    reject(data) {
      axios
        .patch("api/rejectStudentSupport/" + data.id)
        .then(response => {
          this.getSupportRequests();
          this.$toasted.success("Apoio rejeitado.", {
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
    this.getSupportRequests();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>
