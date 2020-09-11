<template>
    <div>
        <b-container>
            <b-row>
                <b-col></b-col>
                <b-col>
                    <div class="loader">
                        <ClipLoader
                            sizeUnit="px"
                            class="loading"
                            v-if="loading"
                            :size="50"
                        />
                    </div>
                </b-col>
                <b-col></b-col>
            </b-row>
        </b-container>
        <b-container>
            <div class="row">
                <div class="col-sm-6 text-center">
                    <b-button
                        v-on:click="showRequestedMeetings()"
                        style="height:50px;width:400px"
                        class="btn-xl"
                    >
                        Pedidos de Reunião
                    </b-button>
                </div>
                <div class="col-sm-6 text-center">
                    <b-button
                        v-on:click="showScheduledMeetings()"
                        style="height:50px;width:400px"
                        class="btn-xl"
                    >
                        Reuniões Agendadas
                    </b-button>
                </div>
            </div>
        </b-container>
        <br />
        <b-container>
            <b-row>
                <b-col>
                    <h2 v-if="showRequested">Pedidos Reunião</h2>
                    <h2 v-if="showScheduled">Reuniões Agendadas</h2>
                    <div v-if="meetings != null && meetings.length!==0">
                    <b-table
                        striped
                        hover
                        :items="meetings"
                        :fields="fields"
                    >
                        <template v-slot:cell(actions)="row">
                            <b-row class="text-center">
                                <b-col md="4" sm="12">
                                    <div
                                        v-if="row.detailsShowing"
                                        style="margin-left: -8px;"
                                    >
                                        <b-button
                                            size="sm"
                                            @click="row.toggleDetails"
                                            >Detalhes
                                            <font-awesome-icon
                                                icon="eye-slash"
                                            />
                                        </b-button>
                                    </div>
                                    <div
                                        v-if="!row.detailsShowing"
                                        style="margin-left: -8px;"
                                    >
                                        <b-button
                                            size="sm"
                                            @click="row.toggleDetails"
                                            >Detalhes
                                            <font-awesome-icon icon="eye" />
                                        </b-button>
                                    </div>
                                </b-col>
                                <b-col md="4" sm="12">
                                    <b-button
                                        v-if="showRequested"
                                        size="sm"
                                        v-on:click.prevent="
                                            setMeeting(row.item)
                                        "
                                    >
                                        Agendar
                                        <font-awesome-icon icon="handshake" />
                                    </b-button>
                                </b-col>
                            </b-row>
                        </template>
                        <template slot="row-details" slot-scope="row">
                            <b-card>
                                <b-row v-if="showScheduled"  class="mb-2">
                                    <b-col class="text">
                                        <b>Informação:</b>
                                        {{ row.item.info }}
                                    </b-col>
                                </b-row>

                                <b-row v-if="showScheduled"  class="mb-2">
                                    <b-col class="text">
                                        <b>Local:</b>
                                        {{ row.item.place }}
                                    </b-col>
                                </b-row>
                                <b-row class="mb-2">
                                    <b-col class="text">
                                        <b>Comentário do ENE sobre o pedido de reunião:</b>
                                        <br />
                                        <div style="padding-left: 4em;">
                                            {{ row.item.comment }}
                                        </div>
                                        <br />
                                    </b-col>
                                </b-row>
                                <b-row v-if="row.item.hasFiles" class="text-center mb-2">
                                    <b-col class="text">
                                        <button class="btn btn-info" v-on:click.prevent="downloadFiles(row.item.id)">
                                            Descarregar ficheiros anexados
                                            <font-awesome-icon icon="arrow-circle-down" />
                                        </button>
                                    </b-col>
                                </b-row>
                                <b-row class="text-center mb-2">
                                    <b-col class="text">
                                        <b-button
                                            size="sm"
                                            @click="row.toggleDetails"
                                            >Esconder</b-button
                                        >
                                    </b-col>
                                </b-row>
                            </b-card>
                        </template>
                    </b-table>
                        <nav aria-label="Page navigation" v-if="meetings">
                            <ul class="pagination">
                                <li
                                    v-bind:class="[{ disabled: !pagination.prev_page_url }]"
                                    class="page-item"
                                >
                                    <a
                                        class="page-link"
                                        href="#"
                                        @click.prevent="
                                getMeetingsEnee(pagination.prev_page_url)
                            "
                                    >{{$t('anterior')}}</a
                                    >
                                </li>

                                <li class="page-item disabled">
                                    <a class="page-link text-dark" href="#">
                                        Página {{ pagination.current_page }} de
                                        {{ pagination.last_page }}
                                    </a>
                                </li>

                                <li
                                    v-bind:class="[{ disabled: !pagination.next_page_url }]"
                                    class="page-item"
                                >
                                    <a
                                        class="page-link"
                                        href="#"
                                        @click.prevent="
                                getMeetingsEnee(pagination.next_page_url)
                            "
                                    >{{$t('próximo')}}</a
                                    >
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div v-else>
                        <br>
                        <h5>
                            Não existem reuniões para mostrar.
                        </h5>
                    </div>

                </b-col>
            </b-row>
        </b-container>

        <set-meeting
            :meetingRequest="currentMeeting"
            @save-meeting="saveMeeting"
            @cancel-edit="cancelEdit()"
        ></set-meeting>
    </div>
