<template>
  <div>
    <enee-list :user="user" :contact="contact"></enee-list>
    <meetings :meetings="meetings" @setMeeting="setMeeting"></meetings>
  </div>
</template>

<script>
export default {
  data() {
    return {
      meetings: null,
      user: null,
      contact: null
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
              duration: 3000,
              position: "top-center",
              theme: "bubble"
            });
          })
          .catch(error => {
            console.log(error);
            this.$toasted.error(
              "Erro ao marcar reunião, por favor tente novamente.",
              {
                duration: 3000,
                position: "top-center",
                theme: "bubble"
              }
            );
          });
    },
    getMeetings() {
      axios
        .get("api/getMeetings")
        .then(response => {
          this.meetings = response.data.data;
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  created() {
    this.getMeetings();
  }
};
</script>

<style>
</style>
