<template>
  <div class="demo-app">
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
    />
    <button
      type="button"
      class="btn btn-primary"
      data-toggle="modal"
      data-target="#exampleModal"
    >Adicionar evento</button>
    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
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
            <input
              v-model="event.title"
              type="text"
              class="form-control"
              aria-label="titulo"
              aria-describedby="basic-addon1"
            >
            <label>Data de começo</label>
            <input
              v-model="event.startDate"
              type="date"
              class="form-control"
              aria-label="dataComeco"
              aria-describedby="basic-addon1"
            >
            <label>Hora de começo</label>
            <input
              v-model="event.timeStart"
              type="time"
              class="form-control"
              aria-label="hora"
              aria-describedby="basic-addon1"
            >
            <label>Data de fim</label>
            <input
              v-model="event.endDate"
              type="date"
              class="form-control"
              aria-label="dataComeco"
              aria-describedby="basic-addon1"
            >
            <label>Hora de fim</label>
            <input
              v-model="event.timeEnd"
              type="time"
              class="form-control"
              aria-label="hora"
              aria-describedby="basic-addon1"
            >
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <b-button
              variant="success"
              v-on:click.prevent="saveEvent()"
              data-dismiss="modal"
            >Guardar</b-button>
          </div>
        </div>
      </div>
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
  data: function() {
    return {
      event: {
        title: null,
        startDate: null,
        timeStart: null,
        endDate: null,
        timeEnd: null
      },
      calendarPlugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
      calendarWeekends: true,
      calendarEvents: []
    };
  },
  methods: {
    getMeetingsCaseManager() {},
    saveEvent() {
      console.log("oal");

      axios
        .post("api/addEvent", this.event)
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style>
.demo-app {
  font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
  font-size: 14px;
}

.demo-app-top {
  margin: 0 0 3em;
}

.demo-app-calendar {
  margin: 0 auto;
  max-width: 900px;
}
</style>
