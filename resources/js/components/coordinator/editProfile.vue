<template>
<div>
    <hr>
    <h3>Adicionar Email Secundário</h3>
    <br>
    <label for="secondaryEmail">Email Secundário:</label>
    <br>
    <input class="w-25" id="secondaryEmail" type="text" v-model="secondaryEmail">
    <div class="text-center">
        <br>
        <button class="btn btn-outline-success" @click.prevent="setSecondaryEmail()">Gravar</button>
    </div>
</div>
</template>

<script>
  export default {
    name: 'editProfile',
    data(){
        return{
            secondaryEmail:"",
        }
    },
    methods:{
        getSecondaryEmail(){
            axios
            .get('/api/getSecondaryEmail')
                .then(response=>{
                    console.log(response.data);
                    this.secondaryEmail = response.data;
                })
                .catch(error=>{
                    console.log(error);
                })
        },
        setSecondaryEmail(){
            axios
            .post('/api/setSecondaryEmail',{'secondaryEmail':this.secondaryEmail})
                .then(response=>{
                    this.$toasted.success("Email adicionado com sucesso.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                })
                .catch(error=>{
                    console.log(error);
                    this.$toasted.error(
                        "Error ao adicionar email. Por favor tente novamente.",
                        {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        }
                    );
                })
        }
    },
    mounted() {
        this.getSecondaryEmail();
    }
  };
</script>

<style scoped>

</style>
