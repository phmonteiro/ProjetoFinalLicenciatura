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
            <label for="decision">Data da interação: </label>
            <date-picker v-model="data.interactionDate" valueType="format" lang="pt-br"></date-picker>
            <code>Caso nao especificado, a data de hoje será assumida.</code>
        </div>

        <div class="form-group">
            <label for="decision">Servico: </label>
            <b-form-select v-model="data.service" class="mb-3">
                <template slot="first">
                    <option :value="null" disabled>-- Selecione o serviço --</option>
                </template>

                <option value="SAPE">SAPE</option>
                <option value="SAS">SAS</option>
                <option value="Escola">Escola</option>
                <option value="Biblioteca">Biblioteca</option>
                <option value="Direção">Direção</option>
                <option value="Professor-Tutor">Professor-Tutor</option>
                <option value="Gestor-Caso">Gestor-Caso</option>
            </b-form-select>
        </div>

        <div class="form-group">
            <label for="decision">Medida</label>
            <textarea class="form-control" id="decision" v-model="data.decision" name="decision" rows="3"
                ></textarea>
        </div>

        <div class="form-group">
            <label for="information">Informação</label>
            <textarea class="form-control" id="information" v-model="data.information" name="information"
                rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="nextInteraction">Proxima interação: </label>
            <date-picker v-model="data.nextInteraction" valueType="format" lang="pt-br"></date-picker>
        </div>

        <b-button variant="outline-success" v-on:click.prevent="save()">Guardar</b-button>
        <b-button variant="outline-info" v-on:click.prevent="cancel()">Cancelar</b-button>
    </div>
</template>
<script>
    export default {
        props: ["user", "studentCMs"],
        data: function () {
            return {
                data: {
                    email: '',
                    interactionDate: '',
                    nextInteraction: '',
                    service: '',
                    decision: '',
                    information: ''
                },
                meeting: {
                    info: null,
                    date: null
                },
                format: "d MMMM yyyy",

            };

        },
        methods: {
            cancel() {
                this.$emit("cancel-edit");
            },
            save: function () {
                this.$emit("save-interaction", this.data);
                //this.data = Object.assign({}, {});
            }
        },
        mounted() {},

    };

</script>
