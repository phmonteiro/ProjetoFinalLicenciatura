<template>
  <div class="container" v-if="this.users">
    <h2>Lista de enees</h2>
    <b-table striped hover v-if="users!=null" :items="users" :fields="fields">
      <template slot="actions" slot-scope="row">
        <button
          type="submit"
          class="btn btn-secondary"
          data-dismiss="modal"
          v-on:click.prevent="downloadPDF(row.item)"
        >Download Histórico</button>
        <button
          type="button"
          class="btn btn-secondary"
          data-toggle="modal"
          data-target="#exampleModal"
        >Nova interação</button>
        <router-link class="nav-link" :to="{name: 'contactDetails', params: {id: row.item.id }}">
          <button class="btn btn-secondary">Contactos</button>
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

                    <option value="SAPE">SAPE</option>
                    <option value="SAS">SAS</option>
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
                  >
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                  <button
                    type="submit"
                    class="btn btn-primary"
                    data-dismiss="modal"
                    v-on:click.prevent="setContact(row.item.id)"
                  >Confirmar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </b-table>
  </div>
</template>

<script>
export default {
  data() {
    return {
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
    getEnee() {
      axios
        .get("api/getEnee")
        .then(response => {
          this.users = response.data.data;
          console.log(this.users);
        })
        .catch(error => {
          console.log(error);
        });
    },
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
          link.setAttribute("download", user.number + ".pdf");
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
      console.log(userId),
        axios
          .post("api/setContact/" + userId, this.contact)
          .then(response => {
            this.$toasted.success(
              "Interação com estudante criada com sucesso.",
              {
                duration: 4000,
                position: "top-center",
                theme: "bubble"
              }
            );
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
    }
  },
  created() {
    this.getEnee();
  }
};
</script>

<style>
</style>
