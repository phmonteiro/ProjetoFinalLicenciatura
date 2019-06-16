<template>
  <form @submit.prevent="validateBeforeSubmit">
    <b-container>
      <b-row>
        <b-col class="top100">
          <h2>{{ $t('pedir_agendamento_reunião') }}</h2>
          <div class="form-group">
            <b-form-select
              v-model="meeting.service"
              v-validate="'required'"
              name="service"
              class="mb-3"
            >
              <template slot="first">
                <option :value="null" disabled>-- {{ $t('selecionar_serviço') }} --</option>
              </template>
              <option value="Gestor-Caso">{{ $t('gestor_caso') }}</option>
            </b-form-select>
            <i v-show="errors.has('service')" class="fa fa-warning"></i>
            <span
              v-show="errors.has('service')"
              class="help is-danger"
            >{{ errors.first('service') }}</span>
          </div>
          <div class="form-group">
            <label for="comment">{{ $t('comentário') }}</label>
            <textarea
              v-validate="'required'"
              class="form-control"
              id="decision"
              v-model="meeting.comment"
              name="comment"
              rows="3"
            ></textarea>
            <i v-show="errors.has('comment')" class="fa fa-warning"></i>
            <span
              v-show="errors.has('comment')"
              class="help is-danger"
            >{{ errors.first('comment') }}</span>
          </div>
          <button type="submit" class="btn btn-primary">{{ $t('pedir_reunião') }}</button>
        </b-col>
      </b-row>
    </b-container>
  </form>
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
    validateBeforeSubmit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.setMeeting();
          return;
        }
      });
    },
    setMeeting() {
      axios
        .post("api/setMeeting", this.meeting)
        .then(response => {
          console.log("meeting requested!");
          this.$toasted.success("Pedido de reunião realizado sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(
            "Erro ao fazer pedido de reunião. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
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
