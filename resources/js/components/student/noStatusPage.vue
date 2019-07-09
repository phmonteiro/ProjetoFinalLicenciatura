<template>
  <div class="center">
    <b-container fluid>
      <b-row>
        <b-col>
          <h2>{{$t('bem_vindo')}}</h2>
          <a class="btn btn-secondary" v-on:click.prevent="subscribe()" href>{{$t('pedir_estatuto')}}</a>
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

