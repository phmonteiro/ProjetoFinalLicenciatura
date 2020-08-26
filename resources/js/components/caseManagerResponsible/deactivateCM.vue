<template>
    <div>
        <br>
        <h3>Ativar/Desativar Gestores de Caso</h3>
        <br>
        <p>Apenas é possível desativar Gestores de Caso que não tenham quaisquer ENEs atribuídos.</p>
        <br>
        <div v-if="caseManagers!=null && caseManagers.length!==0">
            <b-table striped hover :items="caseManagers" :fields="fields">
                <template v-slot:cell(actions)="row">
                    <button v-if="row.item.inactive===1" @click.prevent="ativateCM(row.item)" class="btn btn-success">Ativar</button>
                    <button v-else :disabled="!CMWithENEEmails.includes(row.item.email)" @click.prevent="deactivateCM(row.item)" class="btn btn-danger">Desativar</button>
                </template>
            </b-table>
        </div>
        <div v-else>
            <br>
            <h4>De momento não há Gestores de Caso na plataforma.</h4>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'deactivateCM',
    data(){
        return{
            CMWithENEEmails:[],
            caseManagers:null,
            fields: [
                {
                    key: "name",
                    label: "Nome",
                    sortable: true
                },
                {
                    key: "email",
                    label: "Email",
                    sortable: true
                },
                {
                    key: "actions",
                    label: "Ações",
                    sortable: true
                }
            ],
        }
    },
    methods:{
        deactivateCM(item){
            let confirmed = confirm("Após desativar a conta o Gestor de Caso em questão irá ficar sem acesso à Plataforma. Tem a certeza que pretende desativar a conta de "+item.name+"?");
            if(confirmed){
            axios
                .post('/api/deactivateCM/'+item.email)
                .then(response=>{
                    item.inactive = 1;
                    this.$toasted.success("Gestor de Caso desativado.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                })
                .catch(error=>{
                    console.log(error);
                    this.$toasted.error("Erro ao desactivar Gestor de Caso. Por favor tente novamente.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                });
            }
        },
        ativateCM(item){
            axios
                .post('/api/activateCM/'+item.email)
                .then(response=>{
                    item.inactive = null;
                    this.$toasted.success("Gestor de Caso ativado.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                })
                .catch(error=>{
                    console.log(error);
                    this.$toasted.error("Erro ao activar Gestor de Caso. Por favor tente novamente.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                });
        },
        getAllCMs(){
            axios
                .get('/api/getAllCMsActivateDeactivate')
                .then(response=>{
                    this.caseManagers = response.data;
                })
                .catch(error=>{
                    console.log(error);
                });
        },
        getCMwithENE(){
            axios
                .get('/api/getCMWithENE')
                .then(response=>{
                    this.CMWithENEEmails = response.data;
                })
                .catch(error=>{
                    console.log(error);
                })
        }
    },
     mounted() {
        this.getAllCMs();
        this.getCMwithENE();
     }
  };
</script>

<style scoped>

</style>
