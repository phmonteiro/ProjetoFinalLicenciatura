<template>
<div>
<b-table striped hover v-if="caseManagers!=null" :items="caseManagers" :fields="fields">
    <template slot="actions" slot-scope="row">
                <button class="btn btn-success" v-on:click.prevent="substituir(row.item)">Substituir</button>
    </template>
</b-table>
    <button class="btn btn-success" v-on:click.prevent="toggleSubstituir(caseManagers[1])">Substituir</button>

    <div v-if="showSubstitutes">
        <h4>Selecionar Gestor Caso substituto</h4>
        <br>
        <select v-model="substitute">
            <option value="default" disabled>Por favor selecione um</option>
            <option  v-for="cm in substitutes" :value="cm">{{cm.email}}</option>
        </select>
        <br>
        <br>
        <b-button @click="save()">Guardar</b-button>
        <b-button @click="cancelAddSubstitute()">Cancelar</b-button>
    </div>
</div>
</template>

<script>
  export default {
    name: 'listCMs',
      data(){
        return{
            substitute:"default",
            showSubstitutes:false,
            currentCaseManager:null,
            caseManagers:[],
            substitutes:[],
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
          cancelAddSubstitute(){
              this.showSubstitutes=false;
          },
          getAllCaseManagers(){
              axios.get("api/getAllCMs")
                  .then(response=>{
                      this.caseManagers=response.data;
                  })
                  .catch(error=>{
                      console.log(error);
                  })
          },
          toggleSubstituir(row){
              this.currentCaseManager= row;
              this.caseManagers.forEach(cm=>{
                  if(cm.email !== row.email){
                      this.substitutes.push(cm);
                  }
              });
              this.showSubstitutes=true;
          },
          save(){
              if(this.substitute.email!=null && this.emailEnee!=="default"){
                  axios
                      .post("api/setCmSubstitute",{"emailSubstituteCaseManager":this.substitute.email,
                          "emailCurrentCaseManager": this.currentCaseManager.email}
                      )
                      .then(response=>{

                          this.showSubstitutes= false;
                          this.$toasted.success("Substituição realizada com sucesso.", {
                              duration: 4000,
                              position: "top-center",
                              theme: "bubble"
                          });
                      }).catch(error=>{
                      console.log(error);
                  })
              }
          }
      },
      created() {
        this.getAllCaseManagers();
      }
  };
</script>

<style scoped>

</style>
