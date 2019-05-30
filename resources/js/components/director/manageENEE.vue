<template>
    <div>
        <eneeOptions :user="currentUser" :studentSupports="supportsForStudent" @save-user="saveUser" @cancel-edit="cancelEdit()"></eneeOptions>
        <div class="container">
            <h2>Lista de ENEE</h2>
            <b-table striped hover v-if="enee!=null" :items="enee" :fields="fields">
                <template slot="actions" slot-scope="row">
                    <button class="btn btn-info" v-on:click.prevent="editUser(row.item)"
                        v-if="row.item.number != user.number">Opções</button>
                </template>
            </b-table>
            <nav aria-label="Page navigation" v-if="enee">
                <ul class="pagination">
                    <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                        <a class="page-link" href="#" @click.prevent="getEnee(pagination.prev_page_url)">Anterior</a>
                    </li>

                    <li class="page-item disabled">
                        <a class="page-link text-dark" href="#">Página {{ pagination.current_page }} de
                            {{ pagination.last_page }}</a>
                    </li>

                    <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                        <a class="page-link" href="#" @click.prevent="getEnee(pagination.next_page_url)">Próximo</a>
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
                        label: "Nome",
                        sortable: true
                    },
                    {
                        key: "email",
                        label: "Email",
                        sortable: true
                    },
                    {
                        key: "number",
                        label: "Número",
                        sortable: true
                    },
                    {
                        key: "course",
                        label: "Curso",
                        sortable: true
                    },
                    {
                        key: "school",
                        label: "Escola",
                        sortable: true
                    },
                    {
                        key: "actions",
                        label: "Ações Utilizador"
                    }
                ],
                currentUser: null,
                supportsForStudent: null
            };
        },
        methods: {
            getEnee(page_url) {
                let pg = this;
                page_url = page_url || "api/getEnee?page=1";
                axios
                    .get(page_url)
                    .then(response => {
                        this.loading = false;
                        this.enee = response.data.data;
                        pg.makePagination(response.data.meta, response.data.links);
                    })
                    .catch(error => {
                        console.log(error);
                        this.loading = false;
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
            editUser(row) {
                this.currentUser = Object.assign({}, row);
                this.getStudentSupports();

            },
            cancelEdit: function () {
                this.currentUser = null;
            },
            getStudentSupports(){
                axios.get("api/getStudentSupports/" + this.currentUser.email).then(
                        response => {
                            console.log(response);
                            this.supportsForStudent = response.data;
                        }
                    )
                    .catch(error => {
                        console.log(error);
                    });
            },
            saveUser(data) {
                axios
                    .post("api/updateStudentSupports/", data)
                    .then(response => {
                        this.getStudentSupports();
                        this.currentUser = null;
                        this.$toasted.success("Estudante guardado com sucesso.", {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });
                    })
                    .catch(error => {
                        console.log(error);
                        this.$toasted.error(
                            "Erro ao guardar. Por favor tente novamente.", {
                                duration: 4000,
                                position: "top-center",
                                theme: "bubble"
                            }
                        );
                    });
                    
            }
        },
        created() {
            this.getEnee();
        },
        computed: {
            user: function () {
                return this.$store.state.user;
            }
        }
    };

</script>