</template>

<script>
export default {
    data() {
        return {
            yourTimeValue: {},
            pagination: {},
            loading: true,
            requestedMeetings: [],
            scheduledMeetings: [],
            meetings: null,
            fields: [
                {
                    key: "name",
                    label: "Nome Estudante",
                    sortable: true
                },
                {
                    key: "email",
                    label: "Email Estudante",
                    sortable: true
                },
                {
                    key: "service",
                    label: "Serviço",
                    sortable: true
                },
                {
                    key: "date",
                    label: "Data",
                    sortable: true
                },
                {
                    key: "time",
                    label: "Hora",
                    sortable: true
                },
                {
                    key: "requestDate",
                    label: "Data do Pedido",
                    sortable: true
                },
                {
                    key: "actions",
                    label: "Ações"
                }
            ],
            currentMeeting: null,
            userInteractions: null,
            interactions: null,
            showRequested: true,
            showScheduled: null
        };
    },
    methods: {
        downloadFiles(id) {
            axios({
                url: "api/meeting/download/" + id,
                method: "GET",
                responseType: "blob"
            }).then(response => {
                console.log(response);
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "Ficheiros" + this.user.name + ".zip");
                document.body.appendChild(link);
                link.click();
                this.$toasted.success("Download efetuado com sucesso.", {
                    duration: 4000,
                    position: "top-center",
                    theme: "bubble"
                });
            })
                .catch(error => {
                    console.log(error, "error");
                    this.$toasted.error(
                        "Error ao fazer download. Por favor tente novamente.",
                        {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        }
                    );
                });
        },
        showRequestedMeetings() {
            this.showRequested = true;
            this.showScheduled = false;
            this.getMeetingsEnee();
        },
        showScheduledMeetings() {
            this.showRequested = false;
            this.showScheduled = true;
            this.getMeetingsEnee();
        },
        getMeetingsEnee(page_url) {
            let varRequested;
            if (this.showRequested) {
                varRequested = 1;
            } else {
                varRequested = 0;
            }

            let pg = this;
            page_url =
                page_url ||
                "api/getMyMeetings?page=1&requested=" + varRequested;
            axios
                .get(page_url)
                .then(response => {
                    this.meetings = response.data.data;

                    this.loading = false;

                    pg.makePagination(response.data);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        makePagination(data) {
            let pagination = {
                current_page: data.current_page,
                last_page: data.last_page,
                next_page_url: data.next_page_url,
                prev_page_url: data.prev_page_url
            };
            this.pagination = pagination;
        },
        setMeeting(row) {
            this.meet = row;
            this.currentMeeting = Object.assign({}, row);
        },
        cancelEdit: function() {
            this.currentMeeting = null;
        },
        cancelInteractions: function() {
            this.userInteractions = null;
        },
        saveMeeting(data) {
            axios
                .put("api/setEneeMeeting/" + this.currentMeeting.id, data)
                .then(response => {
                    this.getMeetingsEnee();

                    // this.scheduledMeetings.push(this.currentMeeting);
                    // this.requestedMeetings.splice(this.requestedMeetings.indexOf(this.currentMeeting),1);

                    this.currentMeeting = null;
                    this.$toasted.success("Guardado com sucesso.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                })
                .catch(error => {
                    console.log(error);
                    this.$toasted.error(
                        "Erro ao guardar. Por favor tente novamente.",
                        {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        }
                    );
                });
        }
    },
    created() {
        this.getMeetingsEnee();
    },
    computed: {
        user: function() {
            return this.$store.state.user;
        }
    }
};
</script>
