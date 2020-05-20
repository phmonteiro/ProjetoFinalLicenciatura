<template>
        <div v-if="onSubstitution" class="text-center">
            <h3>Gestor Caso substituto: {{onSubstitution}}</h3><br>

            <h3>Contacte o Responsável pelos Gestores Caso se pretender cancelar a substituição</h3>
            <h5>Email: {{emailCaseManagerResponsible}}</h5>
        </div>
        <div v-else>
            <h4>Para requisitar uma substituição contacte o Responsável dos Gestores de caso.</h4><br>
            <h5>Email: {{emailCaseManagerResponsible}}</h5>
        </div>
</template>

<script>
  export default {
    name: 'substitution',
      data(){
        return{
            emailCaseManagerResponsible:null,
            caseManagersByStudent:null,
            onSubstitution:null,
        }
      },
      methods:{
        getEmailCaseManagerResponsible(){
            axios.get('api/getEmailCaseManagerResponsible')
                .then(response=>{
                    this.emailCaseManagerResponsible = response.data;
                })
                .catch(error=>{
                    console.log(error);
                })
        },
          checkSubstitution(){
            axios.get("/api/checkSubstitution")
              .then(response=>{

                  if(response.data==="false"){
                      this.onSubstitution=false;
                  }else{
                      this.onSubstitution=response.data;
                  }
              })
              .catch(error=>{
                  console.log(error);
              })
          }
      },
      created() {
        this.checkSubstitution();
        this.getEmailCaseManagerResponsible();
      },
      computed: {
          user: function() {
              return this.$store.state.user;
          }
      }
  };
</script>

<style scoped>

</style>
