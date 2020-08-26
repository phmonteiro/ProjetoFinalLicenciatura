<template>
  <div>
    <b-container>
      <b-row>
        <b-col></b-col>
        <b-col>
          <div class="loader">
            <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50" />
          </div>
        </b-col>
        <b-col></b-col>
      </b-row>
    </b-container>
    <b-container>
      <b-row>
        <b-col class="top100">
          <div v-if="supports">
            <h2>{{ $t('acompanhamento_individualizado') }}</h2>
            <br />
            <h5>{{ $t('quantidade_horas_total')}}: {{this.supportHoursLimit}}</h5>
            <h5>{{ $t('quantidade_horas_utilizadas') }}: {{this.totalHours}}</h5>
            <h5>{{ $t('quantidade_horas_disponiveis')}}: {{this.supportHoursLimit - this.totalHours}}</h5>
            <br />
            <b-table striped hover :items="supports" :fields="fields">
              <template v-slot:cell(actions)="row">
                <b-button
                  type="submit"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                  v-on:click.prevent="editSupportHours(row.item)"
                >{{ $t('solicitar_horas_apoio') }}</b-button>
              </template>
            </b-table>
          </div>
          <div v-else>
            <h4>Não está inscrito em quaisquer Unidades Curriculares.</h4>
          </div>
        </b-col>
      </b-row>
    </b-container>

    <edit-support-hours
      :support="currentSupport"
      @request-support-hours="requestSupportHours()"
      @cancel-request="cancelSupportHoursRequest()"
    ></edit-support-hours>
  </div>
</template>

<script>
export default {
  data() {
    return {
      supports: null,
      loading: true,
      currentSupport: null,
      totalHours: 0,
      supportHoursLimit: null,
      user: null,
      fields: [
        {
          key: "nome",
          label: this.$t("nome"),
          sortable: true,
        },
        {
          key: "semester",
          label: this.$t("semestre"),
          sortable: true,
        },
        {
          key: "hours",
          label: this.$t("horas_acompanhamento"),
          sortable: true,
        },
        {
          key: "actions",
          label: this.$t("ação"),
        },
      ],
    };
  },
  methods: {
    getTotalHours() {
      axios
        .get("api/getTotalSupportHours/" + this.user.id)
        .then((response) => {
          this.supportHoursLimit = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getHours() {
      axios
        .get("api/supportHours/" + this.user.id)
        .then((response) => {
          this.totalHours = 0;
          this.supports = response.data;
          this.loading = false;

          this.supports.forEach((element) => {
            this.totalHours += element.hours;
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
    editSupportHours(row) {
      console.log(row);
      this.currentSupport = Object.assign({}, row);
    },
    getAuth() {
      axios
        .get("api/getAuthUser")
        .then((response) => {
          this.user = response.data;
          this.getTotalHours();
          this.getHours();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    requestSupportHours() {
      console.log("antes do axios.get('api/getSupportHoursRequests')");
      axios
        .get("api/getSupportHoursRequests")
        .then((response) => {

          // declarada aqui devido à abrangência da função
          function contains(arr, key, val) {
            for (var i = 0; i < arr.length; i++) {
              if (arr[i][key] === val) return "true";
            }
            return "false";
          };
          console.log("início da resposta a axios.get('api/getSupportHoursRequests')");

          if (contains(response.data, "id", this.currentSupport.id) == "false") {
            console.log("entrou no contains do axios.get('api/getSupportHoursRequests')");
            axios
              .post("api/requestSupportHours", this.currentSupport)
              .then((response) => {
                console.log("início da resposta a axios.post('api/requestSupportHours')");
                console.log(response);
                this.getHours();
                this.$toasted.success(
                  "Pedido de horas de apoio realizado com sucesso.",
                  {
                    duration: 4000,
                    position: "top-center",
                    theme: "bubble",
                  }
                );
                this.currentSupport = null;
              })
              .catch((error) => {
                console.log(error.response.data.message);
                this.$toasted.error(error.response.data.message, {
                  duration: 4000,
                  position: "top-center",
                  theme: "bubble",
                });
              });
          } else {
              this.$toasted.error(
              "Já realizou um pedido de horas de apoio para esta UC, que será avaliado pelo seu Gestor de Caso.",
              {
                duration: 4000,
                position: "top-center",
                theme: "bubble",
              });
          };
        })
        .catch((error) => {
                this.$toasted.error(error, {
                  duration: 4000,
                  position: "top-center",
                  theme: "bubble",
                });
        });
    },

    /* 
        setSupportHours() {
          axios
            .post("api/setSupportHours", this.currentSupport)
            .then(response => {
              console.log(response);
              this.getHours();
              this.$toasted.success(
                "Pedido de acompanhamento individualizado realizado com sucesso.",
                {
                  duration: 4000,
                  position: "top-center",
                  theme: "bubble"
                }
              );
              this.currentSupport = null;
            })
            .catch(error => {
              console.log(error.response.data.message);
              this.$toasted.error(error.response.data.message, {
                duration: 4000,
                position: "top-center",
                theme: "bubble"
              });
            });
        }, 
      */
    cancelSupportHoursRequest: function () {
      this.currentSupport = null;
    },
  },
  created() {
    this.getAuth();
  },
};
</script>
