<template>
    <div class="container mt-3" v-if="user">
        <div class="form-group">
            <h4>Estudante: </h4>
            <label for="inputName">Nome</label>
            <input type="text" class="form-control" v-model="user.name" name="name" id="inputName" disabled>
        </div>

        <div class="form-group">
            <label for="inputEmail">E-mail</label>
            <input type="text" class="form-control" v-model="user.email" name="email" id="inputEmail" disabled>
        </div>

        <label for="">Plano inclusao</label>

        <div v-if="plan">
            <b-form-textarea id="textarea" v-model="plan.plan" placeholder="Plano inclusao..." rows="3" max-rows="6">
        </b-form-textarea>

        <div class="dropdown-divider"></div>

        <label for="">Plano diagnostico</label>
        <b-form-textarea id="textarea" v-model="plan.diagnostic" placeholder="Plano diagnostico..." rows="3"
            max-rows="6">
        </b-form-textarea>
        </div>

        <div v-else>

            <b-form-textarea id="textarea" v-model="data.plan" placeholder="Plano inclusao..." rows="3" max-rows="6">
        </b-form-textarea>

        <div class="dropdown-divider"></div>

        <label for="">Plano diagnostico</label>
        <b-form-textarea id="textarea" v-model="data.diagnostic" placeholder="Plano diagnostico..." rows="3"
            max-rows="6">
        </b-form-textarea>


        </div>

        

        <b-button variant="outline-success" v-on:click.prevent="save()">Guardar</b-button>
        <b-button variant="outline-info" v-on:click.prevent="cancel()">Cancelar</b-button>
    </div>
</template>
<script>
    export default {
        props: ["user", "plan"],
        data: function () {
            return {
                data: {
                    plan: '',
                    diagnostic: '',
                    studentId: ''
                },

            };

        },
        methods: {
            cancel() {
                this.$emit("cancel-edit2");
            },
            save: function () {
                this.data.studentId = this.user.id;
                if(this.plan != null){
                    this.data.plan = this.plan.plan;
                    this.data.diagnostic = this.plan.diagnostic;
                    

                    axios
                    .post("api/updatePlan/" + this.plan.id, this.data)
                    .then(response => {
                        this.$toasted.success("Guardado com sucesso.", {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });
                        this.$emit("cancel-edit2");
                        this.data = {};
                    })
                    .catch(error => {
                        console.log(error);
                        this.$toasted.error("Erro ao guardar. Por favor tente novamente.", {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });
                    });


                } else {
                    axios
                    .post("api/setPlan/", this.data)
                    .then(response => {
                        this.$toasted.success("Guardado com sucesso.", {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });
                        this.$emit("cancel-edit2");
                        this.data = {};
                    })
                    .catch(error => {
                        console.log(error);
                        this.$toasted.error("Erro ao guardar. Por favor tente novamente.", {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });
                    });
                }
                
                


            },
            handleFiles() {
                this.files = [];
                let uploadedFiles = this.$refs.files.files;
                for (var i = 0; i < uploadedFiles.length; i++) {
                    this.files.push(uploadedFiles[i]);
                }
            },
        },
        mounted() {},
    };

</script>
