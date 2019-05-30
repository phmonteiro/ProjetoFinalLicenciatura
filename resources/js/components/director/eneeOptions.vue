<template>
    <div class="container mt-3" v-if="user">
        <div class="form-group">
            <h4>Estudante:</h4>
            <label for="inputName">Nome</label>
            <input type="text" class="form-control" v-model="user.name" name="name" id="inputName" disabled>
        </div>

        <div class="form-group">
            <label for="inputEmail">E-mail</label>
            <input type="text" class="form-control" v-model="user.email" name="email" id="inputEmail" disabled>
        </div>

        <b-form-group id="input-group-3" label="Duracao da NEE:" label-for="input-3">
            <b-form-select id="input-3" v-model="form.duration" :options="durationOpts" required></b-form-select>
        </b-form-group>

        <b-form-group label="Apoios ao estudante">
            <b-form-checkbox-group v-model="studentSupports" :options="options" switches></b-form-checkbox-group>
        </b-form-group>
        
        <div class="form-group">
            <button type="submit" class="btn btn-secondary" name="ok" v-on:click.prevent="save()">Guardar</button>
            <button class="btn btn-secondary" v-on:click.prevent="cancel()">Cancelar</button>
        </div>
    </div>
</template>
<script>
    export default {
        props: ["user", "studentSupports"],
        data: function () {
            return {
                data: {
                    cmEmail: "",
                    studentName: ""
                },
                meeting: {
                    info: null,
                    date: null
                },
                form: {
                    duration: null
                },
                durationOpts: [{
                        text: "Escolha uma",
                        value: null
                    },
                    "Temporária",
                    "Permanente"
                ],
                options: [
                    
                ],
                childData: ''
            };
        },
        methods: {
            cancel() {
                this.$emit("cancel-edit");
            },
            save: function () {
                let data= {
                    email: this.user.email,
                    supports: this.studentSupports
                }
                this.$emit("save-user", data);
            },
            getAllSupports() {
                axios.get("api/getSupports").then(
                        response => {
                            this.options = response.data;
                            console.log("supports aqui"+this.options);
                        }
                    )
                    .catch(error => {
                        console.log(error);
                    });
            },
            
        },
        created() {
            this.getAllSupports();
        },
    };

</script>
