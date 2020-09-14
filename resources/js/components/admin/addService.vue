<template>
    <div>
        <br>
        <h4>Configurar Serviços</h4>
        <hr>
        <ValidationObserver v-slot="{ handleSubmit }">
            <ValidationProvider name="email" rules="required|email" v-slot="{ errors }">
                <label for>Email</label>
                <b-form-input v-model="data.email"></b-form-input>
                <code>{{ errors[0] }}</code>
            </ValidationProvider>
            <br>
            <ValidationProvider name="service" rules="required" v-slot="{ errors }">
                <label for>Serviço</label>
            <b-form-select name="service" v-model="data.service" class="mb-3">
                    <option :value="null" disabled>-- Selecionar o Serviço pretendido --</option>
                    <option value="SAPE">SAPE</option>
                    <option value="SAS">SAS</option>
                    <option value="UED">UED</option>
                    <option value="SA">SA</option>
                    <option value="CRID">CRID</option>
                    <option value="DST">DST</option>
                </b-form-select>
                <code>{{ errors[0] }}</code>
            </ValidationProvider>
            <br>
            <div class="text-center">
            <b-button variant="outline-success" v-on:click.prevent="handleSubmit(save)">Adicionar</b-button>
            </div>
        </ValidationObserver>
    </div>
</template>

<script>
  export default {
    name: 'addService',
    data(){
        return{
            data:{
                email:"",
                service:null
            }
        }
    },
    methods:{
        save(){
            axios
                .post('/api/addService',{'email':this.data.email,'service':this.data.service})
                .then(response=>{
                    this.$toasted.success("Serviço adicionado.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                })
                .catch(error=>{
                    this.$toasted.error(
                        ""+error.message,
                        {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });
                });
        }
    }
  };
</script>

<style scoped>

</style>
