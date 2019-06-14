<template>
  <div>
    <b-container>
      <b-row>
        <b-col></b-col>
        <b-col>
          <div class="loader">
            <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50"/>
          </div>
        </b-col>
        <b-col></b-col>
      </b-row>
    </b-container>
    <manage-plan :user="currentUser2" :plan="currentPlan" @cancel-edit2="cancelEdit2()"></manage-plan>

    <set-inter :user="currentUser" @save-interaction="saveInteraction" @cancel-edit="cancelEdit()"></set-inter>

    <div class="container">
      <h2>Lista de Enee</h2>
      <b-table striped hover v-if="enee!=null" :items="enee" :fields="fields">
        <template slot="actions" slot-scope="row">
          <b-row class="text-center">
            <b-col md="3" sm="12">
              <b-form-checkbox v-model="row.detailsShowing" @change="row.toggleDetails"></b-form-checkbox>
              <div v-if="row.detailsShowing" style="margin-left: -8px;">
                <font-awesome-icon icon="eye"/>
              </div>
              <div v-if="!row.detailsShowing" style="margin-left: -8px;">
                <font-awesome-icon icon="eye-slash"/>
              </div>
            </b-col>
            <b-col md="3" sm="12">
              <b-button size="sm" v-on:click.prevent="setInteraction(row.item)">
                Interação
                <font-awesome-icon icon="handshake"/>
              </b-button>
            </b-col>
            <b-col md="3" sm="12">
              <b-button size="sm" v-on:click.prevent="seeInteractions(row.item)">
                Ver Interações
                <font-awesome-icon icon="handshake"/>
              </b-button>
            </b-col>
            <b-col md="3" sm="12">
              <b-button size="sm" v-on:click.prevent="managePlan(row.item)">
                Plano
                <font-awesome-icon icon="book"/>
              </b-button>
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
                <span v-if="row.item.gender == 'masculino'">Masculino</span>
                <span v-if="row.item.gender == 'feminino'">Feminino</span>
                <span v-if="row.item.gender == 'outro'">Outro</span>
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
                <span v-if="row.item.enee == 'approved'">Aprovado</span>
                <span v-if="row.item.enee == 'awaiting'">A espera</span>
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
      <nav aria-label="Page navigation" v-if="enee">
        <ul class="pagination">
          <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
            <a
              class="page-link"
              href="#"
              @click.prevent="getcaseManagers(pagination.prev_page_url)"
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
              @click.prevent="getcaseManagers(pagination.next_page_url)"
            >Próximo</a>
          </li>
        </ul>
      </nav>
    </div>

    <interactionsDetails
      :user="userInteractions"
      :interactions="interactions"
      @cancel-edit="cancelInteractions()"
    ></interactionsDetails>
  </div>
</template>

<script>
export default {
  data() {
    return {
      yourTimeValue: {},
      pagination: {},
      loading: true,
      enee: null,
      fields: [
        {
          key: "name",
          label: "Nome Estudande",
          sortable: true
        },
        {
          key: "email",
          label: "Email Estudante",
          sortable: true
        },
        {
          key: "phoneNumber",
          label: "Contacto",
          sortable: true
        },
        {
          key: "school",
          label: "Escola",
          sortable: true
        },
        {
          key: "actions",
          label: "Ações"
        }
      ],
      currentUser: null,
      currentUser2: null,
      userInteractions: null,
      interactions: null,
      currentPlan: null
    };
  },
  methods: {
    getCmEnee() {
      axios
        .get("api/getCmEnee/" + this.user.id)
        .then(response => {
          this.enee = response.data;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
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
    seeInteractions(row) {
      this.userInteractions = Object.assign({}, row);
      this.getEneeInteractions(this.userInteractions);
    },
    getEneeInteractions(user) {
      axios
        .get("api/getEneeInteractions/" + user.email)
        .then(response => {
          this.interactions = response.data.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getPlan(userId) {
      axios
        .get("api/getEneePlan/" + userId)
        .then(response => {
          this.currentPlan = response.data.data[0];
        })
        .catch(error => {
          console.log(error);
        });
    },
    setInteraction(row) {
      this.currentUser = Object.assign({}, row);
    },
    managePlan(row) {
      this.currentUser2 = Object.assign({}, row);
      this.getPlan(row.id);
    },
    cancelEdit: function() {
      this.currentUser = null;
    },
    cancelEdit2: function() {
      this.currentUser2 = null;
    },
    cancelInteractions: function() {
      this.userInteractions = null;
    },
    saveInteraction(data, files) {
      console.log(data);

      const formData = new FormData();
      for (var i = 0; i < files.length; i++) {
        formData.append("file" + i, files[i]);
      }

      data.email = this.currentUser.email;

      formData.append("decision", data.decision);
      formData.append("email", data.email);
      formData.append("information", data.information);
      formData.append("interactionDate", data.interactionDate);
      formData.append("nextInteraction", data.nextInteraction);
      formData.append("service", data.service);
      formData.append("numberFiles", files.length);

      axios
        .post("api/setInteraction/", formData)
        .then(response => {
          this.getCmEnee();
          this.currentUser = null;
          this.$toasted.success("Guardado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
          if (this.userInteractions != null) {
            this.getEneeInteractions(this.userInteractions);
          }
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
    this.getCmEnee();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>
