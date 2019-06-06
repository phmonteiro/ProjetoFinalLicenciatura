<template>
  <div>
    <edit-support-hours
      :support="currentSupport"
      @save-support="setSupportHours"
      @cancel-support="cancelSupport()"
    ></edit-support-hours>
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
            <h2>Acompanhamento personalizado</h2>
            <h3>Quantidade de horas utilizadas {{this.totalHours}}/40 ano</h3>
            <b-table striped hover :items="supports" :fields="fields">
              <template slot="actions" slot-scope="row">
                <button
                  type="submit"
                  class="btn btn-secondary"
                  data-dismiss="modal"
                  v-on:click.prevent="editSupportHours(row.item)"
                >Editar quantidade de horas</button>
              </template>
            </b-table>
          </div>
        </b-col>
      </b-row>
    </b-container>
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
      fields: [
        {
          key: "nome",
          label: "Nome",
          sortable: true
        },
        {
          key: "semester",
          label: "Semestre",
          sortable: true
        },
        {
          key: "hours",
          label: "Horas de acompanhamento",
          sortable: true
        },
        {
          key: "actions",
          label: "Acção"
        }
      ]
    };
  },
  methods: {
    getHours() {
      axios
        .get("api/supportHours")
        .then(response => {
          this.totalHours = 0;
          console.log(response.data);
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
    this.getHours();
  }
};
</script>

<style>
</style>
