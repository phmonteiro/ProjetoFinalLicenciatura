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
<!--    <set-cm :user="currentUser" @save-user="saveUser()" @cancel-edit="cancelEdit()"></set-cm>-->
    <div class="container">
        <br>
      <h2>Lista de ENEs por Gestores de Caso</h2>
        <hr v-if="caseManagers==null || caseManagers.length===0">
      <b-table striped hover v-if="caseManagers!=null && caseManagers.length>0" :items="caseManagers" :fields="fields">

      </b-table>
        <h4 v-else>Não existem Gestores de Caso com ENEs atribuídos.</h4>

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
    </div>
      <br>
  </div>
</template>

<script>
export default {
  data() {
    return {
      cmSubstitutes:[],
      emailEnee:null,
      substitute:"default",
      pagination: {},
      loading: true,
      showSubstitutes:false,
      caseManagers: null,
      fields: [
        {
          key: "studentName",
          label: "Nome Estudante",
          sortable: true
        },
        {
          key: "studentEmail",
          label: "Email Estudante",
          sortable: true
        },
        {
          key: "caseManagerName",
          label: "Gestor Caso",
          sortable: true
        },
        {
              key: "caseManagerEmail",
              label: "Email Gestor Caso",
              sortable: true
        },
        {
            key:"emailMainCaseManager",
            label:"Gestor Caso Original",
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
