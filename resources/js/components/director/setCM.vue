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

        <div class="form-group">
            <label for="inputType">E-mail do novo gestor caso:</label>
            <input type="text" class="form-control" v-model="data.cmEmail" name="cm" id="caseManager">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-secondary" name="ok" v-on:click.prevent="save()">Guardar</button>
            <button class="btn btn-secondary" v-on:click.prevent="cancel()">Cancelar</button>
        </div>
    </div>
</template>
<script>
    export default {
        props: ["user"],
        data: function () {
            return {
                data: 
                {
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
                        this.$toasted.success("O responsavel será notificado para aprovacao.", {
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
            }
        }
    };

</script>
