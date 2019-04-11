<template>
  <div>
    <div class="container">
      <b-table striped hover v-if="contacts!=null" :items="contacts" :fields="fields"></b-table>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      contacts: null,
      fields: [
        {
          key: "service",
          label: "Serviço",
          sortable: true
        },
        {
          key: "decision",
          label: "Decisão"
        },
        {
          key: "date",
          label: "Data",
          sortable: true
        }
      ]
    };
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  },
  methods: {
    getContacts() {
      axios
        .get("api/getContacts/" + this.user.id)
        .then(response => {
          this.contacts = response.data.data;
          console.log(this.contacts);
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  created() {
    this.getContacts();
  }
};
</script>
