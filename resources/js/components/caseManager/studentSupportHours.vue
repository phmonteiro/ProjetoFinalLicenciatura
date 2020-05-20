<template>
    <b-container>
        <b-row>
            <b-col>
                <div v-if="supports">
                    <h2>{{ $t('acompanhamento_individualizado') }}</h2>
                    <h5>{{ $t('quantidade_horas_utilizadas') }}: {{this.totalHours}}</h5>
                    <h5>{{ $t('quantidade_horas_total')}}: {{this.supportHoursLimit}}</h5>
                    <b-table striped hover :items="supports" :fields="fields">
                        <template slot="actions" slot-scope="row">
                            <b-button
                                type="submit"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                                v-on:click.prevent="editSupportHours(row.item)"
                            >{{ $t('editar_quantidade_horas_utilizadas') }}</b-button>
                        </template>
                    </b-table>
                </div>
            </b-col>
        </b-row>
        <div class="text-center">
            <b-button @click="cancel()">Fechar</b-button>
        </div>
    </b-container>
</template>

<script>
  export default {
    name: 'studentSupportHours',
    props:['student'],
    data: function(){
        return {
            supports: null,
            totalHours:null,
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
                }
            ]

        };
    }  ,
      methods:{
        cancel(){
            this.$emit("cancel-show-hours");
        },
          getTotalHours(){
              axios
                  .get("api/getTotalSupportHours/"+this.student[0].id)
                  .then(response => {
                      this.supportHoursLimit = response.data})
                  .catch(error => {
                      console.log(error)
                  })
          },
          getHours() {

              axios
                  .get("api/supportHours/"+this.student[0].id)
                  .then(response => {

                      this.totalHours = 0;
                      this.supports = response.data;

                      this.supports.forEach(element => {
                          this.totalHours += element.hours;
                      });
                  })
                  .catch(error => {
                      console.log(error);
                  });
          },
      },
      created() {
          this.getHours();
          this.getTotalHours();
      }
  };
</script>

<style scoped>

</style>
