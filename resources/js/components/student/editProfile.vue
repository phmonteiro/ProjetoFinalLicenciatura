<template>
  <div class="container" v-if="user">
      <ValidationObserver v-slot="{ handleSubmit }">
    <form @submit.prevent="handleSubmit(editProfile)">
      <h2>{{ $t('editar_perfil') }}</h2>
      <div class="row"></div>
      <div class="col-md-12 pt-4">
        <div class="form-group">
          <div class="container-full-width">

            <div class="row">
              <div class="col">
                <label for="student-name">{{ $t('nome') }}</label>
                  <ValidationProvider name="nome" id="valNome" rules="required|alpha_spaces" v-slot="{ errors }">
                      <input
                          type="text"
                          class="form-control"
                          id="student-name"
                          placeholder="Nome Estudante"
                          name="student-name"
                          v-model="user.name"
                          disabled
                      />
                      <code>{{ errors[0] }}</code>
                  </ValidationProvider>
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
                />
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="secondEmail">{{ $t('email_secundário') }}</label>

                  <ValidationProvider name="email" rules="email" v-slot="{ errors }">
                      <input
                          aria-label="Segundo Email"
                          class="form-control"
                          type="text"
                          name="secondEmail"
                          id="secondEmail"
                          v-model="user.secondEmail"
                      />
                      <code>{{ errors[0] }}</code>
                  </ValidationProvider>
              </div>
            </div>
          </div>
          <div class="dropdown-divider"></div>

          <h3>{{$t('identificação_responsável')}}</h3>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="responsibleName">{{$t('nome')}}</label>
                  <ValidationProvider name="responsibleName" rules="required|alpha" v-slot="{ errors }">
                      <input
                          type="text"
                          class="form-control"
                          id="responsibleName"
                          name="responsibleName"
                          v-model="user.responsibleName"
                      />
                      <code>{{ errors[0] }}</code>
                  </ValidationProvider>
              </div>
              <div class="col">
                <label for="responsiblePhone">{{$t('contacto_telefónico')}}</label>
                  <ValidationProvider name="responsiblePhone" rules="required|digits:9" v-slot="{ errors }">
                      <input
                          class="form-control"
                          name="responsiblePhone"
                          id="responsiblePhone"
                          min="1"
                          max="9999999999"
                          v-model="user.responsiblePhone"
                      />
                      <code>{{ errors[0] }}</code>
                  </ValidationProvider>
              </div>
              <div class="col">
                <label for="responsibleKin">{{$t('parentesco')}}</label>
                  <ValidationProvider name="responsibleKin" rules="required" v-slot="{ errors }">
                      <input
                          type="text"
                          class="form-control"
                          id="responsibleKin"
                          name="responsibleKin"
                          v-model="user.responsibleKin"
                      />
                      <code>{{ errors[0] }}</code>
                  </ValidationProvider>
              </div>
              <div class="col">
                <label for="responsibleEmail">{{$t('email')}}</label>
                  <ValidationProvider name="responsibleEmail" rules="required|email" v-slot="{ errors }">
                      <input
                          type="email"
                          class="form-control"
                          id="responsibleEmail"
                          name="responsibleEmail"
                          v-model="user.responsibleEmail"
                      />
                      <code>{{ errors[0] }}</code>
                  </ValidationProvider>
              </div>
            </div>
          </div>
          <h3>{{$t('contacto_emergencia')}}</h3>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="emergencyName">{{$t('nome')}}</label>
                  <ValidationProvider name="emergencyName" rules="required" v-slot="{ errors }">
                      <input
                          type="text"
                          class="form-control"
                          id="emergencyName"
                          name="emergencyName"
                          v-model="user.emergencyName"
                      />
                      <code>{{ errors[0] }}</code>
                  </ValidationProvider>
              </div>
              <div class="col">
                <label for="emergencyPhone">{{$t('contacto_telefónico')}}</label>
                  <ValidationProvider name="emergencyPhone" rules="required|digits:9" v-slot="{ errors }">
                      <input
                          class="form-control"
                          name="emergencyPhone"
                          id="emergencyPhone"
                          v-model="user.emergencyPhone"
                      />
                      <code>{{ errors[0] }}</code>
                  </ValidationProvider>
              </div>
              <div class="col">
                <label for="emergencyKin">{{$t('parentesco')}}</label>
                  <ValidationProvider name="emergencyKin" rules="required" v-slot="{ errors }">
                      <input
                          type="text"
                          class="form-control"
                          id="emergencyKin"
                          name="emergencyKin"
                          v-model="user.emergencyKin"
                      />
                      <code>{{ errors[0] }}</code>
                  </ValidationProvider>
              </div>
              <div class="col">
                <label for="emergencyEmail">{{$t('email')}}</label>
                  <ValidationProvider name="emergencyEmail" rules="required|email" v-slot="{ errors }">
                      <input
                          type="email"
                          class="form-control"
                          id="emergencyEmail"
                          name="emergencyEmail"
                          v-model="user.emergencyEmail"
                      />
                      <code>{{ errors[0] }}</code>
                  </ValidationProvider>
              </div>
            </div>
          </div>

          <div class="dropdown-divider"></div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">{{ $t('confirmar') }}</button>
    </form>
      </ValidationObserver>
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
        .put("api/editProfile", this.user)
        .then(response => {
          this.$toasted.success("Perfil editado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
          console.log(response);
        })
        .catch(error => {
          this.$toasted.error("Erro ao editar perfil de utilizador.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
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
