<template>
  <div>
    <div class="container">
      <h2>Lista de enees</h2>
      <b-table striped hover v-if="users!=null" :items="users" :fields="fields">
        <template slot="actions" slot-scope="row">
          <button
            type="button"
            class="btn btn-secondary"
            data-toggle="modal"
            data-target="#exampleModal"
          >Nova interação</button>
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
                    <select class="form-control" v-model="contact.service" name="service">
                      <option value disabled selected>Select your option</option>
                      <option>SAPE</option>
                      <option>SAS</option>
                      <option>Escola</option>
                      <option>Biblioteca</option>
                      <option>Direção</option>
                      <option>Professor-Tutor</option>
                      <option>Gestor-Caso</option>
                    </select>
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
                      v-model="contact.infromation"
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
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </b-table>
    </div>
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
        })
        .catch(error => {
          console.log(error);
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
