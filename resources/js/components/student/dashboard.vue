<template>
  <div>
    <div class="container">
      <div>
        <h2>Contactos</h2>
        <b-table striped hover v-if="contacts!=null" :items="contacts" :fields="fields"></b-table>
      </div>
      <div>
        <myMeetings></myMeetings>
      </div>
      <div>
        <h2>Serviços usufruidos</h2>
        <b-table striped hover v-if="services" :items="services" :fields="fieldsServices"></b-table>
      </div>
      <b-row>
        <b-col md="6">
          <h2>Pedir Agendamento Reuniao</h2>
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
            <label for="comment">Comentário:</label>
            <textarea
              class="form-control"
              id="decision"
              v-model="meeting.comment"
              name="comment"
              rows="3"
            ></textarea>
          </div>
          <button
            type="submit"
            class="btn btn-primary"
            v-on:click.prevent="setMeeting()"
          >Pedir reunião</button>
        </b-col>
        <b-col md="6">
          <h2>Pedido de serviços</h2>
          <div class="form-group">
            <b-form-select v-model="service.name" class="mb-3">
              <!-- This slot appears above the options from 'options' prop -->
              <template slot="first">
                <option :value="null" disabled>-- Selecione o serviço pretendido --</option>
              </template>

              <!-- These options will appear after the ones from 'options' prop -->
              <option value="Elevador">Elevador</option>
              <option value="Apoio componente Letiva">Apoio componente Letiva</option>
              <option
                value="Mais tempo na realização de avaliações"
              >Mais tempo na realização de avaliações</option>
            </b-form-select>
          </div>
          <div class="form-group">
            <label for="comment">Motivo:</label>
            <textarea
              class="form-control"
              id="reason"
              v-model="service.reason"
              name="reason"
              rows="3"
            ></textarea>
          </div>
          <button
            type="submit"
            class="btn btn-primary"
            v-on:click.prevent="setService()"
          >Pedir serviço</button>
        </b-col>
      </b-row>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      contacts: null,
      services: null,
      fields: [
        {
          key: "service",
          label: "Serviço",
          sortable: true
        },
        {
          key: "date",
          label: "Data",
          sortable: true
        },
        {
          key: "nextContact",
          label: "Próximo Contacto",
          sortable: true
        }
      ],
      meeting: {
        service: null,
        comment: null
      },
      service: {
        reason: null,
        name: null
      },
      fieldsServices: [
        {
          key: "name",
          label: "Nome do serviço",
          sortable: true
        },
        {
          key: "expirationDate",
          label: "Data de expiração do serviço"
        }
      ]
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
        })
        .catch(error => {
          console.log(error);
        });
    },
    setMeeting() {
      axios
        .post("api/setMeeting/" + this.user.id, this.meeting)
        .then(response => {
          console.log("meeting requested!");
        })
        .catch(error => {
          console.log(error);
        });
    },
    setService() {
      axios
        .post("api/setService/" + this.user.id, this.service)
        .then(response => {
          console.log("service requested!");
        })
        .catch(error => {
          console.log(error);
        });
    },
    getServices() {
      axios
        .get("api/getServices/" + this.user.id)
        .then(response => {
          this.services = response.data;
          console.log(this.services);
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  created() {
    this.getContacts();
    this.getServices();
  }
};
</script>
