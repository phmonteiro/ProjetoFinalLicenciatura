<template>
  <div>
    <loading :show="loading" :label="label"></loading>
    <div class="main row m-0">
      <div class="col-lg-6 p-0 d-flex align-items-center left-login">
        <div class="col">
          <div class="row">
            <div class="col">
              <div class="d-flex justify-content-center">
                <img
                  class="logo"
                  alt="Logotipo do Instituto Politécnico de Leiria"
                  src="/imagens/logo-ipl.png"
                  tabindex="-1"
                >
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="titulo justify-content-center">
                <p>Plataforma</p>
                <p>100%IN</p>
                <p>2018.2019</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 p-0 d-flex align-items-center right-login">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
          <form
            class="justify-content-center"
            @submit.prevent="validateBeforeSubmit"
            v-if="!cartaoCidadao"
            autocomplete="on"
          >
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <span class="numero">{{ $t('nome_utilizador') }}</span>
                  <input
                    v-validate="{ required: true, email:true }"
                    id="email"
                    v-model="user.email"
                    type="text"
                    class="form-control"
                    name="email"
                    placeholder="email@my.ipleiria.pt"
                    required
                    autofocus
                  >
                  <i v-show="errors.has('email')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('email')"
                    class="help is-danger"
                  >{{ errors.first('email') }}</span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <span class="senha">{{ $t('palavra_pass') }}</span>
                  <input
                    v-validate="'required'"
                    id="password"
                    v-model="user.password"
                    type="password"
                    class="form-control"
                    name="password"
                    required
                  >
                  <i v-show="errors.has('password')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('password')"
                    class="help is-danger"
                  >{{ errors.first('password') }}</span>
                </div>
              </div>
            </div>
            <button class="btn btn-secondary entrar" type="submit">Login</button>
          </form>
        </div>
        <div class="col-lg-3"></div>
      </div>
    </div>
  </div>
</template>

<script>
import loading from "vue-full-loading";
export default {
  components: {
    loading
  },
  data() {
    return {
      user: {
        email: "",
        password: ""
      },
      cartaoCidadao: false,
      language: "",
      loading: false,
      label: "Loading"
    };
  },
  methods: {
    validateBeforeSubmit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.loading = true;
          this.login();
          return;
        }
      });
    },
    login() {
      axios
        .post("api/login", this.user)
        .then(response => {
          this.$store.commit("setUser", response.data.user);
          this.$store.commit("setToken", response.headers.authorization);
          this.loading = false;
          //Vem do role da base de dados da universidade
          if (
            response.data.user.type == "Estudante" &&
            (response.data.user.enee == null ||
              response.data.user.enee == "rejected")
          ) {
            this.$router.push("/studentForm");
            return;
          }
          if (
            response.data.user.type == "Estudante" &&
            (response.data.user.enee != "waiting" &&
              response.data.user.enee != "null")
          ) {
            this.$router.push("/student");
            return;
          }
          if (response.data.user.type == "Administrador") {
            this.$router.push("/admin");
            return;
          }
          if (
            response.data.user.type == "Services" ||
            response.data.user.type == "SAPE" ||
            response.data.user.type == "SAS" ||
            response.data.user.type == "CRID" ||
            response.data.user.type == "UED" ||
            response.data.user.type == "DST" ||
            response.data.user.type == "SA"
          ) {
            this.$router.push("/services");
            return;
          }
          if (response.data.user.type == "Director") {
            this.$router.push("/director");
            return;
          }
          if (response.data.type == "Coordinator") {
            this.$router.push("/coordinator");
            return;
          }
          if (response.data.type == "CaseManagerResponsible") {
            this.$router.push("/caseManagerResponsible");
            return;
          }
          if (response.data.type == "CaseManager") {
            this.$router.push("/caseManager");
            return;
          }
        })
        .catch(error => {
          this.loading = false;
          console.log(error.response);
          this.$store.commit("clearUserAndToken");
          this.$toasted.error(error.response.data.message, {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        });
    }
  }
};
</script>

