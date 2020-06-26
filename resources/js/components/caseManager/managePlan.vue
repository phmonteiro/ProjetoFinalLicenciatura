<template>
  <div class="container mt-3">
    <div class="form-group">
      <h4>Gerir Plano Individual de Inclusão </h4>
      <label for="inputName">Nome</label>
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
    <div >

    </div>
      <label for><b>Categorias de Apoios</b></label>
      <div id="app">
              <span v-for="category in categories">
                  <li style="list-style-type:none">
<!--                      <b-form-checkbox v-if="!category.supports.length" v-model="selectedCategories" :value="category.id">-->
<!--                              {{ category.name }}-->
<!--                          </b-form-checkbox>-->
                      <u>
                          {{category.name}}:
                      </u>
                  </li>

                  <ul>
                          <b-form-checkbox v-model="selectedSupports" :value="sup.id" v-for="sup in category.supports">
                              {{ sup.name }}
                          </b-form-checkbox>
                  </ul>
              </span>

      </div>
      <br>
<!--      <h4><b>Plano Individual de Inclusão</b></h4>-->

<!--      <div id="app">-->
<!--              <span v-for="category in categories">-->
<!--                  <li>{{category.name}}</li>-->
<!--                  <ul>-->
<!--                          <b-form-checkbox v-model="selectedSupports" :value="sup.id" v-for="sup in category.supports">-->
<!--                              {{ sup.name }}-->
<!--                          </b-form-checkbox>-->
<!--                  </ul>-->
<!--              </span>-->
<!--      </div>-->
      <div class="dropdown-divider"></div>

    <div v-if="plan">
        <h4><b>Informação Adicional</b></h4>
        <b-form-textarea
        id="textarea"
        v-model="plan.plan"
        placeholder="Informação Adicional..."
        rows="3"
        max-rows="6"
      ></b-form-textarea>

      <div class="dropdown-divider"></div>

        <h4><b>Diligências</b></h4>
      <b-form-textarea
        id="textarea"
        v-model="plan.diagnostic"
        placeholder="Diligências..."
        rows="3"
        max-rows="6"
      ></b-form-textarea>
    </div>

    <div v-else>
        <h4><b>Informação Adicional</b></h4>
        <b-form-textarea
        id="textarea"
        v-model="data.plan"
        placeholder="Informação Adicional..."
        rows="3"
        max-rows="6"
      ></b-form-textarea>
        <br>
      <div class="dropdown-divider"></div>

      <h4><b>Diligências</b></h4>
      <b-form-textarea
        id="textarea"
        v-model="data.diagnostic"
        placeholder="Diligências..."
        rows="3"
        max-rows="6"
      ></b-form-textarea>
    </div>
      <br>
    <b-button variant="outline-success" v-on:click.prevent="save()">Guardar</b-button>
    <b-button variant="outline-info" v-on:click.prevent="cancel()">Cancelar</b-button>
  </div>
</template>
<script>
export default {
  props: ["user", "plan"],
  data: function() {
    return {
        selectedSupports:[],
        selectedCategories:[],
      categories:[],
      data: {
        plan: "",
        diagnostic: "",
        studentId: ""
      }
    };
  },
  methods: {
    cancel() {
      this.$emit("cancel-edit2");
    },
    save: function() {
      this.data.studentId = this.user.id;
      if (this.plan != null) {
        this.data.plan = this.plan.plan;
        this.data.diagnostic = this.plan.diagnostic;

        this.data.selectedSupports=this.selectedSupports;

        axios
          .put("api/updatePlan/" + this.plan.id, this.data)
          .then(response => {
            this.$toasted.success("Guardado com sucesso.", {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            });
            this.$emit("cancel-edit2");
            this.data = {};
          })
          .catch(error => {
            console.log(error);
            this.$toasted.error("Erro ao guardar. Por favor tente novamente.", {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            });
          });
      } else {
          this.data.selectedSupports=this.selectedSupports;

          axios
          .post("api/setPlan/", this.data)
          .then(response => {
            this.$toasted.success("Guardado com sucesso.", {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            });
            this.$emit("cancel-edit2");
            this.data = {};
          })
          .catch(error => {
            console.log(error);
            this.$toasted.error("Erro ao guardar. Por favor tente novamente.", {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            });
          });
      }
    },
    getSupports(){
        axios.get('api/getSupportsByCategory')
            .then(response => {
                this.categories = response.data;
            })
            .catch(error=>{
                console.log(error);
            });

    },
    getSupportsByStudent(){
        axios
            .get('api/getStudentSupports/'+this.user.email)
            .then(response=>{
                let studentSupports = response.data;
                studentSupports.forEach(support=>{
                    this.selectedSupports.push(support);
                })
            })
            .catch(error=>{
                console.log(error);
            })
    },
    handleFiles() {
      this.files = [];
      let uploadedFiles = this.$refs.files.files;
      for (var i = 0; i < uploadedFiles.length; i++) {
        this.files.push(uploadedFiles[i]);
      }
    }
  },
  created() {
      this.getSupports();
      this.getSupportsByStudent();
  }
};
</script>
