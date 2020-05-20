<template>
<div><br><br>
    <b-input-group prepend="Email do novo gestor caso" class="mt-sm-4 p-sm-4 w-75">
        <b-form-input v-model="cmEmail"></b-form-input>
        <b-input-group-append>
            <b-button variant="outline-success" v-on:click.prevent="save()">Adicionar</b-button>
        </b-input-group-append>
    </b-input-group>
</div>
</template>

<script>
  export default {
    name: 'addCM',
      data(){
        return {
            cmEmail:""
        }
      },
      methods:{
        save(){
           if(!this.cmEmail.includes("ipleiria.pt") || this.cmEmail.includes("my.ipleiria.pt")){
                this.$toasted.error(
                    "Insira um email válido.",
                    {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });

                this.loading=false;
                this.cmEmail="";
            }else{
            axios.post('api/addCM', {'cmEmail':this.cmEmail})
            .then(response=>{

                if(response.data===418){
                    this.$toasted.error(
                        "Utilizador já existe, entre em contacto com o administrador para alterar o seu tipo",
                        {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });

                }else if(response.data===409){
                    this.$toasted.info(
                        "Gestor Caso já existe!",
                        {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });

                }else{
                    this.$toasted.success("Gestor de caso adicionado.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                }
            })
            .catch(error=>{
                console.log(error);
            });
        }
        }
      }
  };
</script>

<style scoped>

</style>
