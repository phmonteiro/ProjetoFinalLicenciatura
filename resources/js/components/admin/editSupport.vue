<template>
  <div class="container mt-3" v-if="support">
    <div class="form-group">
      <div class="form-group">
        <label for="inputEmail">Alterar Descricao</label>
        <input
          type="text"
          class="form-control"
          v-model="support.text"
          name="description"
          id="description"
        />
      </div>

      <div class="form-group">
        <button
          type="submit"
          class="btn btn-secondary"
          name="ok"
          v-on:click.prevent="save()"
        >Guardar</button>
        <button class="btn btn-secondary" v-on:click.prevent="cancel()">Cancelar</button>
      </div>
    </div>
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
      this.$emit("cancel-edit");
    },
    save: function() {
      this.$emit("save-support");
    }
  }
};
</script>
