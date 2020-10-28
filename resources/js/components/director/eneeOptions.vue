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
    <div class="form-group">
      <label for>Necessidades específicas</label>
      <div v-for="aux in nee">
        <li>{{aux.name}}</li>
      </div>
    </div>

    <div class="form-group" v-if="user.functionalAnalysis!=null">
      <label for="inputAnalysis">Análise Funcional</label>
      <input
        type="text"
        class="form-control"
        v-model="user.functionalAnalysis"
        name="functionalAnalysis"
        id="functionalAnalysis"
        disabled
      />
    </div>

    <div class="form-group" v-if="user.educationalSupport!=null">
      <label for="inputAnalysis">Apoios Prévios</label>
      <input
        type="text"
        class="form-control"
        v-model="user.educationalSupport"
        name="functionalAnalysis"
        id="inputAnalysis"
        disabled
      />
    </div>

    <div class="form-group">
      <button
        class="btn btn-secondary"
        v-on:click.prevent="downloadPDF(user.id)"
        v-if="user.enee!=='reproved'"
      >Download ficheiros médicos</button>
    </div>
      <ValidationObserver v-slot="{ handleSubmit }">
      <div>

      <div class="form-group">
        <h4 for="coordinatorApproval">Parecer do coordenador de curso:</h4>
          <div v-if="coordinator.email!= null && coordinator.email!==''">
              <span>Email do Coordenador de Curso de {{user.course}}: </span>
              <b>{{coordinator.email}}</b>
              <br>
              <span v-if="coordinator.secondaryEmail">Email Secundário: </span>
              <b>{{coordinator.secondaryEmail}}</b>
          </div>
          <div v-else>
              <span>O Email do Coordenador de Curso de {{user.course}} ainda não está definido. Por favor defina o email para avançar no processo.</span>
          </div>

          <div v-if="coordinator.email">
              <div v-if="user.coordinatorApproval!==1 && user.coordinatorApproval!==0">
                  <label for>Alterar Email do Coordenador de Curso de {{user.course}}</label>
                  <button class="btn btn-outline-secondary" @click.prevent="alterarEmail()">Alterar</button>
              </div>
              <div v-if="showAlterarEmail">
                  <b-input-group prepend="Email do Novo Coordenador de Curso: " class="mt-sm-4 p-sm-4 w-75">
                      <b-form-input v-model="newCoordinatorEmail"></b-form-input>
                      <b-input-group-append>
                          <b-button variant="outline-success" v-on:click.prevent="saveNewCoordinatorEmail()">Gravar</b-button>
                      </b-input-group-append>
                  </b-input-group>
              </div>
          </div>
          <div v-else>
              <label for>Definir Email do Coordenador de Curso de {{user.course}}</label>
              <button class="btn btn-outline-secondary" @click="adicionarEmail()">Definir</button>
              <br>
          </div>
          <div v-if="showAddEmail">
              <b-input-group prepend="Email do Coordenador de Curso: " class="mt-sm-4 p-sm-4 w-75">
                  <b-form-input v-model="newCoordinatorEmail"></b-form-input>
                  <b-input-group-append>
                      <b-button variant="outline-success" v-on:click.prevent="saveNewCoordinatorEmail()">Gravar</b-button>
                  </b-input-group-append>
              </b-input-group>
          </div>
          <br>
          <div>
              <p v-if="user.coordinatorApproval==null">
                  <span>Confirme o email do Coordenador de Curso e peça o parecer do mesmo.</span>
              </p>
              <p v-if="user.coordinatorApproval==1">
                  <b>O pedido foi aprovado pelo Coordenador de Curso.</b>
              </p>
              <p v-if="user.coordinatorApproval==0">
                  <b>O pedido foi rejeitado pelo Coordenador de Curso.</b>
              </p>
              <p v-if="user.coordinatorApproval==-1">
                  <span>Pedido de parecer enviado - a aguardar resposta</span>
                  <br>
              </p>
          </div>

          <div>
