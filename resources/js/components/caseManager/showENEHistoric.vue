<template>
    <div>

        <div class="container mt-3">
            <div class="form-group">
                <label for="inputName">Nome</label>
                <input
                    type="text"
                    class="form-control"
                    v-model="student.name"
                    name="name"
                    id="inputName"
                    disabled
                />
            </div>

            <div class="form-group">
                <label for="inputEmail">E-mail</label>
                <input
                    type="text"
                    class="form-control"
                    v-model="student.email"
                    name="email"
                    id="inputEmail"
                    disabled
                />
            </div>
        </div>
        <b-container>
            <b-row>
                <b-col></b-col>
                <b-col>
                    <div class="loader">
                        <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50" />
                    </div>
                </b-col>
                <b-col></b-col>
            </b-row>
        </b-container>
        <br>
        <div class="text-center">
            <b-button @click="cancel()">Fechar</b-button>
        </div>
        <br>
        <b-table striped hover :items="historic" :fields="fields">
        </b-table>
        <div v-if="historic" class="text-center">
            <b-button @click="cancel()">Fechar</b-button>
        </div>
    </div>
</template>

<script>
    export default {
        props:["student"],
        name: 'showENEHistoric',
        data(){
            return{
                loading:true,
                historic:[],
                fields: [
                    {
                        key: "date",
                        label: "Data",
                        sortable: true
                    },
                    {
                        key: "description",
                        label: "Descrição",
                        sortable: true
                    },
                ],
            }
        },
        methods:{
            cancel(){
                this.$emit("cancel-historic");
            },
            getENEHistoric() {
                axios
                    .get('api/getENEHistories/'+this.student.email)
                    .then(response=>{
                        this.historic = response.data;
                        this.loading=false;
                    })
                    .catch(error=>{
                        console.log(error)
                    })
            },
        },
        created(){
            this.getENEHistoric();
        },

    };
</script>

<style scoped>

</style>
