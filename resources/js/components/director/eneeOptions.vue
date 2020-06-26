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
    <div class="form-group">
      <label for="inputNee">Necessidades específicas</label>
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
        id="functionalAnalysis"
        disabled
      />
    </div>

    <div class="form-group">
      <button
        class="btn btn-secondary"
        v-on:click.prevent="downloadPDF(user.id)"
        v-if="user.enee!='reproved'"
      >Download ficheiros médicos</button>
    </div>
      <ValidationObserver v-slot="{ handleSubmit }">

      <div>
      <div class="form-group">
        <h4 for="coordinatorApproval">Parecer do coordenador de curso:</h4>
        <p v-if="user.coordinatorApproval==1">
          <span>Aprovado</span>
        </p>
        <p v-if="user.coordinatorApproval==0">
          <span>Rejeitado</span>
        </p>
        <p v-if="user.coordinatorApproval==null">
          <span>Pedido de parecer enviado automaticamente - a aguardar resposta</span>
            <br>
            <code v-if="showCoordinatorNoResponseError">O parecer do Coordenador de Curso ainda não foi emitido. Impossível avançar.</code>
        </p>
      </div>

      <div class="form-group">
        <h4 for="servicesApproval">Parecer dos serviços:</h4>
        <div v-if="user.servicesApproval=='denied' || user.servicesApproval=='approved'">
          <div class="container" v-for="aux in service">
            <div class="row">
              <div class="col-sm-2">{{aux.name}}</div>
              <div class="col-sm-2">{{aux.approval}}</div>
                <label v-if="aux.comment" for>Informação adicional</label>
              <div v-if="aux.comment" class="col-sm-8">{{aux.comment}}</div>
            </div>
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
              <b-form-checkbox value="SAPE">Serviços de Apoio ao Estudante</b-form-checkbox>
              <b-form-checkbox value="CRID">Centro de Recursos para a Inclusão Digital</b-form-checkbox>
              <b-form-checkbox value="SAS">Serviços de Ação Social</b-form-checkbox>
              <b-form-checkbox value="UED">Unidade de Ensino á Distância</b-form-checkbox>
              <b-form-checkbox value="DST">Direção de Serviços Técnicos</b-form-checkbox>
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


          <h4>Duração da Necessidade Específica:</h4>
          <ValidationProvider name="duracaoEne" rules="required" v-slot="{ errors }">
              <b-form-group id="input-group-3" label-for="input-3">
                  <b-form-select id="input-3" v-model="form.duration" :options="durationOpts" required></b-form-select>
                  <div v-if="form.duration=='Temporária'">
                      <label for="date">Data de fim de estatuto ENE</label>
                      <input class="form-control" type="date" value id="date" name="date" v-model="data.date" />
                  </div>
              </b-form-group>
              <code>{{ errors[0] }}</code>
          </ValidationProvider>
    </div>
      <br>
         <b-form-checkbox v-model="gestorCasoApoios">Plano de Inclusão a ser definido pelo Gestor de Caso.</b-form-checkbox>
      <br>
      <div class="form-group">
          <h4>Professores a ser notificados</h4>
          <ul v-if="aux.length!=0">
              <li v-for="prof in aux">
                  {{prof.name}} - {{prof.subject}}
              </li>
          </ul>
          <span v-else>Nenhum professor selecionado.</span>
          <br>
          <br>
          <b-button
              type="button"
              class="btn btn-primary"
              data-toggle="modal"
              data-target="#exampleModalLong"
          >Notificar Professores</b-button>
      </div>

      <div class="form-group">
      <h4>Professor Orientador</h4>
      <input
        type="email"
        class="form-control"
        v-model="data.tutor"
        name="tutor"
        id="tutor"
        placeholder="professor.orientador@my.ipleiria.pt"
      />
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
      showCoordinatorNoResponseError:false,
        gestorCasoApoios:true,
        selectedSupports:[],
        selectedCategories:[],
        categories:[],
        data: {
        selected: null,
        options: null,
        tutor: "",
        date: ""
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
      axios
        .post("api/servicesApprovalRequest/" + this.user.id, this.services)
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
        if(this.user.coordinatorApproval != 1){
            this.showCoordinatorNoResponseError = true;
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
    }
  },
  created() {
    this.getServicesEvaluation();
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
