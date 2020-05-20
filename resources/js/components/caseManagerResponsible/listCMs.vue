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

        <div>
        <input type="radio" id="temporary" value="temporary" v-model="substitutionType">
        <label for="one">Temporary</label>
        <br>
        <input type="radio" id="permanent" value="permanent" v-model="substitutionType">
        <label for="two">Permanent</label>
        <br>
            <div v-if="substitutionType==='temporary'">
        <span>Introduza a data de início e fim</span>
             <date-picker v-model="substitutionDates" range=true valuetype="'YYYY-MM-DD'" type="date" format='YYYY-MM-DD' lang="pt-br"></date-picker>
            </div>
        </div>
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

    <h3>Substituições ativas</h3>
    <b-table striped hover v-if="activeSubstitutions!=null" :items="activeSubstitutions" :fields="fieldsActiveSubstitutes">
        <template slot="actions" slot-scope="row">
            <button class="btn btn-danger" v-on:click.prevent="cancelarSubstituicao(row.item)">Cancelar Substituição</button>
        </template>
    </b-table>
    <h4 v-else>Não existem substituições em curso</h4>
    <button class="btn btn-danger" v-on:click.prevent="cancelarSubstituicao(activeSubstitutions[0])">Cancelar Substituição</button>

</div>
</template>

<script>
  export default {
    name: 'listCMs',
      data(){
        return{
            substitutionDates:null,
            substitutionType:null,
            activeSubstitutions:null,
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
            fieldsActiveSubstitutes: [
                {
                    key: "mainCaseManagerName",
                    label: "Nome Gestor Caso Principal",
                    sortable: true
                },
                {
                    key: "emailMainCaseManager",
                    label: "Email Gestor Caso Principal",
                    sortable: true
                },
                {
                    key: "caseManagerName",
                    label: "Nome Gestor Caso Substituto",
                    sortable: true
                },
                {
                    key: "caseManagerEmail",
                    label: "Email Gestor Caso Substituto",
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
              if(this.substitutionDates==null){
                  this.substitutionDates[0]=this.substitutionType;
                  this.substitutionDates[1]=this.substitutionType;
              }else{
                  this.substitutionDates[0].setHours(this.substitutionDates[0].getHours()+1);
                  this.substitutionDates[1].setHours(this.substitutionDates[1].getHours()+1);


                  let partsStartDate = this.substitutionDates[0].toISOString().split('T');
                  var startDate = partsStartDate[0];
                  let partsEndDate = this.substitutionDates[1].toISOString().split('T');
                  var endDate = partsEndDate[0];

                  console.log(startDate);
                  console.log(endDate);
              }

              if(this.substitute.email!=null && this.emailEnee!=="default"){

                  axios
                      .post("api/setCmSubstitute",{
                          "emailSubstituteCaseManager":this.substitute.email,
                          "nameSubstituteCaseManager":this.substitute.name,
                          "emailCurrentCaseManager": this.currentCaseManager.email,
                          "nameCurrentCaseManager": this.currentCaseManager.name,
                          "startDate": startDate,
                          "endDate": endDate,
                          "substitutionType" : this.substitutionType
                          }
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
          },
          getActiveSubstitutions(){
              axios.get('api/getActiveSubstitutions')
                .then(response=>{

                    this.activeSubstitutions= response.data.length===0 ? null : response.data;

                }).catch(error=>{
                    console.log(error);
              })
          },
          cancelarSubstituicao(row){
              axios.put('/api/cancelSubstitution',{"emailMainCaseManager":row.emailMainCaseManager})
                  .then(response=>{

                      this.activeSubstitutions.splice(row);

                      this.activeSubstitutions= this.activeSubstitutions.length===0 ? null : this.activeSubstitutions;

                      this.$toasted.success("Pedido efetuado com sucesso.", {
                          duration: 4000,
                          position: "top-center",
                          theme: "bubble"
                      });
                  })
                  .catch(error=>{
                      console.log(error);
                  })
          }
      },
      created() {
        this.getAllCaseManagers();
        this.getActiveSubstitutions();
      }
  };
</script>

<style scoped>

</style>
