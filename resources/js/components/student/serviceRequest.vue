<template>
  <div>
      <ValidationObserver v-slot="{ handleSubmit }">
      <form @submit.prevent="handleSubmit(setService)">
      <b-container class="top100">
        <b-row>
          <b-col>

        \Debugbar::info($supportsToAdd);
        \Debugbar::info($existingSupports);
        \Debugbar::info($newSupports);
        \Debugbar::info($supportsToDelete);     <h2>{{ $t('pedido_apoio') }}</h2>
            <div class="form-group">
              <div v-if="supports">
                <b-form-group label="Apoios ao estudante">
                    <ValidationProvider name="support" rules="required" v-slot="{ errors }">
                        <div id="app">
                              <span v-for="category in categories">
                                  <li style="list-style-type:none">
<!--                                      <b-form-checkbox  v-if="!category.supports.length" v-model="selectedCategories" :value="category.id">-->
<!--                                              {{ category.name }}-->
<!--                                          </b-form-checkbox>-->
                                      <label>
                                          {{category.name}}
                                      </label>
                                  </li>

                                  <ul>
                                          <b-form-checkbox  v-model="selectedSupports" :disabled="mainSupports.includes(sup.id)" :value="sup.id" v-for="sup in category.supports">
                                              {{ sup.name }}
                                          </b-form-checkbox>
                                  </ul>
                              </span>

                        </div>
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
      supports: [],
      selectedSupports:[],
      selectedCategories:[],
      mainSupports:[],
      categories:[],
      service: {
        reason: null,
        requestedSupport: null
      },
      options: null
    };
  },
  methods: {
    setService() {
        this.service.requestedSupport = this.selectedSupports.filter(x => !this.mainSupports.includes(x));

      axios
        .post("api/setService/", this.service)
        .then(response => {
          this.mainSupports = this.selectedSupports;
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
    getSupportsByStudent() {
      axios
          .get("api/getSupportsByStudent")
          .then(response => {
              this.loading = false;
              this.supports = response.data;
              this.supports.forEach(support => {
                  this.selectedSupports.push(support.id);
                  this.mainSupports.push(support.id);
              })

              console.log(this.supports);
          })
          .catch(error => {
              console.log(error);
              this.loading = false;
          });
    },
    getSupports(){
      axios.get('api/getSupportsByCategory')
          .then(response => {
              this.categories = response.data;
          })
          .catch(error=>{
              console.log(error);
          });

    },
    getRequestedSupports(){
        axios.get('api/getSupportRequestsByStudent')
            .then(response => {
                console.log(response.data);
                response.data.forEach(support=>{
                    if(!this.mainSupports.includes(support)){
                        this.mainSupports.push(Number(support));
                    }
                })
            })
            .catch(error=>{
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
    this.getSupports();
    this.getSupportsByStudent();
    this.getRequestedSupports();
  }
};
</script>

<style>
</style>
