<template>
  <div>
    <b-container>
      <b-row>
        <b-col></b-col>
        <b-col>
          <div class="loader">
            <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50" />
          </div>
        </b-col>
        <b-col></b-col>
      </b-row>
    </b-container>
    <div class="container">
      <h2>Lista de ENE</h2>
      <b-table striped hover v-if="enee!=null" :items="enee" :fields="fields">
        <template v-slot:cell(actions)="row">
          <b-row class="text-center">
              <b-col sm="12" class="m-1">
              <div v-if="row.detailsShowing">
                  <b-button  @click="row.toggleDetails">Fechar<font-awesome-icon icon="eye-slash"/>
                  </b-button>
              </div>
              <div v-if="!row.detailsShowing">
                  <b-button  @click="row.toggleDetails">Opções<font-awesome-icon icon="eye"/>
                  </b-button>
              </div>
            </b-col>\
          </b-row>
        </template>
        <template slot="row-details" slot-scope="row">
          <b-card>
            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Nascido em:</b>
                {{row.item.birthDate}}
              </b-col>
              <b-col>
                <b-row class="mb-2">
                  <b-col sm="5" class="text">
                    <b>Idade:</b>
                    {{calculateAge(row.item.birthDate)}}
                  </b-col>
                </b-row>
              </b-col>
              <b-col sm="3" class="text text-center">
                  <b-button size="sm" @click.prevent="showComponents('newInteraction',row.item)">
                     Nova Interação
                      <font-awesome-icon icon="handshake" />
                  </b-button>
              </b-col>
            </b-row>

            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Escola:</b>
                {{row.item.school}}
              </b-col>
              <b-col>
                <b-row class="mb-9">
                  <b-col sm="9" class="text">
                    <b>Entrou na escola em:</b>
                    {{row.item.enruledYear}}
                  </b-col>
                </b-row>
              </b-col>
                <b-col sm="3" class="text text-center">
                    <b-button size="sm" @click.prevent="showComponents('seeInteractions',row.item)">
                        Ver Interações
                        <font-awesome-icon icon="handshake" />
                    </b-button>
                </b-col>
            </b-row>

            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Curso:</b>
                {{row.item.course}}
              </b-col>
              <b-col>
                <b-row class="mb-9">
                  <b-col sm="9" class="text">
                    <b>Incapacidade:</b>
                    {{row.item.functionalAnalysis}}
                  </b-col>
                </b-row>
              </b-col>
                <b-col sm="3" class="m-1 text-center">
                    <b-button size="sm" @click.prevent="showComponents('managePlan',row.item)">
                        Plano de Inclusão
                        <font-awesome-icon icon="book" />
                    </b-button>
                </b-col>
            </b-row>

            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Sexo:</b>
                <span v-if="row.item.gender == 'masculino'">Masculino</span>
                <span v-if="row.item.gender == 'feminino'">Feminino</span>
                <span v-if="row.item.gender == 'outro'">Outro</span>
              </b-col>
              <b-col>
                <b-row class="mb-2">
                  <b-col sm="5" class="text">
                    <b>1ª matricula:</b>
                    {{row.item.enruledYear}}
                  </b-col>
                </b-row>
              </b-col>
                <b-col sm="3" class="m-1 text-center">
                    <b-button size="sm" @click.prevent="showComponents('increaseSupportHours',row.item)">
                        Gerir horas de apoio
                        <font-awesome-icon icon="book" />
                    </b-button>
                </b-col>
            </b-row>

            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Estatuto:</b>
                <span v-if="row.item.enee == 'approved'">Aprovado</span>
                <span v-if="row.item.enee == 'awaiting'">A espera</span>
                <span v-if="row.item.enee == null">Nao pedido</span>
              </b-col>
              <b-col>
                <b-row class="mb-9">
                  <b-col sm="9" class="text">
                    <b>Estatuto pedido a:</b>
                    {{row.item.dateEneeRequested}}
                  </b-col>
                </b-row>
              </b-col>
                <b-col sm="3" class="m-1 text-center">
                    <b-button   size="sm" @click.prevent="showComponents('supportHours',row.item)" >
                        Consultar Horas de Apoio
                        <font-awesome-icon icon="book" />
                    </b-button>
                </b-col>
            </b-row>

            <b-row class="mb-2">
              <b-col sm="4" class="text">
                <b>Estatuto aprovado a: {{row.item.dateEneeApproval}}</b>
              </b-col>
                <b-col>
                    <b-row class="mb-9">
                        <b-col sm="9" class="text">

                        </b-col>
                    </b-row>
                </b-col>
                <b-col sm="3" class="m-1 text-center">
                    <b-button size="sm" @click.prevent="showComponents('academicHistorial',row.item)">
                        Historial académico
                        <font-awesome-icon icon="book" />
                    </b-button>
                </b-col>
            </b-row>

              <b-row class="mb-2">
                  <b-col sm="4" class="text">
                  </b-col>
                  <b-col>
                      <b-row class="mb-9">
                          <b-col sm="9" class="text">

                          </b-col>
                      </b-row>
                  </b-col>
                  <b-col sm="3" class="m-1 text-center">
                      <b-button size="sm" @click.prevent="showComponents('showENEHistoric',row.item)">
                          Ver Histórico do ENE
                          <font-awesome-icon icon="book" />
                      </b-button>
                  </b-col>
              </b-row>

              <b-row class="mb-2">
                  <b-col sm="4" class="text">

                  </b-col>
                  <b-col sm="4" class="text text-center">
                      <b-button size="sm" @click="row.toggleDetails">Esconder</b-button>
                  </b-col>
              </b-row>
          </b-card>
        </template>
      </b-table>
      <nav aria-label="Page navigation" v-if="enee">
        <ul class="pagination">
          <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
            <a
              class="page-link"
              href="#"
              @click.prevent="getcaseManagers(pagination.prev_page_url)"
            >Anterior</a>
          </li>

          <li class="page-item disabled">
            <a class="page-link text-dark" href="#">
              Página {{ pagination.current_page }} de
              {{ pagination.last_page }}
            </a>
          </li>

          <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
            <a
              class="page-link"
              href="#"
              @click.prevent="getcaseManagers(pagination.next_page_url)"
            >Próximo</a>
          </li>
        </ul>
      </nav>
    </div>
      <br>
      <show-hours
                  :student="enee"
                  v-if="showStudentHours"
                  @cancel-show-hours="hideAllComponents()">
      </show-hours>
      <manage-plan
                   v-if="showPlan"
                   :user="currentUser"
                   :plan="currentPlan"
                   @cancel-edit2="hideAllComponents()">
      </manage-plan>
      <set-inter
                 v-if="showNewInteraction"
                 :user="currentUser"
                 @save-interaction="saveInteraction"
                 @cancel-edit="hideAllComponents()">
      </set-inter>
      <increase-hours
                      v-if="showIncreaseHours"
                      :student="currentUser"
                      @cancel-increase="hideAllComponents"
                      @save-increase="saveIncrease"
    ></increase-hours>
    <interactionsDetails
                         v-if="showDetails"
                         :user="currentUser"
                         :interactions="interactions"
                         @cancel-edit="hideAllComponents()"
    ></interactionsDetails>
      <historial-academico
                           v-if="showHistorialAcademico"
                           :student="currentUser"
                           @cancel-academic="hideAllComponents()"
      ></historial-academico>
      <ene-historic ref="historialacademico"
                    v-if="showENEHistoric"
                    :student="currentUser"
                    @cancel-historic="hideAllComponents()"
      >
      </ene-historic>
  </div>
