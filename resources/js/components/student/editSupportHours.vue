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
        v-on:click.prevent="handleSubmit(request)"
      >{{ $t('solicitar_horas_apoio') }}
      </button>
        </ValidationObserver>
      <button
        type="submit"
        class="btn btn-secondary"
        data-dismiss="modal"
        v-on:click.prevent="cancel"
      >{{ $t('cancelar') }}
      </button>
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
        this.support="";
        this.$emit("cancel-request");
    },
    request: function() {
      this.$emit("request-support-hours");
    }
  }
};
</script>

<style>
</style>
