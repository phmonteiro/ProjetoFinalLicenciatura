<template>
  <div>
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
      <textarea class="form-control" id="reason" v-model="service.reason" name="reason" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary" v-on:click.prevent="setService()">Pedir serviço</button>
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
