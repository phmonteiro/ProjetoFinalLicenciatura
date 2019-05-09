<template>
  <div>
    <b-container>
      <b-row>
        <b-col></b-col>
        <b-col>
          <div class="loader">
            <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50"/>
          </div>
        </b-col>
        <b-col></b-col>
      </b-row>
    </b-container>
    <b-container>
      <b-row>
        <b-col class="top100">
          <div v-if="contacts">
            <h2>Contactos</h2>
            <b-table striped hover :items="contacts" :fields="fields"></b-table>
            <nav aria-label="Page navigation">
              <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="getContacts(pagination.prev_page_url)"
                  >Anterior</a>
                </li>

                <li class="page-item disabled">
                  <a
                    class="page-link text-dark"
                    href="#"
                  >Página {{ pagination.current_page }} de {{ pagination.last_page }}</a>
                </li>

                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="getContacts(pagination.next_page_url)"
                  >Próximo</a>
                </li>
              </ul>
            </nav>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pagination: {},
      contacts: null,
      loading: true,
      fields: [
        {
          key: "service",
          label: "Serviço",
          sortable: true
        },
        {
          key: "date",
          label: "Data",
          sortable: true
        },
        {
          key: "nextContact",
          label: "Próximo Contacto",
          sortable: true
        }
      ]
    };
  },
  methods: {
    getContacts(page_url) {
      let pg = this;
      page_url = page_url || "api/getContacts/" + this.user.id + "?page=1";
      axios
        .get(page_url)
        .then(response => {
          this.loading = false;
          this.contacts = response.data.data;
          pg.makePagination(response.data.meta, response.data.links);
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
    this.getContacts();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>

<style>
</style>
