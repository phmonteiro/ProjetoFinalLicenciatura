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
            <label for="decision">Informacao</label>
            <input class="form-control" id="decision" v-model="data.info" name="decision" rows="3">
        </div>

        <div class="form-group">
            <label for="place">Local: </label>
            <input class="form-control" id="place" v-model="data.place" name="place" rows="3">
        </div>

        <div class="form-group">
            <b-row>
                <b-col>
                    <label for="information">Data</label><br>
                    <date-picker v-model="data.date" valueType="format" lang="pt-br"></date-picker>
                </b-col>
                <b-col>
                    <label for="information">Hora</label>
                    <div v-for="type in types" :key="type">
                        <b-form-input :id="`type-${type}`" :type="type" v-model="data.time"></b-form-input>
                    </div>
                </b-col>
            </b-row>
        </div>

        <b-button variant="outline-success" v-on:click.prevent="save()">Guardar</b-button>
        <b-button variant="outline-info" v-on:click.prevent="cancel()">Cancelar</b-button>
    </div>
</template>
<script>

    export default {
        props: ["user"],
        data: function () {
            return {
                data: {
                    place: '',
                    time: '',
                    date: '',
                    info: ''

                },

                meeting: {
                    info: null,
                    date: null
                },
                files: [],
                types: [
                    `time`,
                ]

            };

        },
        methods: {
            cancel() {
                this.$emit("cancel-edit");
            },
            save: function () {
                this.$emit("save-meeting", this.data);
            }
        },
        mounted() {}

    };

</script>
