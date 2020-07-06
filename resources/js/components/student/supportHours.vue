<template>
  <div>
    <b-container>
      <b-row>
        <b-col></b-col>
        <b-col>
          <div class="loader">
            <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50"/>
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
              <br>
              <h5>{{ $t('quantidade_horas_total')}}: {{this.supportHoursLimit}}</h5>
              <h5>{{ $t('quantidade_horas_utilizadas') }}: {{this.totalHours}}</h5>
            <h5>{{ $t('quantidade_horas_disponiveis')}}: {{this.supportHoursLimit - this.totalHours}}</h5>
<!--              <br>-->
<!--              <h6>{{ $t('quantidade_horas_por_uc')}}: {{this.supportHoursLimit/supports.length}}</h6>-->
              <br>
              <b-table striped hover :items="supports" :fields="fields">
              <template v-slot:cell(actions)="row">
                <b-button
                  type="submit"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                  v-on:click.prevent="editSupportHours(row.item)"
                >{{ $t('editar_quantidade_horas_utilizadas') }}</b-button>
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
      @save-support="setSupportHours"
      @cancel-support="cancelSupport()"
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
      supportHoursLimit:null,
      user:null,
      fields: [
        {
          key: "nome",
          label: this.$t("nome"),
          sortable: true
        },
        {
          key: "semester",
          label: this.$t("semestre"),
          sortable: true
        },
        {
          key: "hours",
          label: this.$t("horas_acompanhamento"),
          sortable: true
        },
        {
          key: "actions",
          label: this.$t("ação")
        }
      ]
    };
  },
  methods: {
    getTotalHours(){
      axios
          .get("api/getTotalSupportHours/"+this.user.id)
          .then(response => {
              this.supportHoursLimit = response.data})
            .catch(error => {
                console.log(error)
            })
    },
    getHours() {
      axios
        .get("api/supportHours/"+this.user.id)
        .then(response => {

          this.totalHours = 0;
          this.supports = response.data;
          this.loading = false;

          this.supports.forEach(element => {
            this.totalHours += element.hours;
          });
        })
        .catch(error => {
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
              .then(response => {
                  this.user = response.data;
                  this.getTotalHours();
                  this.getHours();

              })
              .catch(error => {

                  console.log(error);
              });
      },
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
    cancelSupport: function() {
      this.currentSupport = null;
    }
  },
  created() {
    this.getAuth();
  }
};
</script>