<!--              <code v-if="user.coordinatorApproval == null">O parecer do Coordenador de Curso ainda não foi requisitado. Impossível avançar.</code>-->
              <code v-if="user.coordinatorApproval === -1">O parecer do Coordenador de Curso ainda não foi emitido. Impossível avançar.</code>
          </div>
          <br>

          <button v-if="user.coordinatorApproval!==1 && user.coordinatorApproval!==0" class="btn btn-secondary" :disabled="coordinator.email == '' || coordinator == null" @click.prevent="pedirParecer()">Pedir Parecer</button>

      </div>

      <div class="form-group">
        <h4 for="servicesApproval">Parecer dos serviços:</h4>
        <div v-if="user.servicesApproval=='denied' || user.servicesApproval=='approved'">
          <div class="container" v-for="aux in service">
            <div class="row">
              <div class="col-sm-2">{{aux.name}}</div>
              <div v-if="aux.approval==null" class="col-sm-2">Pendente...</div>
              <div v-else class="col-sm-2"><b>{{aux.approval}}</b></div>
            </div>
              <div v-if="aux.comment">
                  Informação adicional disponibilizada pelo {{aux.name}}:
                  {{aux.comment}}
              </div>
              <hr>
          </div>
        </div>
        <p v-if="user.servicesApproval==null">
          <span>Opcional - Pode solicitar parecer através do seguinte formulário:</span>
        </p>
          <p v-if="user.servicesApproval=='requested'">
              <span>Parecer solicitado - a aguardar resposta</span>
          </p>
        <div v-if="user.servicesApproval==null">
          <b-form-group>
            <b-form-checkbox-group id="checkbox-group-2" v-model="services.name" name="service">
              <b-form-checkbox :disabled="requestedServices.includes('SAPE')" value="SAPE">Serviço de Apoio ao Estudante</b-form-checkbox>
              <b-form-checkbox :disabled="requestedServices.includes('CRID')" value="CRID">Centro de Recursos para a Inclusão Digital</b-form-checkbox>
              <b-form-checkbox :disabled="requestedServices.includes('SAS')" value="SAS">Serviços de Ação Social</b-form-checkbox>
              <b-form-checkbox :disabled="requestedServices.includes('UED')" value="UED">Unidade de Ensino à Distância</b-form-checkbox>
              <b-form-checkbox :disabled="requestedServices.includes('DST')" value="DST">Direção de Serviços Técnicos</b-form-checkbox>
            </b-form-checkbox-group>
          </b-form-group>
          <button
            type="submit"
            class="btn btn-secondary"
            name="ok"
            v-on:click.prevent="askForServicesApproval()"
          >Pedir parecer</button>
        </div>
      </div>

      <div>
          <h4>Duração da Necessidade Específica:</h4>
          <ValidationProvider name="duracaoEne" rules="required" v-slot="{ errors }">
              <b-form-group id="input-group-3" label-for="input-3">
                  <b-form-select id="input-3" v-model="form.duration" :options="durationOpts" required></b-form-select>
              </b-form-group>
              <code>{{ errors[0] }}</code>
          </ValidationProvider>
      </div>
      <div v-if="form.duration=='Temporária'">
          <label for="date">Data de fim de estatuto ENE</label>
          <ValidationProvider name="duracaoEne" rules="required" v-slot="{ errors }">
              <input class="form-control" type="date" value id="date" name="date" v-model="data.date" />
              <code>{{ errors[0] }}</code>
          </ValidationProvider>
      </div>



    </div>
      <br>
         <b-form-checkbox v-model="gestorCasoApoios">Plano de Inclusão a ser definido pelo Gestor de Caso.</b-form-checkbox>
      <br>
      <div class="form-group">
          <h4>Professores a notificar</h4>
          <code v-if="teachers == null || teachers.length === 0">Atualmente, o estudante não está inscrito a nenhuma UC.</code>
          <br>
          <ul v-if="aux.length!=0">
              <li v-for="prof in aux">
                  {{prof.name}} - {{prof.subject}}
              </li>
          </ul>
          <span v-else>Nenhum professor selecionado.</span>
          <br>
          <br>
          <b-button
              :disabled="teachers == null || teachers.length === 0"
              type="button"
              class="btn btn-primary"
              data-toggle="modal"
              data-target="#exampleModalLong"
          >Notificar Professores</b-button>
      </div>

      <div class="form-group">
      <h4>Definir Professor Orientador (Opcional)</h4>
          <div v-if="studentTutorEmail">
              Professor Orientador Atual: {{studentTutorEmail}}
          </div>
          <br>
<!--      <input-->
<!--        type="email"-->
<!--        class="form-control"-->
<!--        v-model="data.tutor"-->
<!--        name="tutor"-->
<!--        id="tutor"-->
<!--        placeholder="professor.orientador@my.ipleiria.pt"-->
<!--      />-->
      <b-input-group prepend="Email do Professor Orientador: ">
          <b-form-input v-model="data.tutor"
                        name="tutor"
                        id="tutor"
                        placeholder="professor.orientador@my.ipleiria.pt"></b-form-input>
      </b-input-group>
    </div>

    <!-- Button trigger modal -->
    <button
      type="button"
      class="btn btn-success"
      v-on:click.prevent="handleSubmit(save)"
    >Aprovar</button>
     <b-button class="btn btn-danger"  @click="cancel()">Cancelar</b-button>
    </ValidationObserver>

    <div
      class="modal fade"
      id="exampleModalLong"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLongTitle"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5
              class="modal-title"
              id="exampleModalLongTitle"
            >Definir professores notificados do estatuto</h5>
          </div>
          <div class="modal-body">
              <div>
              <ClipLoader sizeUnit="px" class="loading" v-if="!teachers" :size="15" />
            </div>
              <div v-if="teachers">

                <div class="switch-sex">
                        <b-form-checkbox autofocus v-for="teacher in teachers" :value="teacher" v-model="aux" >
                        <label>
                            Professor {{teacher.name}}
                            <br />
                            Disciplina: {{teacher.subject}}
                        </label>
                        </b-form-checkbox>
                        <br>
                </div>

              <button
                type="button"
                class="btn btn-danger"
                v-on:click.prevent="cancelAux()"
              >Limpar seleção</button>
              <button
                  type="button"
                  class="btn btn-info"
                  v-on:click.prevent="selectAllTeachers()"
              >Selecionar Todos</button>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-target="#exampleModalLong"
              data-dismiss="modal"
            >Fechar</button>

          </div>
        </div>
      </div>
    </div>
  </div>

