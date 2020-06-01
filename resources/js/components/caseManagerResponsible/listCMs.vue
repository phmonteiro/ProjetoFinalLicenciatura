<template>
<div>
<b-table striped hover v-if="caseManagers!=null" :items="caseManagers" :fields="fields">
    <template v-slot:cell(actions)="row">
        <button v-if="!emailMainCMActiveSubstitution.contains(row.item.email)" class="btn btn-success" v-on:click.prevent="toggleSubstituir(row.item)">Substituir</button>
    </template>
</b-table>


    <div v-if="showSubstitutes">
        <h4>Selecionar Gestor Caso substituto</h4>
        <br>
        <ValidationObserver v-slot="{ handleSubmit }">
        <ValidationProvider name="substitute" rules="isDefault" v-slot="{ errors }">
            <select v-model="substitute">
                <option value="default" disabled>Selecionar Gestor de Caso</option>
                <option  v-for="cm in substitutes" :value="cm">{{cm.email}}</option>
            </select><br>
            <code>{{ errors[0] }}</code>
        </ValidationProvider>
        <br>
        <br>
        <div>

        <ValidationProvider name="subType" rules="required" v-slot="{ errors }">
            <input type="radio" id="temporary" value="temporary" v-model="substitutionType">
            <label for="one">Substituição Temporária</label>
            <br>
            <input type="radio" id="permanent" value="permanent" v-model="substitutionType">
            <label for="two">Substituição Permanente</label><br>
            <code>{{ errors[0] }}</code>
        </ValidationProvider>
        <br>
            <div v-if="substitutionType==='temporary'">
        <span>Introduza a data de início e fim</span>
             <date-picker required v-model="substitutionDates" range=true valuetype="'YYYY-MM-DD'" type="date" format='YYYY-MM-DD' lang="pt-br"></date-picker>
            </div>
            <div v-if="substitutionType==='permanent'">
                <span>Introduza a data de início</span>
                <date-picker required v-model="substitutionDates" valuetype="'YYYY-MM-DD'" type="date" format='YYYY-MM-DD' lang="pt-br"></date-picker>
            </div>
        </div>
        <br>
        <b-button @click="handleSubmit(save)">Guardar</b-button>
        <b-button @click="cancelAddSubstitute()">Cancelar</b-button>
        </ValidationObserver>

    </div>
    <br>

    <h3>Substituições ativas</h3>
    <b-table striped hover v-if="activeSubstitutions!=null" :items="activeSubstitutions" :fields="fieldsActiveSubstitutes">
        <template v-slot:cell(actions)="row">
            <button class="btn btn-danger" v-on:click.prevent="cancelarSubstituicao(row.item)">Cancelar Substituição</button>
        </template>
    </b-table>
    <h4 v-else>Não existem substituições em curso</h4>
</div>
</template>

<script>
  export default {
    name: 'listCMs',
      data(){
        return{
            emailMainCMActiveSubstitution:[],
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
              this.substitutes=[];
              this.currentCaseManager= row;
              this.caseManagers.forEach(cm=>{
                  if(cm.email !== row.email){
                      this.substitutes.push(cm);
                  }
              });
              this.showSubstitutes=true;
          },
          save(){
              var startDate;
              var endDate;

              if(this.substitutionType==="permanent"){
                  endDate="";
                  this.substitutionDates.setHours(this.substitutionDates.getHours()+1);
                  let partsStartDate = this.substitutionDates.toISOString().split('T');
                  startDate = partsStartDate[0];

              }else if(this.substitutionType==="temporary"){
                  this.substitutionDates[0].setHours(this.substitutionDates[0].getHours()+1);
                  this.substitutionDates[1].setHours(this.substitutionDates[1].getHours()+1);


                  let partsStartDate = this.substitutionDates[0].toISOString().split('T');
                  startDate = partsStartDate[0];
                  let partsEndDate = this.substitutionDates[1].toISOString().split('T');
                  endDate = partsEndDate[0];
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
                          this.substitute = "default";
                          this.substitutionType = null;
                          this.substitutionDates = null;

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

                    if(this.activeSubstitutions!=null){
                        this.activeSubstitutions.forEach(cm=>{
                            this.emailMainCMActiveSubstitution.push(cm.emailMainCaseManager);
                        })
                    }

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
