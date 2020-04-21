<template>
  <div v-if="support">
    <b-container>
      <h2>{{ $t('disciplina') }} {{support.nome}}</h2>
      <label for="hours">{{ $t('quantidade_horas') }}</label>
      <input type="text" class="form-control" id="hours" name="hours" v-model="support.hours">
      <button
        type="submit"
        class="btn btn-secondary"
        data-dismiss="modal"
        v-on:click.prevent="save"
      >{{ $t('gravar') }}</button>
      <button
        type="submit"
        class="btn btn-secondary"
        data-dismiss="modal"
        v-on:click.prevent="cancel"
      >{{ $t('cancelar') }}</button>
    </b-container>
  </div>
</template>

<script>
export default {
  props: ["support"],
  data: function() {
    return {};
  },
  methods: {
    cancel() {
      this.$emit("cancel-support");
    },
    save: function() {
        console.log(this.support.nome)
      this.$emit("save-support");
    }
  }
};
</script>

<style>
</style>
