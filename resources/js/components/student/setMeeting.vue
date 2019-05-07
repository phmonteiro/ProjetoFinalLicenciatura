<template>
  <b-row>
    <b-col>
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
      <button type="submit" class="btn btn-primary" v-on:click.prevent="setMeeting()">Pedir reunião</button>
    </b-col>
  </b-row>
</template>

<script>
export default {
  data() {
    return {
      meeting: {
        service: null,
        comment: null
      }
    };
  },
  methods: {
    setMeeting() {
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
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>

<style>
</style>
