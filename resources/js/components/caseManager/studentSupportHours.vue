<template>
    <b-container>
        <b-row>
            <b-col>
                <div v-if="supports">
                    <h2>Acompanhamento Individualizado</h2>
                    <br>
                    <h5>Total de número de horas de apoio:  {{this.supportHoursLimit}} horas</h5>
                    <h5>Número de horas usadas:  {{this.totalHours}} horas</h5>
                    <h5>Horas Disponíveis:  {{Number(this.supportHoursLimit)-Number(this.totalHours)}} horas</h5>
                    <br>
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
                    label: "Horas de Acompanhamento",
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
