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

        <div v-if="studentCMs">
            <h4>Gestor(es) de caso</h4>
            <div v-for="(cm, index) in studentCMs" :key="index">    
                <b-container>
                    <b-row>
                        <b-col class="p-3"><li>Nome: {{cm.caseManagerName}}, Email: {{cm.caseManagerEmail}} </li></b-col>
                        <b-col><b-button variant="btn btn-danger" v-on:click.prevent="removeCM()">remover</b-button></b-col>
                    </b-row>
                </b-container>
            </div>
        </div>

        <b-input-group prepend="Email do novo gestor caso" class="mt-3 p-3">
            <b-form-input v-model="data.cmEmail"></b-form-input>
            <b-input-group-append>
                <b-button variant="outline-success" v-on:click.prevent="save()">Atribuir</b-button>
            </b-input-group-append>
        </b-input-group>
    </div>
</template>
<script>
    export default {
        props: ["user", "studentCMs"],
        data: function () {
            return {
                data: {
                    cmEmail: '',
                    studentName: ''
                },
                meeting: {
                    info: null,
                    date: null
                }

            };
        },
        methods: {
            cancel() {
                this.$emit("cancel-edit");
            },
            save: function () {
                this.setCM();
                this.$emit("save-user");
            },
            setCM() {
                this.data.studentName = this.user.name;
                axios
                    .post("api/setCM/" + this.user.id, this.data)
                    .then(response => {
                        this.$toasted.success("Gestor de caso atribuido.", {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });
                    })
                    .catch(error => {
                        console.log(error);
                        this.$toasted.error(
                            "Erro ao atribuir gestor caso. Por favor tente novamente.", {
                                duration: 4000,
                                position: "top-center",
                                theme: "bubble"
                            }
                        );
                    });
            },
            removeCM(){
                axios
                    .post("api/removeCM/" + this.user.email)
                    .then(response => {
                        this.$emit('refreshCMs');
                        this.$toasted.success("Gestor de caso removido.", {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });
                    })
                    .catch(error => {
                        console.log(error);
                        this.$toasted.error(
                            "Erro ao remover gestor caso. Por favor tente novamente.", {
                                duration: 4000,
                                position: "top-center",
                                theme: "bubble"
                            }
                        );
                    });
            }

        },
        mounted() {},
    };

</script>
