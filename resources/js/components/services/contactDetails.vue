<template>
  <div class="container mt-3" v-if="user">
    <h2>{{this.user.name}}</h2>
    <div>
      <h2 v-if="contact">Próximo contacto {{this.contact[0].nextContact}}</h2>
      <button
        type="button"
        class="btn btn-secondary"
        data-toggle="modal"
        data-target="#exampleModal"
      >Alterar data do contato</button>
    </div>
    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      v-if="contact"
    >
      <div class="modal-dialog" id="modal" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Interação com {{this.user.name}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="date">Próximo contacto {{this.contact[0].nextContact}}</label>
              <input
                id="nextContact"
                type="date"
                class="form-control"
                name="nextContact"
                v-model="nextContact"
              >
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button
                type="submit"
                data-dismiss="modal"
                v-on:click.prevent="changeNextContact()"
                class="btn btn-primary"
              >Confirmar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <b-table striped hover :items="contact" :fields="fields"></b-table>
    <router-link class="btn btn-secondary" to="/services">Voltar</router-link>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      userId: this.$route.params.id,
      user: null,
      contact: null,
      nextContact: null,
      fields: [
        {
          key: "service",
          label: "Serviço",
          sortable: true
        },
        {
          key: "decision",
          label: "Decisão",
          sortable: true
        },
        {
          key: "information",
          label: "Informação",
          sortable: true
        },
        {
          key: "nextContact",
          label: "Próximo contacto",
          sortable: true
        }
      ]
    };
  },
  created() {
    this.getUser();
    this.getContact();
  },
  methods: {
    getUser() {
      axios.get("api/getUser/" + this.userId).then(response => {
        this.user = response.data.data;
      });
    },
    getContact() {
      axios.get("api/getContact/" + this.userId).then(response => {
        this.contact = response.data;
      });
    },
    changeNextContact() {
      let contactDate = {
        nextContact: this.nextContact
      };
      axios
        .post("api/changeNextContact/" + this.contact[0].id, contactDate)
        .then(response => {
          this.getContact();
          this.$toasted.success(
            "Alteração da próxima interação feita com sucesso.",
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
            "Erro ao alterar a data da próxima interação. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    }
  }
};
</script>

<style>
</style>
