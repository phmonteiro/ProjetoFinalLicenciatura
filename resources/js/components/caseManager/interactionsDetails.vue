<template>
    <div>
  <div class="container mt-3">
    <div class="form-group">
      <label for="inputName">Nome</label>
      <input
        type="text"
        class="form-control"
        v-model="user.name"
        name="name"
        id="inputName"
        disabled
      />
    </div>

    <div class="form-group">
      <label for="inputEmail">E-mail</label>
      <input
        type="text"
        class="form-control"
        v-model="user.email"
        name="email"
        id="inputEmail"
        disabled
      />
    </div>

      <br v-if="!isInteractions" />

    <div v-if="isInteractions">
    <b-container v-for="inter in interactions" :key="inter.id">
      <b-row>
        <b-col>
          <b>Data:</b>
          {{inter.date}}
        </b-col>
        <b-col>
          <b>Serviço:</b>
          {{inter.service}}
        </b-col>
      </b-row>
      <b-row>
        <b-col>
            <b>Hora:</b>
            {{inter.time}}
        </b-col>
        <b-col>
          <b>Informação:</b>
          {{inter.information}}
        </b-col>
      </b-row>

      <div class="form-group" v-if="inter.hasFiles">
        <button class="btn btn-info" v-on:click.prevent="downloadFiles(inter.id)">
          Descarregar ficheiros anexados
          <font-awesome-icon icon="arrow-circle-down" />
        </button>
      </div>
      <div class="dropdown-divider"></div>
    </b-container>
    </div>
        <h4 style="text-align: center" v-else>Ainda não houve alguma interação com o ENE.</h4>
        <div class="form-group text-center">
      <button class="btn btn-secondary" v-on:click.prevent="cancel()">Fechar</button>
    </div>
  </div>
    </div>
</template>
<script>
export default {
  props: ["user", "interactions"],
  data: function() {
    return {};
  },
  methods: {
    cancel() {
      this.$emit("cancel-edit");
    },
    downloadFiles(id) {
      axios({
        url: "api/contact/download/" + id,
        method: "GET",
        responseType: "blob"
      }).then(response => {
          console.log(response);
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "Ficheiros" + this.user.name + ".zip");
          document.body.appendChild(link);
          link.click();
          this.$toasted.success("Download efetuado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error, "error");
          this.$toasted.error(
            "Error ao fazer download. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    }
  },
    computed: {
      isInteractions: function() {
           if (this.interactions == null || this.interactions.length === 0) {
               return null;
           }
           return true;
      }
  }
};
</script>
