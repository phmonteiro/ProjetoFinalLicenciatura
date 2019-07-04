<template>
  <div>
    <b-container>
      <b-row>
        <b-col></b-col>
        <b-col>
          <div class="loader">
            <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50"/>
          </div>
        </b-col>
        <b-col></b-col>
      </b-row>
    </b-container>
    <b-container>
      <b-row>
        <b-col class="top100">
          <div v-if="services">
            <h2>{{$t('apoios_usufruidos')}}</h2>
            <b-table striped hover :items="services" :fields="fieldsServices"></b-table>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pagination: {},
      services: null,
      loading: true,

      fieldsServices: [
        {
          key: "text",
          label: this.$t("nome_serviço"),
          sortable: true
        }
      ]

      
    };
  },
  methods: {
    getServices() {
      axios
        .get("api/getServices")
        .then(response => {
          this.loading = false;
          this.services = response.data;
          console.log(this.services);
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
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
