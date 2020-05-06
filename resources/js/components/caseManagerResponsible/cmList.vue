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
    <set-cm :user="currentUser" @save-user="saveUser()" @cancel-edit="cancelEdit()"></set-cm>
    <div class="container">
      <h2>Lista de Gestores de caso</h2>
      <b-table striped hover v-if="caseManagers!=null" :items="caseManagers" :fields="fields">
        <!-- <template slot="actions" slot-scope="row">
                    <button class="btn btn-success" v-on:click.prevent="editUser(row.item)"
                        v-if="row.item.number != user.number">Aprovar</button>
        </template>-->
      </b-table>
      <nav aria-label="Page navigation" v-if="caseManagers">
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
        <div v-if="caseManagers">
        <b-button v-if="!caseManagers[0].emailCaseManagerSubstituto" @click="showCbSubstitutes(caseManagers[0])">Adicionar Substituto</b-button>
        </div>
    </div>
      <br>
      <div v-if="showSubstitutes">
          <h4>Selecionar Gestor Caso substituto</h4>
          <br>
          <select name="cbSubstituto" id="cbSubstituto" v-model="emailSubstitute">
              <option value="default" disabled>Por favor selecione um</option>
              <option  v-for="cm in cmSubstitutes" :value="cm.caseManagerEmail">{{cm.caseManagerName}}</option>
          </select>
          <br>
          <br>
          <b-button @click="addSubstituto(caseManagers[0])">Guardar</b-button>
          <b-button @click="cancelAddSubstitute()">Cancelar</b-button>
      </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      cmSubstitutes:[],
      emailEnee:null,
      emailSubstitute:"default",
      pagination: {},
      loading: true,
      showSubstitutes:false,
      caseManagers: null,
      fields: [
        {
          key: "studentName",
          label: "Nome Estudande",
          sortable: true
        },
        {
          key: "studentEmail",
          label: "Email Estudante",
          sortable: true
        },
        {
          key: "caseManagerEmail",
          label: "Email Gestor Caso",
          sortable: true
        },
        {
          key: "caseManagerName",
          label: "Gestor Caso",
          sortable: true
        },
        {
            key:"emailCaseManagerSubstituto",
            label:"Gestor Caso Substituto",
            sortable:true
        }
      ],
      currentUser: null
    };
  },
  methods: {
      cancelAddSubstitute(){
            this.showSubstitutes=false;
      },
      showCbSubstitutes(caseManager){
          this.cmSubstitutes=[];
            this.emailEnee=caseManager.studentEmail;
            this.showSubstitutes = true;
            this.caseManagers.forEach(cm =>{
                if(caseManager.caseManagerName !== cm.caseManagerName){
                    this.cmSubstitutes.push(cm);
                }
            })
      },
      addSubstituto(){
          if(this.emailSubstitute!=null && this.emailEnee!=="default"){
              axios
                  .post("api/setCmSubstitute",{"emailCmSubstitute":this.emailSubstitute,
                                                "emailStudent": this.emailEnee}
                                                )
                  .then(response=>{
                      this.showSubstitutes= false;
                      this.$toasted.success("Substituição realizada com sucesso.", {
                          duration: 4000,
                          position: "top-center",
                          theme: "bubble"
                      });

                  }).catch(error=>{
                  console.log(error);
              })
          }
      },
    getcaseManagers(page_url) {
      let pg = this;
      page_url = page_url || "api/getCaseManagers?page=1";
      axios
        .get(page_url)
        .then(response => {
          this.loading = false;
          this.caseManagers = response.data.data;
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
    }
  },
  created() {
    this.getcaseManagers();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>
