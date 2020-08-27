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
    <div class="container">
<!--        v-if="meetings"-->
      <h2>Pedidos de Reunião</h2>
        <hr>

        <div v-if="meetings!=null && meetings.length!==0">
            <b-table striped hover :items="meetings" :fields="fields">
                <template v-slot:cell(actions)="row">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-toggle="modal"
                        data-target="#modalReunioes"
                    >Marcar Reuniao</button>

                    <div
                        class="modal fade"
                        id="modalReunioes"
                        tabindex="-1"
                        role="dialog"
                        aria-labelledby="modalReunioesLabel"
                        aria-hidden="true"
                    >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalReunioesLabel">Reuniao com {{row.item.service}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="decision">Comentario:</label>
                                        <textarea
                                            class="form-control"
                                            id="decision"
                                            v-model="row.item.comment"
                                            name="decision"
                                            rows="3"
                                            disabled
                                        ></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="additionalInfo">Informação adicional:</label>
                                        <textarea
                                            class="form-control"
                                            id="additionalInfo"
                                            v-model="meeting.info"
                                            name="decision"
                                            rows="3"
                                        ></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="date">Data Reuniao</label>
                                        <input
                                            id="date"
                                            type="date"
                                            class="form-control"
                                            v-model="meeting.date"
                                            name="date"
                                        >
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                            data-dismiss="modal"
                                            v-on:click.prevent="setMeeting(row.item.id)"
                                        >Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </b-table>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                        <a
                            class="page-link"
                            href="#"
                            @click.prevent="getMeetings(pagination.prev_page_url)"
                        >{{$t('anterior')}}</a>
                    </li>

                    <li class="page-item disabled">
                        <a
                            class="page-link text-dark"
                            href="#"
                        >Página {{ pagination.current_page }} de {{ pagination.last_page }}</a>
                    </li>

                    <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                        <a
                            class="page-link"
                            href="#"
                            @click.prevent="getMeetings(pagination.next_page_url)"
                        >{{$t('próximo')}}</a>
                    </li>
                </ul>
            </nav>
        </div>
        <div v-else>
            <br>
            <h5>Não existem reuniões para mostrar.</h5>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: true,
      pagination: {},
      meetings: null,
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
          key: "service",
          label: "Serviço",
          sortable: true
        },
        {
          key: "comment",
          label: "Comentário",
          sortable: true
        },
        {
          key: "info",
          label: "Informação",
          sortable: true
        },
        {
          key: "date",
          label: "Data",
          sortable: true
        },
        {
          key: "actions",
          label: "Ações"
        }
      ],
      meeting: {
        info: null,
        date: null
      }
    };
  },
  methods: {
    setMeeting(meetingId) {
      axios
        .post("api/finalizeMeeting/" + meetingId, this.meeting)
        .then(response => {
          this.getMeetings();
          this.$toasted.success("Reunião marcada.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(
            "Erro ao marcar reunião, por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    },
    getMeetings(page_url) {
      let pg = this;
      page_url = page_url || "api/getMeetings?page=1";
      axios
        .get(page_url)
        .then(response => {
          this.loading = false;
          this.meetings = response.data.data;
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
    }
  },
  created() {
    this.getMeetings();
  }
};
</script>

<style>
</style>
