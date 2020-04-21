<template>
  <div class="container mt-3" v-if="user">
    <div class="form-group">
      <h4>Agendar nova reunião:</h4>
      <label for="inputName">{{this.$t('Nome')}}</label>
      <input
        type="text"
        class="form-control"
        v-model="user.name"
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
        v-model="user.email"
        name="email"
        id="inputEmail"
        disabled
      />
    </div>

    <div class="form-group">
      <label for="decision">{{this.$t('informação')}}</label>
      <input class="form-control" id="decision" v-model="data.info" name="decision" rows="3" />
    </div>

    <div class="form-group">
      <label for="place">Local:</label>
      <input class="form-control" id="place" v-model="data.place" name="place" rows="3" />
    </div>

    <div class="form-group">
          <!-- <date-picker v-model="data.date" valuetype="'YYYY-MM-DD'" type="date" format='YYYY-MM-DD' lang="pt-br"></date-picker> -->
          <!-- <div v-for="type in types" :key="type"> -->

          <label :for="meetingDate">{{this.$t('data')}}</label>
          <b-form-input class="col-md-2" id="meetingDate" type="date" v-model="data.date"></b-form-input>
          <!-- </div> -->
        <br>
          <!-- <date-picker v-model="data.date" valuetype="'YYYY-MM-DD'" type="date" format='YYYY-MM-DD' lang="pt-br"></date-picker> -->
              <!-- <div v-for="type in types" :key="type"> -->
            <label :for="meetingTime">{{this.$t('hora')}}</label>
            <b-form-input class="col-md-2" id="meetingTime" name="meetingTime" type="time" v-model="data.time"></b-form-input>
              <!-- </div> -->
        <!-- <b-col>
          <label for="information">Hora</label>
          <div v-for="type in types" :key="type">
            <b-form-input :id="`type-${type}`" :type="type" v-model="data.time"></b-form-input>
          </div>
        </b-col> -->

    </div>

    <b-button variant="outline-success" v-on:click.prevent="save()">{{this.$t('gravar')}}</b-button>
    <b-button variant="outline-info" v-on:click.prevent="cancel()">{{this.$t('cancelar')}}</b-button>
  </div>
</template>
<script>
export default {
  props: ["user"],
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
      this.$emit("save-meeting", this.data);
    }
  },
  mounted() {}
};
</script>
