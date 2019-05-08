<template>
  <div>
    <div class="loader">
      <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="150"/>
    </div>
    <div v-if="services">
      <h2>Serviços usufruidos</h2>
      <b-table striped hover v-if="services" :items="services" :fields="fieldsServices"></b-table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      services: null,
      loading: true,
      fieldsServices: [
        {
          key: "name",
          label: "Nome do serviço",
          sortable: true
        },
        {
          key: "expirationDate",
          label: "Data de expiração do serviço"
        }
      ]
    };
  },
  methods: {
    getServices() {
      axios
        .get("api/getServices/" + this.user.id)
        .then(response => {
          this.services = response.data;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  created() {
    this.getServices();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>

<style>
</style>
