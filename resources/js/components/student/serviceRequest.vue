<template>
  <div>
    <form @submit.prevent="validateBeforeSubmit">
      <b-container class="top100">
        <b-row>
          <b-col>
            <h2>{{ $t('pedido_apoio') }}</h2>
            <div class="form-group">
              <b-form-select
                v-validate="'required'"
                name="service"
                v-model="service.name"
                class="mb-3"
              >
                <template slot="first">
                  <option :value="null" disabled>-- {{ $t('selecionar_serviço') }} --</option>
                </template>
                <option value="prioridade">{{ $t('prioridade') }}</option>
                <option value="apioSalaAula">{{ $t('apoio_sala_aula') }}</option>
                <option value="apoioComponenteLetiva">{{ $t('apoio_componente_letiva') }}</option>
                <option
                  value="acompanhamentoIndividualizado"
                >{{ $t('acompanhamento_individualizado') }}</option>
                <option
                  value="acopanhamenteProfessorTutor"
                >{{ $t('acompanhament_professor_tutor') }}</option>
                <option value="metodosProvasAdaptados">{{ $t('provas_adaptadas') }}</option>
                <option value="apoioSocial">{{ $t('apoio_social') }}</option>
                <option value="acessorEpocaEspecial">{{ $t('epoca_especial') }}</option>
                <option value="localAulas">{{ $t('local_aulas') }}</option>
              </b-form-select>
              <i v-show="errors.has('service')" class="fa fa-warning"></i>
              <span
                v-show="errors.has('service')"
                class="help is-danger"
              >{{ errors.first('service') }}</span>
            </div>
            <div class="form-group">
              <label for="comment">{{ $t('motivo') }}</label>
              <textarea
                v-validate="'required'"
                class="form-control"
                id="reason"
                v-model="service.reason"
                name="reason"
                rows="3"
              ></textarea>
              <i v-show="errors.has('reason')" class="fa fa-warning"></i>
              <span
                v-show="errors.has('reason')"
                class="help is-danger"
              >{{ errors.first('reason') }}</span>
            </div>
            <button type="submit" class="btn btn-primary">{{ $t('pedir_serviço') }}</button>
          </b-col>
        </b-row>
      </b-container>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      service: {
        reason: null,
        name: null
      }
    };
  },
  methods: {
    validateBeforeSubmit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.setService();
          return;
        }
      });
    },
    setService() {
      axios
        .post("api/setService/", this.service)
        .then(response => {
          this.$toasted.success("Pedido de serviço realizado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          this.$toasted.error(
            "Erro ao fazer pedido de serviço. Por favor tente novamente.",
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
