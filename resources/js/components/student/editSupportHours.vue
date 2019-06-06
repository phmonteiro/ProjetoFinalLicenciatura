<template>
  <div v-if="support">
    <b-container>
      <h2>Disciplina {{support.nome}}</h2>
      <label for="hours">Quantidade de horas:</label>
      <input type="text" class="form-control" id="hours" name="hours" v-model="support.hours">
      <button
        type="submit"
        class="btn btn-secondary"
        data-dismiss="modal"
        v-on:click.prevent="save"
      >Gravar</button>
      <button
        type="submit"
        class="btn btn-secondary"
        data-dismiss="modal"
        v-on:click.prevent="cancel"
      >Cancelar</button>
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
      this.$emit("save-support");
    }
  }
};
</script>

<style>
</style>
