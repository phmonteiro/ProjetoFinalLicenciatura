<template>
  <div>
    <div class="loader">
      <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="150"/>
    </div>
    <div v-if="meetings">
      <enee-list :users="users" :user="user" :contact="contact"></enee-list>
      <meetings :meetings="meetings" @setMeeting="setMeeting"></meetings>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: true,
      meetings: null,
      user: null,
      contact: null,
      users: null
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
    getMeetings() {
      axios
        .get("api/getMeetings")
        .then(response => {
          this.meetings = response.data.data;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
        });
    },
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
    }
  },
  created() {
    this.getMeetings();
    this.getEnee();
  }
};
</script>

<style>
</style>
