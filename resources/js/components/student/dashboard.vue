<template>
  <div>
    <div class="container">
      <b-row>
      <b-col md="8">
        <h6>Proximos Contactos</h6>
        <b-table striped hover v-if="contacts!=null" :items="contacts" :fields="fields"></b-table>
      </b-col>
      <b-col md="4">
        <h6>Pedir Agendamento Reuniao</h6>
        <div class="form-group">
                   <b-form-select v-model="meeting.service" class="mb-3">
                        <!-- This slot appears above the options from 'options' prop -->
                        <template slot="first">
                          <option :value="null" disabled>-- Selecione o serviço --</option>
                        </template>

                        <!-- These options will appear after the ones from 'options' prop -->
                        <option value="SAPE">SAPE</option>
                        <option value="SAS">SAS</option>
                        <option value="Escola">Escola</option>
                        <option value="Biblioteca">Biblioteca</option>
                        <option value="Direção">Direção</option>
                        <option value="Professor-Tutor">Professor-Tutor</option>
                        <option value="Gestor-Caso">Gestor-Caso</option>
                    </b-form-select>
                  </div>
                  <div class="form-group">
                    <label for="comment">Comentário: </label>
                    <textarea
                      class="form-control"
                      id="decision"
                      v-model="meeting.comment"
                      name="comment"
                      rows="3"
                    ></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary" v-on:click.prevent="setMeeting()">Perdir reunião</button>
      </b-col>
      </b-row>
      <myMeetings></myMeetings>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      contacts: null,
      fields: [
        {
          key: "service",
          label: "Serviço",
          sortable: true
        },
        {
          key: "decision",
          label: "Decisão"
        },
        {
          key: "date",
          label: "Data",
          sortable: true
        }
      ],
      meeting: {
        service: null,
        comment: null
      }
      
    };
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  },
  methods: {
    getContacts() {
      axios
        .get("api/getContacts/" + this.user.id)
        .then(response => {
          this.contacts = response.data.data;
          console.log("contactos: ",this.contacts);
        })
        .catch(error => {
          console.log(error);
        });
    },
    setMeeting(){
      axios
        .post("api/setMeeting/" + this.user.id, this.meeting)
        .then(response => {
          console.log("meeting requested!");
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  created() {
    this.getContacts();
  }
};
</script>
