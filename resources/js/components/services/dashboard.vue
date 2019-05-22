<template>
  <div>
    <div>
      <enee-list :user="user" @downloadPDF="downloadPDF" @setContact="setContact"></enee-list>
      <meetings @setMeeting="setMeeting"></meetings>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: null
    };
  },
  methods: {
    setMeeting(meetingId, meeting) {
      console.log(meetingId, meeting),
        axios
          .post("api/finalizeMeeting/" + meetingId, meeting)
          .then(response => {
            this.getMeetings();
            this.$toasted.success("Reunião marcada.", {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            });
          })
          .catch(error => {
            console.log(error);
            this.$toasted.error(
              "Erro ao marcar reunião, por favor tente novamente.",
              {
                duration: 4000,
                position: "top-center",
                theme: "bubble"
              }
            );
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
    setContact(userId, contact) {
      axios
        .post("api/setContact/" + userId.id, contact)
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
    }
  }
};
</script>

<style>
</style>
