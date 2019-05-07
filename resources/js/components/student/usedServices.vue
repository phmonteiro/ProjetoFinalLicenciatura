<template>
  <div>
    <h2>Serviços usufruidos</h2>
    <b-table striped hover v-if="services" :items="services" :fields="fieldsServices"></b-table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      services: null,
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
          console.log(this.services);
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
