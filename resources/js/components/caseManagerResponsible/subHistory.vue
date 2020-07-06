<template>
    <div>
        <br>
        <h3>Histórico de Substituições</h3>
        <div class="loader">
            <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50" />
        </div>
        <b-table striped hover v-if="substitutions!=null" :items="substitutions" :fields="fields">

        </b-table>
        <h4 v-else>Ainda não foi realizada qualquer substituição.</h4>

        <nav aria-label="Page navigation" v-if="substitutions">
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
</template>

<script>
  export default {
    name: 'subHistory',
     data(){
        return{
            substitutions:[],
            pagination: {},
            loading: true,
            fields:[
                {
                    key: "emailMainCM",
                    label: "Nome CM Principal",
                    sortable: true
                },
                {
                    key: "nameMainCM",
                    label: "Email CM Principal",
                    sortable: true
                },
                {
                    key: "emailSubstituteCM",
                    label: "Email CM Substituto",
                    sortable: true
                },
                {
                    key: "nameSubstituteCM",
                    label: "Nome CM Substituto",
                    sortable: true
                },
                {
                    key: "emailStudent",
                    label: "Email Estudante",
                    sortable: true
                },
                {
                    key: "nameStudent",
                    label: "Nome Estudante",
                    sortable: true
                },
                {
                    key: "type",
                    label: "Tipo",
                    sortable: true
                },
                {
                    key: "startDate",
                    label: "Inicio",
                    sortable: true
                },
                {
                    key: "endDate",
                    label: "Fim",
                    sortable: true
                }
            ]
        }
     },
      methods:{
          getSubstitutions(page_url) {
              let pg = this;
              page_url = page_url || "api/substitutionsHistory?page=1";
              axios
                  .get(page_url)
                  .then(response => {
                      this.loading = false;
                      this.substitutions = response.data.data;
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
      },
      created() {
        this.getSubstitutions();
      }
  };
</script>

<style scoped>

</style>