</template>
<script>
export default {
  props: ["user", "nee", "teachers"],
  data: function() {
    return {
      service: null,
      aux: [],
      newCoordinatorEmail:"",
      coordinator:null,
      showAlterarEmail:false,
        requestedServices:[],
        showAddEmail:false,
      // showCoordinatorNoResponseError:false,
        studentTutorEmail: null,
        gestorCasoApoios:true,
        selectedSupports:[],
        selectedCategories:[],
        categories:[],
        data: {
        selected: null,
        options: null,
        tutor: "",
        date: null
      },
      services: {
        name: ""
      },
      form: {
        duration: null
      },
      durationOpts: [
        {
          text: "Escolha uma",
          disabled: true,
          value: null
        },
        "Temporária",
        "Permanente"
      ],
      childData: ""
    };
  },
  methods: {
      getStudentTutor(){
          axios
              .get("api/getStudentTutor/" + this.user.id)
              .then(response => {
                  this.studentTutorEmail = response.data['tutorEmail'];
                    console.log(response.data);
                  if(this.isEmpty(response.data))
                      this.studentTutorEmail=null;

              })
              .catch(error => {
                  console.log(error);
              });
      },
       isEmpty(obj) {
            for(var prop in obj) {
                if(obj.hasOwnProperty(prop)) {
                    return false;
                }
            }

            return JSON.stringify(obj) === JSON.stringify({});
        },
      saveNewCoordinatorEmail(){
        axios.
            post("/api/defineCoordinatorEmail/"+this.user.departmentNumber,{'coordinatorEmail':this.newCoordinatorEmail})
                .then(response=>{
                    this.$toasted.success(
                        "Email guardado com sucesso!",
                        {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        }
                    );
                    this.alterarEmail();
                    this.coordinator.email = this.newCoordinatorEmail;
                    this.secondaryEmail = null;
                })
                .catch(error=>{
                    console.log(error);
                });
      },
      alterarEmail(){
          this.showAlterarEmail = !this.showAlterarEmail;
      },
      adicionarEmail(){
          this.showAddEmail = !this.showAddEmail;
      },
      selectAllTeachers(){
          this.aux = this.teachers;
      },
    downloadPDF(id) {
      axios({
        url: "api/medicalReport/download/" + id,
        method: "GET",
        responseType: "blob"
      })
        .then(response => {
          console.log(response);

          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "Medical  report" + this.user.name + ".zip");
          document.body.appendChild(link);
          link.click();
          this.$toasted.success(
            "Download do relatório médico do estudante feito com sucesso.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        })
        .catch(error => {
          console.log(error.message);

          this.$toasted.error(
            "Error ao fazer download do relatório médico do estudante. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    },
    cancel() {
      this.$emit("cancel-edit");
    },
    askForServicesApproval() {
      this.requestedServices = this.services.name;

      axios
        .post("api/getServicesEvaluation/" + this.user.id, this.services)
        .then(response => {
          this.$emit("refresh");
          this.$toasted.success("Pedido efetuado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          this.$toasted.error("Erro ao pedir. Por favor tente novamente.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        });
    },
    save: function() {
        if(this.user.coordinatorApproval !== 1 && this.user.coordinatorApproval !== 0){
            return;
        }

        let teacherEmails=[];
       this.aux.forEach(teacher=>{
           teacherEmails.push(teacher.email);
       });

      let data = {
        teachers: teacherEmails,
        email: this.user.email,
        supports: this.selectedSupports,
        tutor: this.data.tutor,
        date: this.data.date,
        duration: this.form.duration
      };
      if(this.form.duration == null){
          this.$toasted.error("Por favor escolha a duração do estatuto: temporário ou permanente", {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
          });
      }else{
      this.$emit("save-user", data);
      }
    },
    cancelAux: function() {
      this.aux = [];
    },
    getServicesEvaluation() {
      axios
        .get("api/getServicesEvaluation/" + this.user.id)
        .then(response => {
          this.service = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getCoordinatorEmail(){
          axios.
              get('/api/getCoordinatorEmail/'+this.user.departmentNumber)
              .then(response=>{
                  this.coordinator = response.data;
              })
              .catch(error=>{
                  console.log(error);
              })
    },
      pedirParecer() {
          axios
              .post('/api/pedirParecerCoordenador/'+this.user.number)
                .then(response=>{
                    this.user.coordinatorApproval = -1;
                    this.$toasted.success("O Pedido de Parecer do Coordenador de Curso foi emitido.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                })
                .catch(error=>{
                    this.$toasted.error("Erro ao pedir o Parecer do Coordenador de Curso.", {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    });
                });


      }
  },
  created() {
    this.getServicesEvaluation();
    this.getCoordinatorEmail();
    this.getStudentTutor();
  },

  computed: {
    state() {
      return this.value.length === 1;
    }
  }
};
</script>
<style>
body.modal-open {
  overflow: visible;
}
</style>