</template>

<script>
    import moment from 'moment';

    export default {
  data() {
    return {
      yourTimeValue: {},
      pagination: {},
      loading: true,
      enee: null,
      fields: [
        {
          key: "name",
          label: "Nome Estudande",
          sortable: true
        },
        {
          key: "email",
          label: "Email Estudante",
          sortable: true
        },
        {
          key: "phoneNumber",
          label: "Contacto",
          sortable: true
        },
        {
          key: "school",
          label: "Escola",
          sortable: true
        },
        {
          key: "actions",
          label: "Ações",
        }
      ],
      student:null,
      currentUser: null,
      interactions: null,
      currentPlan: null,
      showIncreaseHours:false,
      showNewInteraction:false,
      showHistorialAcademico:false,
      showPlan:false,
      showDetails:false,
      newTotalHours:null,
      showStudentHours:false,
      showENEHistoric:false,
    };
  },
  methods: {
    calculateAge(birthday){
       return moment().diff(birthday, 'years');
    },

      hideAllComponents(){
          this.showIncreaseHours=false;
          this.showPlan=false;
          this.showDetails=false;
          this.showNewInteraction=false;
          this.showStudentHours = false;
          this.showHistorialAcademico=false;
          this.showENEHistoric=false;
      },
    showComponents(componentName,row){
        this.showIncreaseHours=false;
        this.showPlan=false;
        this.showDetails=false;
        this.showNewInteraction=false;
        this.showStudentHours = false;
        this.showHistorialAcademico=false;
        this.showENEHistoric=false;

        switch(componentName){
            case "supportHours":
                this.student=row;
                this.showStudentHours = true;
                break;
            case "increaseSupportHours":
                this.showIncreaseHours=true;
                this.currentUser = Object.assign({}, row);
                break;
            case "seeInteractions":
                this.currentUser = Object.assign({}, row);
                this.showDetails=true;
                this.getEneeInteractions(this.currentUser);
                break;
            case "newInteraction":
                this.currentUser = Object.assign({}, row);
                this.showNewInteraction=true;
                break;
            case "managePlan":
                this.currentUser = Object.assign({}, row);
                this.showPlan=true;
                this.getPlan(row.id);
                break;
            case "academicHistorial":
                this.currentUser = Object.assign({}, row);
                this.showHistorialAcademico=true;
                break;
            case "showENEHistoric":
                this.showENEHistoric=true;
                this.currentUser = Object.assign({}, row);
        }
    },

    saveIncrease(newTotalHours){
        axios.put("api/setSupportHours/"+this.currentUser.id,{"newTotalHours":newTotalHours})
            .then(response=>{
                this.showIncreaseHours=false;
                this.enee[0].supportHours=newTotalHours;
                this.currentUser=null;
                this.$toasted.success("Guardado com sucesso.", {
                    duration: 4000,
                    position: "top-center",
                    theme: "bubble"
                });
            })
            .catch(error=>{
                console.log(error);
            })
    },
    getCmEnee() {
      axios
        .get("api/getCmEnee/" + this.user.id)
        .then(response => {
          this.enee = response.data;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
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
    getEneeInteractions(user) {
      axios
        .get("api/getEneeInteractions/" + user.email)
        .then(response => {
            this.interactions = response.data.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getPlan(userId) {
      axios
        .get("api/getEneePlan/" + userId)
        .then(response => {
          this.currentPlan = response.data.data[0];
        })
        .catch(error => {
          console.log(error);
        });
    },
    cancelEdit: function() {
        this.showNewInteraction=false;
        this.currentUser = null;
    },
    cancelInteractions: function() {
        this.showDetails=false;
        this.currentUser = null;
    },
    saveInteraction(data, files) {
      //add - for missing values, for better reading
      if(data.software==null){
          data.software = '-';
      }

      if(data.place==null){
          data.place = '-';
      }

      const formData = new FormData();
      for (var i = 0; i < files.length; i++) {
        formData.append("file" + i, files[i]);
      }

      data.email = this.currentUser.email;

      var time = data.interactionTime.HH + ":" + data.interactionTime.mm;

      formData.append("email", data.email);
      formData.append("information", data.information);
      formData.append("interactionDate", data.interactionDate);
      formData.append("interactionTime", time);
      formData.append("contactMedium", data.contactMedium);
      formData.append("software", data.software);
      formData.append("place", data.place);
      formData.append("service", data.service);
      formData.append("numberFiles", files.length);


      axios
        .post("api/setInteraction/", formData)
        .then(response => {
          this.getCmEnee();
            this.showNewInteraction=false;
            this.currentUser = null;
          this.$toasted.success("Guardado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
          if (this.currentUser != null) {
            this.getEneeInteractions(this.currentUser);
          }
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error("Erro ao guardar. Por favor tente novamente.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        });
    },
      // cancelEdit2: function() {
      //     this.showPlan=false;
      //     this.currentUser = null;
      // },
      // cancelShowHours(){
      //     this.showStudentHours = false;
      //     },
      //   cancelHistorialAcademico(){
      //     this.showHistorialAcademico = false;
      //   },

      // showSupportHours(enee){
      //     this.showIncreaseHours=false;
      //     this.showPlan=false;
      //     this.showDetails=false;
      //     this.showNewInteraction=false;
      //     this.showStudentHours = true;
      //     this.student=enee;
      // },
      //   cancelIncrease(){
      //       this.showIncreaseHours=false;
      //       this.currentUser=null;
      //   },
      //   increaseSupportHours(row){
      //       this.currentUser = Object.assign({}, row);
      //       this.showIncreaseHours=true;
      //       this.showPlan=false;
      //       this.showDetails=false;
      //       this.showNewInteraction=false;
      //       this.showStudentHours = false;
      //   },
      //   seeInteractions(row) {
      //       this.currentUser = Object.assign({}, row);
      //       this.showIncreaseHours=false;
      //       this.showPlan=false;
      //       this.showDetails=true;
      //       this.showStudentHours = false;
      //       this.showNewInteraction=false;
      //       this.getEneeInteractions(this.currentUser);
      //   },
      //   newInteraction(row) {
      //       this.currentUser = Object.assign({}, row);
      //       this.showIncreaseHours=false;
      //       this.showPlan=false;
      //       this.showDetails=false;
      //       this.showNewInteraction=true;
      //       this.showStudentHours = false;
      //   },
      //   managePlan(row) {
      //       this.currentUser = Object.assign({}, row);
      //       this.showIncreaseHours=false;
      //       this.showPlan=true;
      //       this.showDetails=false;
      //       this.showNewInteraction=false;
      //       this.showStudentHours = false;
      //       this.getPlan(row.id);
      //   },
  },
  created() {
    this.getCmEnee();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>
