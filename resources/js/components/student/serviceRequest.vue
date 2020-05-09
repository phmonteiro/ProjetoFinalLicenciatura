<template>
  <div>
      <ValidationObserver v-slot="{ handleSubmit }">
      <form @submit.prevent="handleSubmit(setService)">
      <b-container class="top100">
        <b-row>
          <b-col>
            <h2>{{ $t('pedido_apoio') }}</h2>
            <div class="form-group">
              <div v-if="options!=null">
                <b-form-group label="Apoios ao estudante">
                    <ValidationProvider name="support" rules="required" v-slot="{ errors }">
                        <b-form-radio-group
                            v-model="service.requestedSupport"
                            :options="options"
                            switches
                        ></b-form-radio-group>
                        <code>{{ errors[0] }}</code>
                    </ValidationProvider>

                </b-form-group>
              </div>

            </div>
            <div class="form-group">
              <label for="reason">{{ $t('motivo') }}</label>

                <ValidationProvider name="motivo" rules="required" v-slot="{ errors }">
              <textarea
                  class="form-control"
                  id="reason"
                  v-model="service.reason"
                  name="reason"
                  rows="3"
              ></textarea>
                    <code>{{ errors[0] }}</code>
                </ValidationProvider>
            </div>
            <button type="submit" class="btn btn-primary">{{ $t('pedir_serviço') }}</button>
          </b-col>
        </b-row>
      </b-container>
    </form>
    </ValidationObserver>
  </div>
</template>

<script>
export default {
  data() {
    return {
      service: {
        reason: null,
        requestedSupport: null
      },
      options: null
    };
  },
  methods: {
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
    },

    getAllSupports() {
      axios
        .get("api/getSupports")
        .then(response => {
          this.options = response.data;
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
  },
  created() {
    this.getAllSupports();
  }
};
</script>

<style>
</style>
