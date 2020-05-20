<template>
    <div>
        <b-container>
            <div class="loader">
                <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50" />
            </div>
        </b-container>
        <div v-if="!loading" class="demo-app">
            <b-container>
                <b-row>
                    <FullCalendar
                        class="demo-app-calendar"
                        ref="fullCalendar"
                        defaultView="dayGridMonth"
                        :header="{
                          left: 'prev,next today',
                          center: 'title',
                          right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                        }"
                        :plugins="calendarPlugins"
                        :weekends="calendarWeekends"
                        :events="calendarEvents"
                        @eventClick="handleDateClick"
                    />
                </b-row>
                <br>
                        <div class="text-center">
                            <button v-on:click="addEvent()"
                                class="btn btn-primary"
                            >Adicionar evento</button>
                        </div>

                <modal height="450" draggable=".window-header" :resizable="true" name="add-event">
                    <br>
                    <div class="text-center">
                        <label>Nome do evento</label>
                            <input
                            v-model="event.title"
                            type="text"
                            class="form-control"
                            aria-describedby="basic-addon1"
                            />
                        <label>Data</label>
                            <input
                            v-model="event.startDate"
                            type="date"
                            class="form-control"
                            /><br>
                        <label>Horas</label>
                        <vue-timepicker :minute-interval="5" format="HH:mm" v-model="event.hours"></vue-timepicker>
                    </div>
                    <br><br><br><br><br>
                    <div class="text-center">
                        <button
                        class="btn btn-primary "
                        variant="success"
                        v-on:click.prevent="saveEvent()"
                        >Guardar</button>
                        <b-button  @click="hideAddEvent()">Close</b-button></div>
                </modal>

                <modal height="450" draggable=".window-header" :resizable="true" name="hello-world">
                    <br><br>
                    <div class="text-center">
                    <div class="window-header">
                        <h4 class="modal-title" v-if="currentEvent">{{currentEvent.title}}</h4>
                        <br>
                       </div>
                       <div>
                       <h5>Data:</h5>
                        <p v-if="currentEvent">{{eventDate}}</p>
                       </div>
                        <div v-if="currentEvent">
                        <div v-if="currentEvent.extendedProps.hours">
                            <h5>Hora:</h5>
                            <p>{{currentEvent.extendedProps.hours}}</p>
                        </div>
                        <div v-else>
                            <h5>Hora:</h5>
                            <p>{{eventHours}}</p>
                        </div>
                        <div v-if="currentEvent.extendedProps.info">
                            <h5>Informação da Reunião:</h5>
                            <p>{{currentEvent.extendedProps.info}}</p>
                        </div>
                        <div v-if="currentEvent.extendedProps.local">
                            <h5>Local:</h5>
                            <p>{{currentEvent.extendedProps.local}}</p>
                        </div>
                        </div>
                        <button class="btn btn-primary" @click="hideEventInfo()">Close</button>
                    </div>
                </modal>
            </b-container>
            </div>
    </div>
</template>

<script>
    import FullCalendar from "@fullcalendar/vue";
    import dayGridPlugin from "@fullcalendar/daygrid";
    import timeGridPlugin from "@fullcalendar/timegrid";
    import interactionPlugin from "@fullcalendar/interaction";
    import VueTimepicker from 'vue2-timepicker/src/vue-timepicker.vue';
    // must manually include stylesheets for each plugin
    import "@fullcalendar/core/main.css";
    import "@fullcalendar/daygrid/main.css";
    import "@fullcalendar/timegrid/main.css";

    export default {
        components: {
            FullCalendar, // make the <FullCalendar> tag available,
            VueTimepicker
        },
        data: function() {
            return {
                event: {
                    title: null,
                    startDate: null,
                    hours: null
                },
                calendarPlugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
                calendarWeekends: true,
                calendarEvents: [],
                currentEvent: null,
                loading: true,
                eventDate:null,
                eventHours:null,
            };
        },
        methods: {
            hideAddEvent(){
                this.$modal.hide('add-event');
            },
            addEvent(){
                this.$modal.show('add-event');
            },
            hideEventInfo () {
                this.$modal.hide('hello-world');
            },
            getMeetingsCaseManager() {
                axios
                    .get("api/getEvents")
                    .then(response => {
                        console.log(response.data);
                        for (var i = 0; i < response.data.length; i++) {
                            for (var j = 0; j < response.data[i].length; j++) {
                                if (response.data[i][j].date) {
                                    let event = {
                                        title: "Reunião com " + response.data[i][j].name,
                                        start: response.data[i][j].date,
                                        extendedProps: {
                                            hours: response.data[i][j].time,
                                            info: response.data[i][j].info,
                                            local: response.data[i][j].place
                                        }
                                    };
                                    this.calendarEvents.push(event);
                                } else {
                                    let event = {
                                        title: response.data[i][j].title,
                                        start: response.data[i][j].startDate,
                                        extendedProps: {
                                            hours: response.data[i][j].hours,
                                            info: null
                                        }
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
                let data = this.event.startDate.split('T');

                let hours = this.event.hours.HH+":"+this.event.hours.mm
                this.event.hours = hours;
                console.log(this.event);
                axios
                    .post("api/addEvent", this.event)
                    .then(response => {

                        let event = {
                            title: "Reunião com " + this.event.title,
                            start: data[0],
                            extendedProps:{
                                hours:this.event.hours
                            }
                        };

                        this.calendarEvents.push(event);

                        this.hideAddEvent();

                        this.$toasted.success("Evento adicionado com sucesso.", {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });
                    })
                    .catch(error => {
                        this.$toasted.error(
                            "Error ao adicionar evento. Por favor tente novamente.",
                            {
                                duration: 4000,
                                position: "top-center",
                                theme: "bubble"
                            }
                        );
                    });
            },

            handleDateClick(arg) {
                this.currentEvent = arg.event;
                this.eventDate = new  Date(arg.event.start);

                this.eventDate.setHours(this.eventDate.getHours()+1);

                let data =  this.eventDate.toISOString();

                //format data string predifined by FulCalendar
                if(data.includes("Z")) {

                    this.eventDate = data;

                    let dateSplit = this.eventDate.split("T");
                    this.eventDate = dateSplit[0];
                }

                if(this.currentEvent!=null)
                    this.$modal.show('hello-world',{currentEvent: this.currentEvent});
            },
            dismiss() {
                this.currentEvent=null;
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
