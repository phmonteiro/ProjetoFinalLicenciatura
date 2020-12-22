<template>
  <div v-if="support">
    <b-container>
      <h2>{{ $t('disciplina') }} {{support.nome}}</h2>
      <label for="hours">{{ $t('quantidade_horas') }}</label>

        <ValidationObserver v-slot="{handleSubmit}">
        <ValidationProvider rules="required|numeric" v-slot="{ errors }">
            <input type="number" class="form-control" id="hours" name="hours" v-model="support.hours">
            <code>{{ errors[0] }}</code><br>
        </ValidationProvider>
        <ValidationProvider name="teachers" rules="required" v-slot="{ errors }">
            <b-form-select
                v-model="selected_teacher"
                name="service"
                class="mb-3"
                aria-label="Escolher serviço"
            >
                <template slot="first">
                    <option :value="null" disabled>-- {{ $t('selecionar_serviço') }} --</option>
                </template>
                <option v-for="teacher in teachers">{{ teacher.name }}</option>
            </b-form-select>
            <code>{{ errors[0] }}</code>
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
  props: ["support","teachers"],
  data: function() {
    return {
        selected_teacher: null
    };
  },
  methods: {
    cancel() {
        this.support="";
        this.$emit("cancel-request");
    },
    request: function() {
      this.$emit("request-support-hours", this.selected_teacher);
    }
  }
};
</script>

<style>
</style>
