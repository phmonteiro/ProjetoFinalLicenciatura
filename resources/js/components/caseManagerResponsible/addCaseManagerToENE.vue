<template>
    <div>
        <br>
        <h3>Lista de ENEs sem Gestor de Caso</h3>
        <hr v-if="users==null || users.length===0">
        <b-table striped hover v-if="users!=null && users.length>0" :items="users" :fields="fields">
        <template v-slot:cell(actions)="row">
            <button class="btn btn-secondary" v-on:click.prevent="addCM(row.item)">Atribuir</button>
        </template>
    </b-table>
     <h5 v-else>Neste momento todos os ENEs já têm um Gestor de Caso atribuído.</h5>


        <div v-if="showAddCM">
            <div class="form-group">
            <label for="inputName">Nome</label>
            <input
                type="text"
                class="form-control"
                v-model="user.name"
                name="name"
                id="inputName"
                disabled
            >
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
            >
        </div>
            <br>
            <h4>Adicionar Gestor de caso</h4> <br>

            <select v-model="cm">
                <option value="">Escolha um Gestor Caso</option>
                <option v-for="cm in caseManagers" :value="cm">{{cm.name}}</option>
            </select>
            <br>
            <br>
            <b-button variant="outline-success" v-on:click.prevent="setCM()">Atribuir</b-button>
            <b-button v-on:click.prevent="cancel()">Cancelar</b-button>

        </div>
    </div>
</template>

<script>
  export default {
    name: 'addCaseManagerToStudent',
    data(){
        return{
            showAddCM:false,
            caseManagers:null,
            users:null,
            user:{
                name:null,
                email:null,
            },
            pagination:{},
            cm:"",
            data: {
                cmEmail: "",
                cmName: "",
                studentName: ""
            },
            fields: [
                {
                    key: "name",
                    label: "Nome",
                    sortable: true
                },
                {
                    key: "email",
                    label: "Email",
                    sortable: true
                },
                {
                    key: "type",
                    label: "Tipo de utilizador",
                    sortable: true
                },
                {
                    key: "actions",
                    label: "Ações"
                }
            ],
        }
    },
      methods:{
          cancel(){
              this.showAddCM = false;
              this.cm="";
          },
          getStudent(page_url) {
              let pg = this;
              page_url = page_url || "api/getEneWithoutCaseManager?page=1";
              axios
                  .get(page_url)
                  .then(response => {
                      this.loading = false;
                      this.users = response.data.data;
                      pg.makePagination(response.data.meta, response.data.links);
                      this.loading = false;
                  })
                  .catch(error => {
                      console.log(error);
                      this.loading = false;
                  });
          },
          makePagination(meta, links) {
              let pagination = {
                  current_page: meta.current_page,
                  last_page: meta.last_page,
                  next_page_url: links.next,
                  prev_page_url: links.prev
              };
              this.pagination = pagination;
          },
          addCM(row){
              this.showAddCM = true;

              if(this.showAddCM===false){
                  this.user = null;
              }else{
                  this.user = row;
              }
          },
          getCMs(){
              axios.get('api/getAllCMs')
                  .then(response=>{
                      this.caseManagers=response.data;
                  })
                  .catch(error=>{
                      console.log(error);
                  });
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
      },
      created(){
          this.getStudent();
          this.getCMs();

      }

  };
</script>

<style scoped>

</style>
