<template>
  <div class="container mt-3" v-if="meetingRequest">
    <div class="form-group">
      <h4>Agendar nova reunião:</h4>
      <label for="inputName">Nome</label>
      <input
        type="text"
        class="form-control"
        v-model="meetingRequest.name"
        name="name"
        id="inputName"
        disabled
      />
    </div>

    <div class="form-group">
      <label for="inputEmail">E-mail</label>
      <input
        type="text"
        class="form-control"
        v-model="meetingRequest.email"
        name="email"
        id="inputEmail"
        disabled
      />
    </div>

      <div class="form-group">
          <label for="comment">Descrição do Pedido</label>
          <input
              type="text"
              class="form-control"
              v-model="meetingRequest.comment"
              name="comment"
              id="comment"
              disabled
          />
      </div>



    <div class="form-group">
      <label for="decision">Informação</label>
      <input class="form-control" id="decision" v-model="data.info" name="decision" rows="3" />
    </div>

    <div class="form-group">
      <label for="place">Local</label>
      <input class="form-control" id="place" v-model="data.place" name="place" rows="3" />
    </div>

    <div class="form-group">
          <!-- <date-picker v-model="data.date" valuetype="'YYYY-MM-DD'" type="date" format='YYYY-MM-DD' lang="pt-br"></date-picker> -->
          <!-- <div v-for="type in types" :key="type"> -->

          <label>Data</label>
          <b-form-input class="col-md-2" name="meetingDate" id="meetingDate" type="date" v-model="data.date"></b-form-input>
          <!-- </div> -->
        <br>
          <!-- <date-picker v-model="data.date" valuetype="'YYYY-MM-DD'" type="date" format='YYYY-MM-DD' lang="pt-br"></date-picker> -->
              <!-- <div v-for="type in types" :key="type"> -->
            <label>Hora</label><br>
        <vue-timepicker :minute-interval="5" format="HH:mm" v-model="data.time" id="meetingTime" name="meetingTime">
        </vue-timepicker>
              <!-- </div> -->
        <!-- <b-col>
          <label for="information">Hora</label>
          <div v-for="type in types" :key="type">
            <b-form-input :id="`type-${type}`" :type="type" v-model="data.time"></b-form-input>
          </div>
        </b-col> -->

    </div>

    <b-button variant="outline-success" v-on:click.prevent="save()">Gravar</b-button>
    <b-button variant="outline-info" v-on:click.prevent="cancel()">Cancelar</b-button>
      <br><br><br><br><br><br><br>
  </div>
</template>
<script>
import VueTimepicker from 'vue2-timepicker/src/vue-timepicker';

export default {
    components: {
        VueTimepicker
    },
  props: ["meetingRequest"],
  data: function() {
    return {
      data: {
        place: "",
        time: "",
        date: "",
        info: ""
      },

      meeting: {
        info: null,
        date: null
      },
      files: [],
      types: ['date',`time`]
    };
  },
  methods: {
    cancel() {
      this.$emit("cancel-edit");
    },
      save: function() {
        if (new Date(this.data.date) < Date.now()) {
            this.$toasted.error( "Para agendar uma reunião tem de indicar uma data igual ou superior à data de hoje", {                     duration: 40000,
                position: "top-center",
                theme: "bubble" });
        } else {
            this.$emit("save-meeting", this.data);
        }
    }
  },
    updated(){
        if(!this.meetingRequest) {
            this.data.date = "";
            this.data.place = "";
            this.data.time = "";
            this.data.info = "";
        }
    },
};
</script>
