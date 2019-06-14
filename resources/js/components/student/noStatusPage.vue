<template>
  <div class="center">
    <b-container fluid>
      <b-row>
        <b-col>
          <h2>Bem vindo á plataforma 100%IN</h2>
          <a class="btn btn-secondary" v-on:click.prevent="subscribe()" href>Pedir estatuto</a>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
  methods: {
    subscribe() {
      this.$router.push("/subscription");
    }
  }
};
</script>
<style>
.center {
  text-align: center;
  margin-top: 20%;
}
</style>

