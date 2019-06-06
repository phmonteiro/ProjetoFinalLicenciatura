<template>
    <div>
        <set-inter :user="currentUser" @save-interaction="saveInteraction" @cancel-edit="cancelEdit()"> </set-inter>
        <div class="container">
            <h2>Lista de Enee</h2>
            <b-table striped hover v-if="enee!=null" :items="enee" :fields="fields">
                <template slot="actions" slot-scope="row">
                    <b-row class="text-center">
                        <b-col md="6" sm="12">
                            <b-form-checkbox v-model="row.detailsShowing" @change="row.toggleDetails">

                            </b-form-checkbox>
                            <div v-if="row.detailsShowing" style="margin-left: -8px;">
                                <font-awesome-icon icon="eye"/>
                            </div>
                            <div v-if="!row.detailsShowing" style="margin-left: -8px;">
                                <font-awesome-icon icon="eye-slash"/>
                            </div>

                        </b-col>
                        <b-col md="6" sm="12">
                            <b-button size="sm" v-on:click.prevent="setInteraction(row.item)">Interação
                                <font-awesome-icon icon="handshake" />
                            </b-button>
                        </b-col>
                    </b-row>


                </template>

                <template slot="row-details" slot-scope="row">
                    <b-card>
                        <b-row class="mb-2">
                            <b-col sm="4" class="text-sm-right"><b>Nascido em: </b>{{row.item.birthDate}}</b-col>
                            <b-col>
                                <b-row class="mb-2">
                                    <b-col sm="4" class="text-sm-right"><b>Ano curricular:
                                        </b>{{row.item.curricularYear}}</b-col>
                                </b-row>
                            </b-col>
                        </b-row>

                        <b-row class="mb-2">
                            <b-col sm="4" class="text-sm-right"><b>Escola: </b>{{row.item.school}}</b-col>
                            <b-col>
                                <b-row class="mb-2">
                                    <b-col sm="4" class="text-sm-right"><b>Entrou na escola em:
                                        </b>{{row.item.enruledYear}}</b-col>
                                </b-row>
                            </b-col>
                        </b-row>

                        <b-row class="mb-2">
                            <b-col sm="4" class="text-sm-right"><b>Curso: </b>{{row.item.course}}</b-col>
                            <b-col>
                                <b-row class="mb-2">
                                    <b-col sm="4" class="text-sm-right"><b>Incapacidade:
                                        </b>{{row.item.functionalAnalysis}}</b-col>
                                </b-row>
                            </b-col>
                        </b-row>
                        <b-button size="sm" @click="row.toggleDetails">Esconder</b-button>
                    </b-card>
                </template>
            </b-table>
            <nav aria-label="Page navigation" v-if="enee">
                <ul class="pagination">
                    <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                        <a class="page-link" href="#"
                            @click.prevent="getcaseManagers(pagination.prev_page_url)">Anterior</a>
                    </li>

                    <li class="page-item disabled">
                        <a class="page-link text-dark" href="#">Página {{ pagination.current_page }} de
                            {{ pagination.last_page }}</a>
                    </li>

                    <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                        <a class="page-link" href="#"
                            @click.prevent="getcaseManagers(pagination.next_page_url)">Próximo</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                pagination: {},
                loading: true,
                enee: null,
                fields: [{
                        key: "name",
                        label: "Nome Estudande",
                        sortable: true
                    },
                    {
                        key: "email",
                        label: "Email Estudante",
                        sortable: true
                    },
                    {
                        key: "phoneNumber",
                        label: "Contacto",
                        sortable: true
                    },
                    {
                        key: "school",
                        label: "Escola",
                        sortable: true
                    },
                    {
                        key: "actions",
                        label: "Ações"
                    }
                ],
                currentUser: null
            };
        },
        methods: {
            getCmEnee() {
                axios
                    .get("api/getCmEnee/" + this.user.id)
                    .then(response => {
                        this.enee = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
                this.pagination = pagination;
            },
            setInteraction(row) {
                this.currentUser = Object.assign({}, row);
            },
            cancelEdit: function () {
                this.currentUser = null;
            },
            saveInteraction(data) {
                data.email = this.currentUser.email;
                axios
                    .post("api/setInteraction/", data)
                    .then(response => {
                        this.getCmEnee();
                        this.currentUser = null;
                        this.$toasted.success("Guardado com sucesso.", {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });
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
        created() {
            this.getCmEnee();
        },
        computed: {
            user: function () {
                return this.$store.state.user;
            }
        }
    };

</script>
