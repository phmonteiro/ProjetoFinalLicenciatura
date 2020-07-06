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
        <b-table v-if="historic!=null && historic.length>0" striped hover :items="historic" :fields="fields">
        </b-table>
        <h4 v-else>Não existe histórico para mostrar.</h4>
        <div v-if="historic" class="text-center">
            <b-button @click="cancel()">Fechar</b-button>
        </div>
    </div>
</template>

<script>
    export default {
        props:["student","historic","loading"],
        name: 'showENEHistoric',
        data(){
            return{
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
        },
        };
</script>

<style scoped>

</style>
