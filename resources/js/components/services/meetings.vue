<template>
  <div>
    <h2>Pedidos Reuniao</h2>
    <b-table striped hover v-if="meetings!=null" :items="meetings" :fields="fields">
      <template slot="actions" slot-scope="row">
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
                  <label for="decision">Informação adicional:</label>
                  <textarea
                    class="form-control"
                    id="decision"
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
                    v-on:click.prevent="setMeeting(row.item.id)"
                  >Confirmar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </b-table>
  </div>
</template>

<script>
export default {
  data() {
    return {
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
    getMeetings() {
      axios
        .get("api/getMeetings")
        .then(response => {
          this.meetings = response.data.data;
          console.log(this.meetings);
        })
        .catch(error => {
          console.log(error);
        });
    },
    setMeeting(meetingId) {
      console.log(meetingId),
        axios
          .post("api/finalizeMeeting/" + meetingId, this.meeting)
          .then(response => {
            console.log("Reuniao marcada");
          })
          .catch(error => {
            console.log(error);
          });
    }
  },
  created() {
    this.getMeetings();
  }
};
</script>

<style>
</style>
