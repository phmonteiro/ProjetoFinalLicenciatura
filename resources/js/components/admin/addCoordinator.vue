<template>
  <b-container>
    <div class="container pt-2 pb-3">
      <h3>Adicionar coordenador de curso</h3>
      <div class="row">
        <div class="col">
          <label for="school">Escola</label>

          <select class="custom-select" name="school" id="school" single v-model="data.school">
            <option selected value="ESTG">ESTG</option>
            <option value="ESECS">ESECS</option>
            <option value="ESSLei">ESSLei</option>
            <option value="ESAD.CR">ESAD.CR</option>
            <option value="ESTM">ESTM</option>
          </select>
        </div>
        <div class="col">
          <label for="email">Email do Coordenador</label>
          <input type="email" class="form-control" name="email" id="email" v-model="data.email" />
        </div>
      </div>
      <div class="row">
        <div class="col">
          <label for="course">Nome do curso</label>
          <input class="form-control" name="course" id="course" v-model="data.course" />
        </div>
        <div class="col">
          <label for="departmentNumber">Número do departamento</label>
          <input
            type="number"
            min:0000
            max:9999
            class="form-control"
            name="departmentNumber"
            id="departmentNumber"
            v-model="data.departmentNumber"
          />
        </div>
      </div>

      <button class="btn btn-primary" type="submit" v-on:click.prevent="addCoordinator()">Adicionar</button>
    </div>
  </b-container>
</template>

<script>
export default {
  data() {
    return {
      data: {
        email: null,
        course: null,
        departmentNumber: null,
        school: null
      }
    };
  },
  methods: {
    addCoordinator() {
      axios
        .post("api/addCoordinator", this.data)
        .then(response => {
          console.log(response);
          this.$toasted.success(
            "Coordenador de curso adicionado com sucesso.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(error.response.data.message, {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        });
    }
  }
};
</script>

<style>
</style>
