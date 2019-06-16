<template>
  <div class="container" v-if="user">
    <form @submit.prevent="validateBeforeSubmit">
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
                  v-validate="{ email:true }"
                  class="form-control"
                  type="email"
                  name="email"
                  id="email"
                  v-model="user.secondEmail"
                >
                <i v-show="errors.has('email')" class="fa fa-warning"></i>
                <span
                  v-show="errors.has('email')"
                  class="help is-danger"
                >{{ errors.first('email') }}</span>
              </div>
            </div>
          </div>
          <div class="dropdown-divider"></div>

          <h5>{{$t('identificação_responsável')}}</h5>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="responsibleName">{{$t('nome')}}</label>
                <input
                  v-validate="'required'"
                  type="text"
                  class="form-control"
                  id="responsibleName"
                  name="responsibleName"
                  v-model="user.responsibleName"
                >
                <i v-show="errors.has('responsibleName')" class="fa fa-warning"></i>
                <span
                  v-show="errors.has('responsibleName')"
                  class="help is-danger"
                >{{ errors.first('responsibleName') }}</span>
              </div>
              <div class="col">
                <label for="responsiblePhone">{{$t('contacto_telefónico')}}</label>
                <input
                  v-validate="{ required: true, digits:9 }"
                  class="form-control"
                  name="responsiblePhone"
                  id="responsiblePhone"
                  min="1"
                  max="9999999999"
                  v-model="user.responsiblePhone"
                >
                <i v-show="errors.has('responsiblePhone')" class="fa fa-warning"></i>
                <span
                  v-show="errors.has('responsiblePhone')"
                  class="help is-danger"
                >{{ errors.first('responsiblePhone') }}</span>
              </div>
              <div class="col">
                <label for="responsibleKin">{{$t('parentesco')}}</label>
                <input
                  v-validate="'required'"
                  type="text"
                  class="form-control"
                  id="responsibleKin"
                  name="responsibleKin"
                  v-model="user.responsibleKin"
                >
                <i v-show="errors.has('responsibleKin')" class="fa fa-warning"></i>
                <span
                  v-show="errors.has('responsibleKin')"
                  class="help is-danger"
                >{{ errors.first('responsibleKin') }}</span>
              </div>
              <div class="col">
                <label for="responsibleEmail">{{$t('email')}}</label>
                <input
                  v-validate="{ required: true, email:true }"
                  type="email"
                  class="form-control"
                  id="responsibleEmail"
                  name="responsibleEmail"
                  v-model="user.responsibleEmail"
                >
                <i v-show="errors.has('responsibleEmail')" class="fa fa-warning"></i>
                <span
                  v-show="errors.has('responsibleEmail')"
                  class="help is-danger"
                >{{ errors.first('responsibleEmail') }}</span>
              </div>
            </div>
          </div>

          <h5>{{$t('contacto_emergencia')}}</h5>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="emergencyName">{{$t('nome')}}</label>
                <input
                  v-validate="'required'"
                  type="text"
                  class="form-control"
                  id="emergencyName"
                  name="emergencyName"
                  v-model="user.emergencyName"
                >
                <i v-show="errors.has('responsibleEmail')" class="fa fa-warning"></i>
                <span
                  v-show="errors.has('responsibleEmail')"
                  class="help is-danger"
                >{{ errors.first('responsibleEmail') }}</span>
              </div>
              <div class="col">
                <label for="emergencyPhone">{{$t('contacto_telefónico')}}</label>
                <input
                  v-validate="{ required: true, digits:9 }"
                  class="form-control"
                  name="emergencyPhone"
                  id="emergencyPhone"
                  v-model="user.emergencyPhone"
                >
                <i v-show="errors.has('responsibleEmail')" class="fa fa-warning"></i>
                <span
                  v-show="errors.has('responsibleEmail')"
                  class="help is-danger"
                >{{ errors.first('responsibleEmail') }}</span>
              </div>
              <div class="col">
                <label for="emergencyKin">{{$t('parentesco')}}</label>
                <input
                  v-validate="'required'"
                  type="text"
                  class="form-control"
                  id="emergencyKin"
                  name="emergencyKin"
                  v-model="user.emergencyKin"
                >
                <i v-show="errors.has('responsibleEmail')" class="fa fa-warning"></i>
                <span
                  v-show="errors.has('responsibleEmail')"
                  class="help is-danger"
                >{{ errors.first('responsibleEmail') }}</span>
              </div>
              <div class="col">
                <label for="emergencYEmail">{{$t('email')}}</label>
                <input
                  v-validate="{ required: true, email:true }"
                  type="email"
                  class="form-control"
                  id="emergencyEmail"
                  name="emergencyEmail"
                  v-model="user.emergencyEmail"
                >
                <i v-show="errors.has('emergencyEmail')" class="fa fa-warning"></i>
                <span
                  v-show="errors.has('emergencyEmail')"
                  class="help is-danger"
                >{{ errors.first('emergencyEmail') }}</span>
              </div>
            </div>
          </div>

          <div class="dropdown-divider"></div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">{{ $t('confirmar') }}</button>
    </form>
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
    validateBeforeSubmit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.editProfile();
          return;
        }
      });
    },
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
