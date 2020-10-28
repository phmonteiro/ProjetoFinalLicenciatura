<template>
    <div>
        <h3>Configuração de Web Services</h3>
        <div>
            <br>
            <div class="d-inline-block">
                <span>Web Service Estado UCs Estudante</span>
                <b-button disabled size="sm" class="btn btn-secondary" @click.prevent="showConfigs('Web Service Estado das UCs do Aluno')">Configurar</b-button>
            </div>
            <br>
            <br>
            <div class="d-inline-block">
                <span>Web Service Dados Estudante</span>
                <b-button style="margin-left: 35px" @click.prevent="showConfigs('Web Service Dados dos Alunos')" size="sm" class="btn btn-secondary">Configurar</b-button>
            </div>
        </div>
        <div>
<!--            links dos web services  ....-->


        </div>
        <div v-if="showConfig">
            <br>
            <h4 class="text-center">{{title}}</h4>
            <b-input-group prepend="Link do Web Service" class="mt-sm-4 p-sm-4 w-100">
                <b-form-input v-model="linkWebService"></b-form-input>
            </b-input-group>
            <br>
            <b-table striped hover :items="parameters" :fields="fields">
                <template v-slot:cell(jsonParameter)="row">
                    <input type="text" v-model="row.item.jsonParameter">
                </template>
            </b-table>
            <div class="text-center">
                <button class="btn btn-outline-success" @click.prevent="save()">Gravar</button>
                <br>
                <br>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'webServiceManagement',
    data(){
        return{
            title:"",
            linkWebService:"",
            showConfig:false,
            parameters:[],
            fields:[
                {
                    key: "name",
                    label: "Nome",
                    sortable: true
                },
                {
                    key: "jsonParameter",
                    label: "Parametro",
                    sortable: true
                },
            ],
        }
    },
      methods:{
        save(){
            axios.post('/api/setWebServiceConfigs',{'linkWebService':this.linkWebService,'parameters':this.parameters})
                .then(response=>{
                    this.$toasted.success("Parâmetros atualizados com sucesso.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                })
                .catch(error=>{
                    this.$toasted.error(
                        "Error ao atualizar parâmetros. Por favor tente novamente.",
                        {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        }
                    );
                    console.log(error);
                });
        },
        showConfigs(title){
            this.title=title;
            this.showConfig = true;
        },
        getData(){
            axios.get('/api/getWebServiceConfigs')
                .then(response=>{
                    console.log(response.data);
                    this.linkWebService = response.data[0];
                    this.parameters = response.data[1];
                })
                .catch(error=>{
                    console.log(error);
                });
        }
      },
      mounted() {
        this.getData();
      }

  };
</script>

<style scoped>

</style>
