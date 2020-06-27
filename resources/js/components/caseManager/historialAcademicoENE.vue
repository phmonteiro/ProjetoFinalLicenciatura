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
            <div class="form-group">
                <label for="inputCourse">Curso</label>
                <input
                    type="text"
                    class="form-control"
                    v-model="student.course"
                    name="email"
                    id="inputCourse"
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
        <b-table striped hover :items="historial" :fields="fields">
        </b-table>
        <div class="text-center">
            <b-button @click="cancel()">Fechar</b-button>
        </div>
    </div>
</template>

<script>
  export default {
    props:["student"],
    name: 'historialAcademicoENE',
    data(){
        return{
            loading:true,
            historial:[],
            fields: [
                {
                    key: "nome",
                    label: "Nome",
                    sortable: true
                },
                {
                    key: "semester",
                    label: "Semestre",
                    sortable: true
                },
                {
                    key: "estado",
                    label: "Estado",
                    sortable: true
                },
            ],
        }
    },
    methods:{
        cancel(){
            this.$emit("cancel-academic");
        },
        getHistorialAcademico() {
            axios
                .get('api/getAcademicRecord/'+this.student.id)
                .then(response=>{
                    this.historial = response.data;
                    this.loading=false;
                })
                .catch(error=>{
                    console.log(error)
                })
        },
    },
    created(){
        this.getHistorialAcademico();
        this.$nextTick(() => this.$refs.historialacademico.$el.children[0].focus());

    },

  };
</script>

<style scoped>

</style>
