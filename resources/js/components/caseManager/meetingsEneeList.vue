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
    <set-meeting :user="currentMeeting" @save-meeting="saveMeeting" @cancel-edit="cancelEdit()"></set-meeting>
    <b-container>
      <b-row>
        <b-col>
          <h2>Pedidos reuniao</h2>
          <b-table striped hover v-if="meetings!=null" :items="meetings" :fields="fields">
            <template slot="actions" slot-scope="row">
              <b-row class="text-center">
                <b-col md="4" sm="12">
                  <b-form-checkbox v-model="row.detailsShowing" @change="row.toggleDetails"></b-form-checkbox>
                  <div v-if="row.detailsShowing" style="margin-left: -8px;">
                    <font-awesome-icon icon="eye"/>
                  </div>
                  <div v-if="!row.detailsShowing" style="margin-left: -8px;">
                    <font-awesome-icon icon="eye-slash"/>
                  </div>
                </b-col>
                <b-col md="4" sm="12">
                  <b-button size="sm" v-on:click.prevent="setMeeting(row.item)">
                    Agendar
                    <font-awesome-icon icon="handshake"/>
                  </b-button>
                </b-col>
              </b-row>
            </template>
            <template slot="row-details" slot-scope="row">
              <b-card>
                <b-row class="mb-2">
                  <b-col class="text">
                    <b>Informacao:</b>
                    {{row.item.info}}
                  </b-col>
                </b-row>

                <b-row class="mb-2">
                  <b-col class="text">
                    <b>Local:</b>
                    {{row.item.place}}
                  </b-col>
                </b-row>
                <b-row class="mb-2">
                  <b-col class="text">
                    <b>Comentario:</b>
                    {{row.item.comment}}
                  </b-col>
                </b-row>
                <b-button size="sm" @click="row.toggleDetails">Esconder</b-button>
              </b-card>
            </template>
          </b-table>
          <nav aria-label="Page navigation" v-if="meetings">
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
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      yourTimeValue: {},
      pagination: {},
      loading: true,
      meetings: null,
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
          key: "service",
          label: "Servico",
          sortable: true
        },
        {
          key: "date",
          label: "Data",
          sortable: true
        },
        {
          key: "time",
          label: "Hora",
          sortable: true
        },
        {
          key: "actions",
          label: "Ações"
        }
      ],
      currentMeeting: null,
      userInteractions: null,
      interactions: null
    };
  },
  methods: {
    getMeetingsEnee() {
      axios
        .get("api/getMyMeetings/" + this.user.id)
        .then(response => {
          this.meetings = response.data.data;
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
    setMeeting(row) {
      this.currentMeeting = Object.assign({}, row);
    },
    cancelEdit: function() {
      this.currentMeeting = null;
    },
    cancelInteractions: function() {
      this.userInteractions = null;
    },
    saveMeeting(data) {
      console.log(data);
      axios
        .post("api/setEneeMeeting/" + this.currentMeeting.id, data)
        .then(response => {
          this.getMeetingsEnee();
          this.currentMeeting = null;
          this.$toasted.success("Guardado com sucesso.", {
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
    this.getMeetingsEnee();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>
