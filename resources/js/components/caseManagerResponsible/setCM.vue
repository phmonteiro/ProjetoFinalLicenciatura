<template>
  <div>
    <loading :show="loading" :label="label"></loading>

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
          name="email"
          id="inputEmail"
          disabled
        />
      </div>

      <h4>Gestor(es) de caso</h4><br>
      <div v-if="Object.keys(studentCMs).length !== 0 ">
        <div v-for="(cm, index) in studentCMs" :key="index">
          <b-container>
            <b-row>
              <b-col class="p-3">
                <li>Nome: {{cm.caseManagerName}}, Email: {{cm.caseManagerEmail}}</li>
              </b-col>
              <b-col>
                <b-button variant="btn btn-danger" v-on:click.prevent="removeCM(cm)">remover</b-button>
              </b-col>
            </b-row>
          </b-container>
        </div>
      </div>
      <h5 v-if="Object.keys(studentCMs).length === 0 ">Não existe nenhum Gestor Caso atribuido.</h5><br>
        <br>
        <h4>Adicionar Gestor de caso</h4> <br>

        <select :disabled="Object.keys(studentCMs).length !== 0 || Object.keys(caseManagers).length === 0 " v-model="cm">
            <option value="">Escolha um Gestor Caso</option>
            <option v-for="cm in caseManagers" :value="cm">{{cm.name}}</option>
        </select>
        <br>
        <br>
            <b-button :disabled="Object.keys(studentCMs).length !== 0 || Object.keys(caseManagers).length === 0" variant="outline-success" v-on:click.prevent="setCM()">Atribuir</b-button>
            <b-button  v-on:click.prevent="cancel()">Cancelar</b-button>

        <br>
        <code v-if="Object.keys(studentCMs).length !== 0 ">ENEE já tem um Gestor de Caso atribuido</code>
        <code v-if="Object.keys(caseManagers).length === 0 ">Não existem Gestor Caso na aplicação. Contacte o administrador.</code>

    </div>
  </div>
</template>

<script>
import loading from "vue-full-loading";

export default {
  components: {
    loading
  },
  props: ["user", "studentCMs"],
  data: function() {
    return {
      data: {
        cmEmail: "",
        cmName: "",
        studentName: ""
      },
      meeting: {
        info: null,
        date: null
      },
      caseManagers:[],
      cm:"",
      loading: false,
      label: "Definindo gestor de caso"
    };
  },
  methods: {
    cancel() {
      this.$emit("cancel-edit");
    },
    setCM() {
        if(this.cm===""){
            this.$toasted.error(
                "Por favor escolha um Gestor de Caso.",
                {
                    duration: 4000,
                    position: "top-center",
                    theme: "bubble"
                }
            );
        }else{
            this.loading = true;

            this.data.studentName = this.user.name;
            this.data.cmEmail= this.cm.email;
            this.data.cmName= this.cm.name;

      axios
        .post("api/setCM/" + this.user.id, this.data)
        .then(response => {
          this.loading = false;
          this.$toasted.success("Gestor de caso atribuido.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });

            this.$emit("save-user");
        })
        .catch(error => {
          this.loading = false;
          this.$toasted.error(
            "Erro ao atribuir gestor caso. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
        }
    },
    removeCM(cm) {
      console.log(cm);
      axios
        .delete("api/removeCM/" + cm.id)
        .then(response => {
          this.$emit("refreshCMs");
          this.$toasted.success("Gestor de caso removido.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(
            "Erro ao remover gestor caso. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    },
      getCMs(){
          axios.get('api/getAllCMs')
          .then(response=>{
              this.caseManagers=response.data;
          })
          .catch(error=>{
              console.log(error);
          });
      }
  },
    created() {
      this.getCMs();
    },
    mounted() {}

};
</script>
