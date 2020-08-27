<template>
    <div>
    <b-container>
        <b-row>
            <b-col></b-col>
            <b-col>
                <div class="loader">
                    <ClipLoader sizeUnit="px" class="loading" v-if="supportHoursLoading" :size="50" />
                </div>
            </b-col>
            <b-col></b-col>
        </b-row>
    </b-container>
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
                <div v-else>
                    <h4>O estudante não tem Unidades Curriculares atribuídas.</h4>
                </div>
            </b-col>
        </b-row>
        <div class="text-center">
            <b-button @click="cancel()">Fechar</b-button>
        </div>
    </b-container>
    </div>
</template>

<script>
  export default {
    name: 'studentSupportHours',
      props: ["loading", "student", "supports", "totalHours", "supportHoursLimit"],
    data: function(){
        return {
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
      },
    computed: { supportHoursLoading: function() {
        return this.loading;
        }
    },
  };
</script>

<style scoped>

</style>
