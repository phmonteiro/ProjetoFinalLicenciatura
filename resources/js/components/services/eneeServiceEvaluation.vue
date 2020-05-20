<template>
  <div class="container mt-3" v-if="user">
    <div class="form-group">
      <h4>Estudante:</h4>
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
        name="coordinatorAproval"
        id="inputcoordinatorAproval"
        disabled
      />
    </div>
    <div class="form-group">
      <label for="inputNee">Necessidades educativas especiais</label>
      <input
        v-for="aux in nee"
        type="text"
        class="form-control"
        v-model="aux.name"
        name="nee"
        id="nee"
        disabled
      />
    </div>

    <div class="form-group" v-if="user.functionalAnalysis!=null">
      <label for="inputAnalysis">Análise Funcional</label>
      <input
        type="text"
        class="form-control"
        v-model="user.functionalAnalysis"
        name="functionalAnalysis"
        id="functionalAnalysis"
        disabled
      />
    </div>

    <div class="form-group">
      <button
        class="btn btn-secondary"
        v-on:click.prevent="downloadPDF(user.id)"
        v-if="user.enee!='reproved'"
      >Download ficheiros médicos</button>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-success" name="ok" v-on:click.prevent="save()">Aprovar</button>
      <button class="btn btn-secondary" v-on:click.prevent="cancel()">Cancelar</button>
    </div>
  </div>
</template>
<script>
export default {
  props: ["user", "nee"],
  data: function() {
    return {};
  },
  methods: {
    downloadPDF(id) {
      axios({
        url: "api/medicalReport/download/" + id,
        method: "GET",
        responseType: "blob"
      })
        .then(response => {
          console.log(response);

          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "Medical  report" + this.user.name + ".zip");
          document.body.appendChild(link);
          link.click();
          console.log("success");

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
          console.log("error");
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
    cancel() {
      this.$emit("cancel-edit");
    },
    save: function() {
      this.$emit("approve", this.user.id);
    }
  },

  computed: {
    state() {
      return this.value.length === 1;
    }
  }
};
</script>
