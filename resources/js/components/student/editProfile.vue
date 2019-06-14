<template>
  <div class="container" v-if="user">
    <h2>{{ $t('editar_perfil') }}</h2>
    <div class="row"></div>
    <div class="col-md-12 pt-4">
      <div class="form-group">
        <div class="container-full-width">
          <div class="row">
            <div class="col">
              <label for="student-name">{{ $t('nome') }}</label>
              <input
                type="text"
                class="form-control"
                id="student-name"
                placeholder="Nome Estudante"
                name="student-name"
                v-model="user.name"
                disabled
              >
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label for="email">{{ $t('email_principal') }}</label>
              <input
                class="form-control"
                type="email"
                name="email"
                id="email"
                v-model="user.email"
                disabled
              >
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label for="email">{{ $t('email_secundário') }}</label>
              <input
                class="form-control"
                type="email"
                name="email"
                id="email"
                v-model="user.secondEmail"
              >
            </div>
          </div>
        </div>
        <div class="dropdown-divider"></div>

        <h5>{{ $t('identificação_responsável') }}</h5>
        <div class="container-full-width">
          <div class="row">
            <div class="col">
              <label for="responsibleName">{{ $t('nome') }}</label>
              <input
                type="text"
                class="form-control"
                id="responsibleName"
                placeholder
                name="responsibleName"
                v-model="user.responsibleName"
              >
            </div>
            <div class="col">
              <label for="responsiblePhone">{{ $t('contacto_telefónico') }}</label>
              <input
                class="form-control"
                type="number"
                name="responsiblePhone"
                id="responsiblePhone"
                min="1"
                max="9999999999"
                v-model="user.responsiblePhone"
              >
            </div>
            <div class="col">
              <label for="responsibleKin">{{ $t('parentesco') }}</label>
              <input
                type="text"
                class="form-control"
                id="responsibleKin"
                placeholder
                name="responsibleKin"
                v-model="user.responsibleKin"
              >
            </div>
            <div class="col">
              <label for="responsibleEmail">{{ $t('email') }}</label>
              <input
                type="email"
                class="form-control"
                id="responsibleEmail"
                placeholder
                name="responsibleEmail"
                v-model="user.responsibleEmail"
              >
            </div>
          </div>

          <div class="dropdown-divider"></div>
        </div>
        <h5>{{ $t('contacto_emergencia') }}</h5>
        <div class="container-full-width">
          <div class="row">
            <div class="col">
              <label for="emergencyName">{{ $t('nome') }}</label>
              <input
                type="text"
                class="form-control"
                id="emergencyName"
                placeholder
                name="emergencyName"
                v-model="user.emergencyName"
              >
            </div>
            <div class="col">
              <label for="emergencyPhone">{{ $t('contacto_telefónico') }}</label>
              <input
                class="form-control"
                type="number"
                name="emergencyPhone"
                id="emergencyPhone"
                v-model="user.emergencyPhone"
              >
            </div>
            <div class="col">
              <label for="emergencyKin">{{ $t('parentesco') }}</label>
              <input
                type="text"
                class="form-control"
                id="emergencyKin"
                placeholder
                name="emergencyKin"
                v-model="user.emergencyKin"
              >
            </div>
            <div class="col">
              <label for="emergencYEmail">{{ $t('email') }}</label>
              <input
                type="email"
                class="form-control"
                id="emergencyEmail"
                placeholder
                name="emergencyEmail"
                v-model="user.emergencyEmail"
              >
            </div>
          </div>
        </div>
        <div class="dropdown-divider"></div>
      </div>
    </div>
    <button
      type="submit"
      class="btn btn-primary"
      v-on:click.prevent="editProfile()"
    >{{ $t('confirmar') }}</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: null,
      email: ""
    };
  },
  methods: {
    getAuth() {
      axios
        .get("api/getAuthUser")
        .then(response => {
          this.user = response.data;
          console.log(this.user);
        })
        .catch(error => {
          console.log(error);
        });
    },
    editProfile() {
      axios
        .post("api/editProfile", this.user)
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  created() {
    this.getAuth();
  }
};
</script>

<style>
</style>
