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
    <set-cm
      :user="currentUser"
      :studentCMs="studentCMs"
      @save-user="saveUser()"
      @refreshCMs="getStudentCMs(currentUser)"
      @cancel-edit="cancelEdit()"
    ></set-cm>
    <div class="container">
      <h2>Lista de estudantes</h2>
      <b-table striped hover v-if="users!=null" :items="users" :fields="fields">
        <template slot="actions" slot-scope="row">
          <button class="btn btn-secondary" v-on:click.prevent="editUser(row.item)">Gerir</button>
        </template>
      </b-table>
        <button class="btn btn-secondary" v-on:click.prevent="editUser(users[0])">Gerir</button>
        <nav aria-label="Page navigation" v-if="users">
        <ul class="pagination">
          <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
            <a
              class="page-link"
              href="#"
              @click.prevent="getStudent(pagination.prev_page_url)"
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
              @click.prevent="getStudent(pagination.next_page_url)"
            >Próximo</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pagination: {},
      loading: true,
      users: null,
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
      currentUser: null,
      studentCMs: null
    };
  },
  methods: {
    getStudent(page_url) {
      let pg = this;
      page_url = page_url || "api/getStudents?page=1";
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
    editUser(row) {
      this.currentUser = Object.assign({}, row);
      this.getStudentCMs(this.currentUser);
    },
    getStudentCMs(user) {
      axios
        .get("api/getStudentCMs/" + user.email)
        .then(response => {
          this.studentCMs = Object.assign({}, response.data.data);
          console.log(response.data.data, "estes dados");
        })
        .catch(error => {
          console.log("Erro", error);
        });
    },
    cancelEdit: function() {
      this.currentUser = null;
    },
    saveUser() {
      this.currentUser = null;
    }
  },
  created() {
    this.getStudent();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>
