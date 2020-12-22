<template>
    <div>
        <div class="container mt-3">
            <h2>Pedidos de Horas de Apoio</h2>
            <div v-if="requests!=null && requests.length!==0">
                <b-table striped hover  :items="requests" :fields="fields">
                    <template v-slot:cell(status)="{ value }">
                        <p v-if="value==='waiting'">Em avaliação</p>
                    </template>
                    <template v-slot:cell(actions)="row">
                        <b-row class="text-center">
                            <b-col md="4" sm="12">
                                <button
                                    type="button"
                                    class="btn btn-success"
                                    data-toggle="modal"
                                    data-target="#modalSupportHoursConfirmation"
                                >Confirmar</button>
                                <!-- Modal -->
                                <div
                                    class="modal fade"
                                    id="modalSupportHoursConfirmation"
                                    tabindex="-1"
                                    role="dialog"
                                    aria-labelledby="exampleModalCenterTitle"
                                    aria-hidden="true"
                                >
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5
                                                    class="modal-title"
                                                    id="exampleModalLongTitle2"
                                                >Apoio</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <label for>Sumário</label>
                                                <b-form-textarea
                                                    id="supportSummary"
                                                    name="supportSummary"
                                                    v-model="formConfirmation.summary"
                                                    placeholder="Escreva aqui a informação relativa ao apoio ocorrido."
                                                    rows="5"
                                                    max-rows="10"
                                                ></b-form-textarea>
                                                <br>
                                                <label for>Data do Apoio</label>
                                                <input type="date" v-model="formConfirmation.date">
                                                <br>
                                                <label for>Hora do apoio</label>
                                                <input type="text" v-model="formConfirmation.hours">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                <button class="btn btn-success" v-on:click.prevent="confirmSupport(row.item)">Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
<!--                            <b-col md="4" sm="12">-->
<!--                                &lt;!&ndash; Button trigger modal &ndash;&gt;-->
<!--                                <button-->
<!--                                    type="button"-->
<!--                                    class="btn btn-danger"-->
<!--                                    data-toggle="modal"-->
<!--                                    data-target="#modalSupportHoursRequestDecision"-->
<!--                                >Rejeitar</button>-->

<!--                                &lt;!&ndash; Modal &ndash;&gt;-->
<!--                                <div-->
<!--                                    class="modal fade"-->
<!--                                    id="modalSupportHoursRequestDecision"-->
<!--                                    tabindex="-1"-->
<!--                                    role="dialog"-->
<!--                                    aria-labelledby="exampleModalCenterTitle"-->
<!--                                    aria-hidden="true"-->
<!--                                >-->
<!--                                    <div class="modal-dialog" role="document">-->
<!--                                        <div class="modal-content">-->
<!--                                            <div class="modal-header">-->
<!--                                                <h5-->
<!--                                                    class="modal-title"-->
<!--                                                    id="exampleModalLongTitle"-->
<!--                                                >Justifique a sua decisão (opcional)</h5>-->
<!--                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
<!--                                                    <span aria-hidden="true">&times;</span>-->
<!--                                                </button>-->
<!--                                            </div>-->
<!--                                            <div class="modal-body">-->
<!--                                                <b-form-textarea-->
<!--                                                    id="txtSupportHoursRequestDecision"-->
<!--                                                    v-model="row.item.decision"-->
<!--                                                    placeholder="Escreva aqui a informação relativa à rejeição do pedido de horas de apoio (opcional)"-->
<!--                                                    rows="5"-->
<!--                                                    max-rows="10"-->
<!--                                                ></b-form-textarea>-->
<!--                                            </div>-->
<!--                                            <div class="modal-footer">-->
<!--                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
<!--                                                <button class="btn btn-danger" v-on:click.prevent="deny(row.item)">Rejeitar</button>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </b-col>-->
                        </b-row>
                    </template>
                </b-table>
                <nav aria-label="Page navigation" v-if="requests">
                    <ul class="pagination">
                        <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                            <a
                                class="page-link"
                                href="#"
                                @click.prevent="getRequests(pagination.prev_page_url)"
                            >Anterior</a>
                        </li>

                        <li class="page-item disabled">
                            <a class="page-link text-dark" href="#">
                                Página {{ pagination.current_page }} de
                                {{ pagination.last_page }}
                            </a>
                        </li>

                        <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                            <a
                                class="page-link"
                                href="#"
                                @click.prevent="getRequests(pagination.next_page_url)"
                            >Próximo</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div v-else>
                <hr>
                <br>
                <h4>Não existem pedidos de horas de apoio.</h4>
            </div>
        </div>
        <br><br><br><br><br><br><br>
    </div>
</template>

<script>
  export default {
    name: 'supportRequests',
    data(){
        return{
            formConfirmation:{
              summary: "",
              hours: null,
              date: null
            },
            pagination: {},
            loading: true,
            requests: null,
            fields: [
                {
                    key: "student_email",
                    label: "Nome Estudante",
                    sortable: true,
                },
                {
                    key: "subject_name",
                    label: "UC",
                    sortable: true,
                },
                {
                    key: "hours",
                    label: "Horas Solicitadas",
                    sortable: true,
                },
                {
                    key: "status",
                    label: "Estado do Pedido",
                    sortable: true,
                },
                {
                    key: "actions",
                    label: "Ações Utilizador",
                },
            ],
        };
    },
    methods:{
        getRequests(page_url) {
            let pg = this;
            page_url = page_url || "api/getTeacherSupports?page=1";
            axios
                .get(page_url)
                .then((response) => {
                    this.loading = false;
                    this.requests = response.data[0].data;
                    pg.makePagination(response.data[0]);
                })
                .catch((error) => {
                    console.log(error);
                    this.loading = false;
                });
        },
        makePagination(data) {
            let pagination = {
                current_page: data.current_page,
                last_page: data.last_page,
                next_page_url: data.next_page_url,
                prev_page_url: data.prev_page_url,
            };
            this.pagination = pagination;
        },
        confirmSupport(item){
            axios
                .patch("api/createSummary/" + item.id, this.formConfirmation)
                .then((response) => {
                    this.getRequests();
                    $('#modalSupportHoursConfirmation').modal('hide');
                    this.$toasted.success("Confirmado com sucesso.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble",
                    });
                })
                .catch((error) => {
                    console.log(error);
                    this.$toasted.error(
                        "Erro ao criar sumário. Por favor contacte o administrador da plataforma.",
                        {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble",
                        }
                    );
                });
        },
        deny(item) {
            axios
                .patch("api/denySupportHoursRequest", item)
                .then((response) => {
                    this.getRequests();
                    $('#modalSupportHoursRequestDecision').modal('hide');
                    this.$toasted.success("Gravado com sucesso.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble",
                    });
                })
                .catch((error) => {
                    console.log(error);
                    this.$toasted.error(
                        "Erro ao efetuar operação sobre o pedido de horas de apoio. Por favor contacte o administrador da plataforma.",
                        {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble",
                        }
                    );
                });
        },
    },
    created() {
        this.getRequests();
    },
    computed: {
        user: function () {
            return this.$store.state.user;
        },
    },
  };
</script>

<style scoped>

</style>
