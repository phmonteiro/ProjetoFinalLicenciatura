<template>
    <div class="container mt-3" v-if="user">
        <div class="form-group">
            <label for="inputName">Nome</label>
            <input type="text" class="form-control" v-model="user.name" name="name" id="inputName" disabled>
        </div>

        <div class="form-group">
            <label for="inputEmail">E-mail</label>
            <input type="text" class="form-control" v-model="user.email" name="email" id="inputEmail" disabled>
        </div>

        <b-container v-for="(inter, index) in interactions" :key="index">
            <b-row>
                <b-col><b>Data: </b> {{inter.date}}</b-col>
                <b-col><b>Servico: </b> {{inter.service}}</b-col>
                <b-col><b>Proximo Contacto: </b>{{inter.nextContact}}</b-col>
            </b-row>
            <b-row>
                <b-col>
                    <b>Decisao: </b>{{inter.decision}}
                </b-col>
                <b-col>
                    <b>Informacao: </b>{{inter.information}}
                </b-col>
            </b-row>

            <div class="form-group" v-if="inter.hasFiles">
                <button class="btn btn-info" v-on:click.prevent="downloadFiles(inter.id)">Descarregar ficheiros anexados
                    <font-awesome-icon icon="arrow-circle-down" /> </button>
            </div>
            <div class="dropdown-divider"></div>
        </b-container>

        <div class="form-group">
            <button class="btn btn-secondary" v-on:click.prevent="cancel()">Fechar</button>
        </div>
    </div>
</template>
<script>
    export default {
        props: ["user", "interactions"],
        data: function () {
            return {};
        },
        methods: {
            cancel() {
                this.$emit("cancel-edit");
            },
            downloadFiles(id) {
                axios({
                        url: "api/contact/download/" + id,
                        method: "GET",
                        responseType: "blob"
                    })
                    .then(response => {
                        console.log(response);
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement("a");
                        link.href = url;
                        link.setAttribute("download", "Ficheiros" + this.user.name + ".zip");
                        document.body.appendChild(link);
                        link.click();
                        console.log("success");
                    })
                    .catch(error => {
                        console.log(error, "error");
                    });
            }
        }

    };

</script>
