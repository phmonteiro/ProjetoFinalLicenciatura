<template>
  <div>
    <b-container class="top100">
      <b-row>
        <b-col>
          <h2>Pedido de serviços</h2>
          <div class="form-group">
            <b-form-select v-model="service.name" class="mb-3">
              <!-- This slot appears above the options from 'options' prop -->
              <template slot="first">
                <option :value="null" disabled>-- Selecione o serviço pretendido --</option>
              </template>

              <!-- These options will appear after the ones from 'options' prop -->
              <option value="prioridade">Prioridade</option>
              <option value="apioSalaAula">Apoios em sala de aula</option>
              <option value="apoioComponenteLetiva">Apoio à componente letiva</option>
              <option value="acompanhamentoIndividualizado">Acompanhamento individualizado</option>
              <option value="apoioComponenteLetiva">Apoio à componente letiva</option>
              <option value="acopanhamenteProfessorTutor">Acompanhamento por professor tutor</option>
              <option value="metodosProvasAdaptados">Métodos e provas de avaliação adaptados</option>
              <option value="apoioSocial">Apoio social</option>
              <option value="acessorEpocaEspecial">Acesso a épocas especiais de exame</option>
              <option
                value="localAulas"
              >Adequação na atribuição de local para realização das unidades curriculares de estágio, de educação clínica, de ensino clínico e de práticas pedagógicas</option>
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
          <button type="submit" class="btn btn-primary" v-on:click.prevent="setService()">
            Pedir
            serviço
          </button>
        </b-col>
      </b-row>
    </b-container>
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
    setService() {
      axios
        .post("api/setService/" + this.user.id, this.service)
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
