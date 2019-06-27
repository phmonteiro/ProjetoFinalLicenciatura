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
      >
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
      >
    </div>
    <div class="form-group">
      <label for="inputNee">Necessidades educativas especiais</label>
      <input
        v-for="aux in nee"
        type="text"
        class="form-control"
        v-model="aux.name"
        name="nee"
        id="nee"
        disabled
      >
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
      >
    </div>

    <div class="form-group">
      <button
        class="btn btn-secondary"
        v-on:click.prevent="downloadPDF(user.id)"
        v-if="user.enee!='reproved'"
      >Download ficheiros médicos</button>
    </div>
    <div>
      <div class="form-group">
        <h4 for="coordinatorApproval">Opinião coordenador de curso:</h4>
        <p v-if="user.coordinatorApproval==1">
          <b>Aprovado</b>
        </p>
        <p v-if="user.coordinatorApproval==0">
          <b>Rejeitado</b>
        </p>
        <p v-if="user.coordinatorApproval==null">
          <b>Ainda sem parecer</b>
        </p>
      </div>

      <div class="form-group">
        <h4 for="servicesApproval">Opinião servicos:</h4>
        <p v-if="user.servicesApproval=='approved'">
          <b>Aprovado</b>
        </p>
        <p v-if="user.servicesApproval=='denied'">
          <b>Rejeitado</b>
        </p>
        <p v-if="user.servicesApproval==null || user.servicesApproval=='requested' ">
          <b>Ainda sem parecer</b>
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

      <b-form-group id="input-group-3" label="Duracao da NEE:" label-for="input-3">
        <b-form-select id="input-3" v-model="form.duration" :options="durationOpts" required></b-form-select>
        <div v-if="form.duration=='Temporária'">
          <label for="date">Data de fim de estatuto ENEE</label>
          <input class="form-control" type="date" value id="date" name="date" v-model="data.date">
        </div>
      </b-form-group>
    </div>

    <b-form-group label="Apoios ao estudante">
      <b-form-checkbox-group v-model="studentSupports" :options="options" switches></b-form-checkbox-group>
    </b-form-group>

    <div class="form-group">
      <label for="inputTutor">Professor Tutor</label>
      <input
        type="email"
        class="form-control"
        v-model="data.tutor"
        name="tutor"
        id="tutor"
        placeholder="professor.tutor@my.ipleiria.pt"
      >
    </div>

    <div class="form-group">
      <button
        type="button"
        class="btn btn-info"
        data-toggle="modal"
        data-target="#myModal"
      >Continuar</button>
      <button class="btn btn-secondary" v-on:click.prevent="cancel()">Cancelar</button>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Definir professores notificados do estatuto</h4>
          </div>
          <div class="modal-body">
            <div>
              <ClipLoader sizeUnit="px" class="loading" v-if="!teachers" :size="15"/>
            </div>
            <div v-if="teachers">
              <div v-for="(teacher, index) in teachers" :key="index">
                <div class="switch-sex">
                  <input type="radio" :value="teachers[index].email" v-model="aux[index]">
                  <label>
                    Professor {{teachers[index].name}}
                    <br>
                    Disciplina: {{teachers[index].subject}}
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              v-if="teachers"
              v-b-modal.modal-1
              type="submit"
              class="btn btn-success"
              name="ok"
              v-on:click.prevent="save()"
            >Aprovar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["user", "studentSupports", "nee", "teachers"],
  data: function() {
    return {
      aux: [],
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
          link.setAttribute("download", "Medical  report" + user.name + ".zip");
          document.body.appendChild(link);
          link.click();
          console.log("success");
        })
        .catch(error => {
          console.log("error");
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
      let data = {
        teachers: this.aux,
        email: this.user.email,
        supports: this.studentSupports,
        tutor: this.data.tutor,
        date: this.data.date,
        duration: this.form.duration
      };
      this.$emit("save-user", data);
    },
    getAllSupports() {
      axios
        .get("api/getSupports")
        .then(response => {
          this.options = response.data;
          console.log("supports aqui" + this.options);
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  created() {
    this.getAllSupports();
  },

  computed: {
    state() {
      return this.value.length === 1;
    }
  }
};
</script>
