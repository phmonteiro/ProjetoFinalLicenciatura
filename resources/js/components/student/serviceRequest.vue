<template>
  <div>
    <form @submit.prevent="validateBeforeSubmit">
      <b-container class="top100">
        <b-row>
          <b-col>
            <h2>{{ $t('pedido_apoio') }}</h2>
            <div class="form-group">
              <div v-if="options!=null">
                <b-form-group label="Apoios ao estudante">
                  <b-form-radio-group
                    v-model="service.requestedSupport"
                    :options="options"
                    switches
                  ></b-form-radio-group>
                </b-form-group>
              </div>
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
        requestedSupport: null
      },
      options: null
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
