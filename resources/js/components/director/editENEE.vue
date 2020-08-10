<template>
  <div class="container mt-3" v-if="user">
    <div class="form-group">
      <h4>Estudante:</h4>
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
        name="coordinatorAproval"
        id="inputcoordinatorAproval"
        disabled
      />
    </div>

      <div id="app">
              <span v-for="category in categories">
                  <li style="list-style-type:none">
                      <label>
                          {{category.name}}
                      </label>
                  </li>

                  <ul>
                          <b-form-checkbox disabled v-model="selectedSupports" :value="sup.id" v-for="sup in category.supports">
                              {{ sup.name }}
                          </b-form-checkbox>
                  </ul>
              </span>

      </div>

      <div class="form-group" v-if="studentTutor!=null">
      <label for>Professor Orientador</label>
          <li>{{ studentTutor.name }} - {{studentTutor.email}}</li>
      </div>


    <div class="form-group">
      <label v-if="studentTutor!=null">Substituir Professor Orientador</label>
      <label v-if="studentTutor==null">Definir Professor Orientador</label>
      <input
        type="email"
        class="form-control"
        v-model="data.tutor"
        name="tutor"
        id="tutor"
        placeholder="nome.professor@ipleiria.pt"
      />
    </div>

    <div class="form-group text-center">
      <button type="submit" class="btn btn-success" name="ok" v-on:click.prevent="save()">Aprovar</button>
      <button class="btn btn-secondary" v-on:click.prevent="cancel()">Cancelar</button>
    </div>
  </div>
</template>
<script>
export default {
  props: ["user", "studentSupports", "studentTutor"],
  data: function() {
    return {
        selectedSupports:[],
        selectedCategories:[],
        categories:[],
      data: {
        tutor: ""
      },
      form: {
        duration: null
      },
      options: [],
      childData: ""
    };
  },
  methods: {
    cancel() {
      this.$emit("cancel-edit");
    },
    askForServicesApproval() {
      axios
        .post("api/servicesApprovalRequest/" + this.user.id)
        .then(response => {
          this.$emit("refresh");
          this.$toasted.success("Pedido efetuado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          this.$toasted.error(
            "Erro ao fazer pedido. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    },
    save: function() {
      let data = {
        email: this.user.email,
        supports: this.studentSupports,
        tutor: this.data.tutor
      };
      this.$emit("save-user", data);
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
  },
  created() {
    this.getSupports();
    this.getSupportsByStudent();
  },
  computed: {
    state() {
      return this.value.length === 1;
    }
  }
};
</script>
