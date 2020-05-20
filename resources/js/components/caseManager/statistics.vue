<template>
  <b-container>
      <h4 for="selectBox">Selecione a estatística que quer visualizar:</h4><br>
    <select id="selectBox" class="custom-select" name="selectBox" single v-model="stats">
      <option value="curso" >Estatísticas por curso</option>
      <option value="nee">Estatísticas por necessidade educativa especial</option>
      <option value="escola">Estísticas por escola</option>
      <option value="sexo">Estatísticas por sexo</option>
    </select>
      <br><br>
      <div>
    <b-button  variant="outline-success" v-on:click.prevent="getData()">Mostrar estatísticas</b-button>
      </div>
    <div class="small">
      <line-chart v-if="data" :chart-data="data"></line-chart>
    </div>
  </b-container>
</template>

<script>
import LineChart from "./pieChart.js";

export default {
  components: {
    LineChart
  },
  data() {
    return {
      data: null,
      stats: "curso"
    };
  },
  methods: {
    getData() {
      if (this.stats == "curso") {
        axios
          .get("api/statistics/" + this.stats)
          .then(response => {
            this.data = {
              labels: response.data[0],
              datasets: [
                {
                  label: "Número de Estudantes por curso",
                  backgroundColor: "#f87979",
                  data: response.data[1]
                }
              ]
            };
          })
          .catch(error => {
            console.log(error);
          });
      }
      if (this.stats == "nee") {
        axios
          .get("api/statistics/" + this.stats)
          .then(response => {
            this.data = {
              labels: response.data[0],
              datasets: [
                {
                  label:
                    "Número de Estudantes por necessidade educativa especial",
                  backgroundColor: "#f87979",
                  data: response.data[1]
                }
              ]
            };
          })
          .catch(error => {
            console.log(error);
          });
      }

      if (this.stats == "escola") {
        axios
          .get("api/statistics/" + this.stats)
          .then(response => {
            this.data = {
              labels: response.data[0],
              datasets: [
                {
                  label: "Número de Estudantes por escola",
                  backgroundColor: "#f87979",
                  data: response.data[1]
                }
              ]
            };
          })
          .catch(error => {
            console.log(error);
          });
      }

      if (this.stats == "sexo") {
        axios
          .get("api/statistics/" + this.stats)
          .then(response => {
            this.data = {
              labels: response.data[0],
              datasets: [
                {
                  label: "Número de Estudantes por sexo",
                  backgroundColor: "#f87979",
                  data: response.data[1]
                }
              ]
            };
          })
          .catch(error => {
            console.log(error);
          });
      }
    }
  }
};
</script>

<style>
.small {
  max-width: 600px;
  margin: 150px auto;
}
</style>
