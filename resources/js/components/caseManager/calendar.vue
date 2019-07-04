<template>
    <div>
        <b-container>
            <b-row>
                <b-col></b-col>
                <b-col>
                    <div class="loader">
                        <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50" />
                    </div>
                </b-col>
                <b-col></b-col>
            </b-row>
        </b-container>
        <div v-if="!loading" class="demo-app">
        <b-container>
            <b-row>
                    <FullCalendar class="demo-app-calendar" ref="fullCalendar" defaultView="dayGridMonth" :header="{
                          left: 'prev,next today',
                          center: 'title',
                          right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                        }" :plugins="calendarPlugins" :weekends="calendarWeekends" :events="calendarEvents"
                        @eventClick="handleDateClick" />
            </b-row>
            <b-row>
              <b-col>

              </b-col>
              <b-col>
                <div class="text-center mt-1">
                  <button id="addEvent" type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#exampleModal">Adicionar evento</button>
                </div>
                  
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Evento</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label>Titulo do evento</label>
                                <input v-model="event.title" type="text" class="form-control" aria-label="titulo"
                                    aria-describedby="basic-addon1">
                                <label>Data de começo</label>
                                <input v-model="event.startDate" type="date" class="form-control"
                                    aria-label="dataComeco" aria-describedby="basic-addon1">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <b-button variant="success" v-on:click.prevent="saveEvent()" data-dismiss="modal">
                                    Guardar
                                </b-button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
                    aria-hidden="true">
                    <div v-if="this.currentEvent" class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="eventModalLabel">Evento</h5>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <label>Titulo:</label>
                                    <p>{{this.currentEvent.title}}</p>
                                </div>
                                <div>
                                    <label>Data:</label>
                                    <p>{{this.currentEvent.start}}</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <b-button variant="success" v-on:click.prevent="dismiss()" data-dismiss="modal">
                                    Fechar
                                </b-button>
                            </div>
                        </div>
                    </div>
                </div>
              </b-col>
                <b-col>

                </b-col>
            </b-row>
        </b-container>
        </div>
    </div>
</template>

<script>
    import FullCalendar from "@fullcalendar/vue";
    import dayGridPlugin from "@fullcalendar/daygrid";
    import timeGridPlugin from "@fullcalendar/timegrid";
    import interactionPlugin from "@fullcalendar/interaction";

    // must manually include stylesheets for each plugin
    import "@fullcalendar/core/main.css";
    import "@fullcalendar/daygrid/main.css";
    import "@fullcalendar/timegrid/main.css";

    export default {
        components: {
            FullCalendar // make the <FullCalendar> tag available
        },
        data: function () {
            return {
                event: {
                    title: null,
                    startDate: null
                },
                calendarPlugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
                calendarWeekends: true,
                calendarEvents: [],
                currentEvent: null,
                loading: true
            };
        },
        methods: {
            getMeetingsCaseManager() {
                axios
                    .get("api/getEvents")
                    .then(response => {
                        for (var i = 0; i < response.data.length; i++) {
                            for (var j = 0; j < response.data[i].length; j++) {
                                if (response.data[i][j].date) {
                                    let event = {
                                        title: "Reunião com " + response.data[i][j].name,
                                        start: response.data[i][j].date
                                    };
                                    this.calendarEvents.push(event);
                                } else {
                                    let event = {
                                        title: "Reunião com " + response.data[i][j].title,
                                        start: response.data[i][j].startDate
                                    };
                                    this.calendarEvents.push(event);
                                }
                            }
                        }
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            saveEvent() {
                axios
                    .post("api/addEvent", this.event)
                    .then(response => {
                        console.log(response);
                        this.getMeetingsCaseManager();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            handleDateClick(arg) {
                $("#eventModal").modal("show");
                this.currentEvent = arg.event;
            },
            dismiss() {
                this.$router.go(0);
            }
        },
        created() {
            this.getMeetingsCaseManager();
        }
    };

</script>

<style>
    .modal-backdrop.show {
        opacity: 0;
        display: none;
    }

    .demo-app span.fc-title {
        font-size: 10px;
        color: white;
    }

    .demo-app-calendar {
        margin: 0 auto;
        max-width: 60%;
    }

</style>
