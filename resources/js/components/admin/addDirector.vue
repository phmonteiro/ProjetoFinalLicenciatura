<template>
    <div><br><br>
        <b-input-group prepend="Email do Responsável de Gestores de Caso" class="mt-sm-4 p-sm-4 w-75">
            <b-form-input v-model="emailDirector"></b-form-input>
            <b-input-group-append>
                <b-form-select name="service" v-model="school" class="mb-3">
                    <option :value="null" disabled>-- Selecionar a escola --</option>
                    <option value="ESTG">ESTG</option>
                    <option value="ESECS">ESECS</option>
                    <option value="ESAD">ESAD</option>
                    <option value="ESTM">ESTM</option>
                    <option value="ESS">ESS</option>
                </b-form-select>
            </b-input-group-append>
            <b-input-group-append>
                <b-button variant="outline-success" v-on:click.prevent="save()">Adicionar</b-button>
            </b-input-group-append>
        </b-input-group>
    </div>
</template>

<script>
    export default {
        name: 'addDirector',
        data(){
            return {
                emailDirector:null,
                school:null,
            }
        } ,
        methods:{
            getDirectors(){

            },
            save(){
                axios.post('api/setDirector',{
                    "emailDirector" : this.emailDirector
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
