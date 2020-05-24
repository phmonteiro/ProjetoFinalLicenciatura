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
    <div class="container" v-if="users">
      <h2>Lista de enees</h2>
      <b-table v-if="users" striped hover :items="users" :fields="fields">
        <template v-slot:cell(actions)="row">
          <button
            type="button"
            class="btn btn-secondary"
            data-toggle="modal"
            data-target="#exampleModal"
          >Nova interação</button>
          <router-link class="nav-link" :to="{name: 'contactDetails', params: {id: row.item.id }}">
            <button class="btn btn-secondary">Histórico</button>
          </router-link>
          <div
            class="modal fade"
            id="exampleModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Interação com {{row.item.name}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <b-form-select v-model="contact.service" class="mb-3">
                      <template slot="first">
                        <option :value="null" disabled>-- Selecione o serviço --</option>
                      </template>

                      <option value="SAPE">Serviços de Apoio ao Estudante</option>
                      <option value="CRIDE">Centro de Recursos para a Inclusão Digital</option>
                      <option value="SAS">Serviços de Ação Social</option>
                      <option value="UED">Unidade de Ensino á Distância</option>
                      <option value="DST">Direção de Serviços Técnicos</option>
                      <option value="SA">Serviços Académicos</option>
                      <option value="Escola">Escola</option>
                      <option value="Biblioteca">Biblioteca</option>
                      <option value="Direção">Direção</option>
                      <option value="Professor-Tutor">Professor-Tutor</option>
                      <option value="Gestor-Caso">Gestor-Caso</option>
                    </b-form-select>
                  </div>

                  <div class="form-group">
                    <label for="decision">Medida</label>
                    <textarea
                      class="form-control"
                      id="decision"
                      v-model="contact.decision"
                      name="decision"
                      rows="3"
                    ></textarea>
                  </div>

                  <div class="form-group">
                    <label for="information">Informação</label>
                    <textarea
                      class="form-control"
                      id="information"
                      v-model="contact.information"
                      name="information"
                      rows="3"
                    ></textarea>
                  </div>

                  <div class="form-group">
                    <label for="date">Próximo contacto</label>
                    <input
                      id="date"
                      type="date"
                      class="form-control"
                      v-model="contact.nextContact"
                      name="date"
                    />
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button
                      type="submit"
                      class="btn btn-primary"
                      data-dismiss="modal"
                      v-on:click.prevent="setContact(row.item)"
                    >Confirmar</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </b-table>
        <h4 v-else>Não existem ENEEs registados na plataforma</h4>
      <nav v-if="users" aria-label="Page navigation">
        <ul class="pagination">
          <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
            <a
              class="page-link"
              href="#"
              @click.prevent="getEnee(pagination.prev_page_url)"
            >Anterior</a>
          </li>

          <li class="page-item disabled">
            <a
              class="page-link text-dark"
              href="#"
            >Página {{ pagination.current_page }} de {{ pagination.last_page }}</a>
          </li>

          <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
            <a class="page-link" href="#" @click.prevent="getEnee(pagination.next_page_url)">Próximo</a>
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
      loading: true,
      pagination: {},
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
          key: "number",
          label: "Número",
          sortable: true
        },
        {
          key: "course",
          label: "Curso",
          sortable: true
        },
        {
          key: "school",
          label: "Escola",
          sortable: true
        },
        {
          key: "actions",
          label: "Ações Utilizador"
        }
      ],
      contact: {
        service: null,
        decision: null,
        information: null,
        nextContact: null
      }
    };
  },
  methods: {
    downloadPDF(user) {
      axios({
        url: "api/downloadHistory/" + user.id,
        method: "GET",
        responseType: "blob"
      })
        .then(response => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", user.number + ".zip");
          document.body.appendChild(link);
          link.click();
          this.$toasted.success(
            "Download do histórico do estudante feito com sucesso.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        })
        .catch(error => {
          this.$toasted.error(
            "Error ao fazer download do histórico do estudante. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    },
    setContact(userId) {
      axios
        .post("api/setContact/" + userId.id, this.contact)
        .then(response => {
          this.$toasted.success("Interação com estudante criada com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(
            "Erro ao criar interação com estudante, por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    },
    getEnee(page_url) {
      let pg = this;
      page_url = page_url || "api/getEnees?page=1";
      axios
        .get(page_url)
        .then(response => {
          this.loading = false;
          this.users = response.data.data;
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
    this.getEnee();
  }
};
</script>

<style>
</style>
