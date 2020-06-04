<template>
    <div><br><br>
        <b-input-group prepend="Email do Responsável de Gestores de Caso" class="mt-sm-4 p-sm-4 w-75">
            <b-form-input v-model="emailResponsibleCaseManager"></b-form-input>
            <b-input-group-append>
                <b-button variant="outline-success" v-on:click.prevent="save()">Adicionar</b-button>
            </b-input-group-append>
        </b-input-group>
    </div>
</template>

<script>
  export default {
    name: 'addResponsibleCM',
     data(){
        return {
            emailResponsibleCaseManager:null,
        }
     } ,
      methods:{
        save(){
            axios.post('api/setResponsibleCM',{
                "emailResponsibleCaseManager" : this.emailResponsibleCaseManager
            }).then(response=>{
                if(response.status===200){
                    this.$toasted.success(response.data, {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                }else if(response.status===201){
                    this.$toasted.error(response.data.message, {
                        duration: 40000,
                        position: "top-center",
                        theme: "bubble"
                    });
                }
                console.log(response);
            }).catch(error=>{
                console.log(error);
            })
        }
      }
  };
</script>

<style scoped>

</style>
