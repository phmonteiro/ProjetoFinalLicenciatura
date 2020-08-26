<template>
  <div class="container mt-3" v-if="user">

   <h4>Exportar para PDF</h4>
    <button class="btn btn-outline-success" @click.prevent="createPDF()">Exportar PDF</button>
    <div class="form-group">
        <br>
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
      <label for>E-mail</label>
      <input
        type="text"
        class="form-control"
        v-model="user.email"
        name="coordinatorAproval"
        id="inputcoordinatorAproval"
        disabled
      />
    </div>

      <h4>Apoios Usufruídos</h4>
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
      <h4>Professor Orientador</h4>
      <div class="form-group" v-if="studentTutor!=null">
          <label for>Email:</label>
          <li>{{ studentTutor }}</li>
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

    <div class="form-group">
      <button type="submit" class="btn btn-outline-success" name="ok" v-on:click.prevent="save()">Gravar</button>
    </div>
      <div class="dropdown-divider"></div>
      <h4>Estatuto ENE</h4>
      <div>
          <div v-if="user.eneeExpirationDate">
              <span>Tipo de Estatuto: Temporário</span>
              <span>Término de Estatuto a: {{user.eneeExpirationDate}}</span>
          </div>
          <div v-else>
              <span>Tipo de Estatuto: Permanente</span>
          </div>
      </div>
      <br>
      <div class="form-group">
          <button type="submit" class="btn btn-outline-danger" name="ok" v-on:click.prevent="removerEstatuto()">Cancelar Estatuto ENE</button>
      </div>
    <div class="form-group text-center">
        <button class="btn btn-outline-secondary" v-on:click.prevent="cancel()">Cancelar</button>
    </div>
  </div>
</template>
<script>
    import jsPDF  from "jspdf";
export default {
  components:{
  },
  props: ["user", "studentSupports", "studentTutor"],
  data: function() {
    return {
        nee:null,
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
      createPDF () {
          let pdfName = 'comprovativo_estatuto_ene'+this.user.number;
          let doc = new jsPDF();
          // var imgData = 'data:image/jpeg;base64,'+ Base64.encode('images.png');
          // console.log(imgData);
          // doc.addImage(imgData, 'PNG', 15, 40, 180, 160);
          doc.text("\n",10,10);
          doc.text("    Declaro que o aluno "+this.user.name+",",20,30);
          doc.text("nº "+this.user.number+", que frequenta o curso de "+this.user.course,20,40);
          doc.text("do Instituto Politécnico de Leiria na "+this.user.school+", tem direito ao",20,50);
          doc.text("Estatuto para Estudantes com Necessidades Específicas.",20,60);
          doc.text("\n",20,70);
          doc.text("\n",20,80);
          doc.text("\n",20,90);
          doc.text("Assinatura: ____________________",20,100);
          doc.text("\n",20,110);
          doc.text("O Diretor,",20,120);
          doc.text(this.auth.name,20,130);
          doc.save(pdfName + '.pdf');
      },
    cancel() {
      this.$emit("cancel-edit");
    },
    removerEstatuto(){
          let confirmed = confirm("Tem a certeza que pretende cancelar o estatuto para o aluno "+this.user.name+"?");

          if(!confirmed)
              return;
    axios
        .post("api/removeStudentStatus/" + this.user.id)
        .then(response => {
            this.$emit("refresh");
            this.$toasted.success("Estatuto removido com sucesso.", {
                duration: 4000,
                position: "top-center",
                theme: "bubble"
            });
            this.cancel();
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
      getNee(){
          console.log("-----------------------------");
          console.log(this.user);

          axios
              .get("api/getNee/" + this.user.id)
              .then(response => {
                  this.nee = response.data;
              })
              .catch(error => {
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
  mounted(){
  },
  updated() {
      if(this.nee==null) this.getNee();
  },
    created() {
        this.getSupports();
        this.getSupportsByStudent();
  },
    computed: {
    state() {
      return this.value.length === 1;
    },
      auth: function() {
          return this.$store.state.user;
      }
  }
};
</script>
