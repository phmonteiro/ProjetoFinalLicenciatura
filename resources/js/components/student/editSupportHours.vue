<template>
  <div v-if="support">
    <b-container>
      <h2>{{ $t('disciplina') }} {{support.nome}}</h2>
      <label for="hours">{{ $t('quantidade_horas') }}</label>

        <ValidationObserver v-slot="{handleSubmit}">
        <ValidationProvider rules="required|numeric" v-slot="{ errors }">
            <input type="text" class="form-control" id="hours" name="hours" v-model="support.hours">
            <code>{{ errors[0] }}</code><br>
        </ValidationProvider>

      <button
        type="submit"
        class="btn btn-secondary"
        data-dismiss="modal"
        v-on:click.prevent="handleSubmit(save)"
      >{{ $t('gravar') }}</button>
        </ValidationObserver>
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
        console.log(this.support.nome);
      this.$emit("save-support");
    }
  }
};
</script>

<style>
</style>
