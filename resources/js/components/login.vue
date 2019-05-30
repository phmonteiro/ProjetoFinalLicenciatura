<template>
  <div>
    <loading :show="loading" :label="label"></loading>
    <div class="main row m-0">
      <div class="col-lg-6 p-0 d-flex align-items-center left-login">
        <div class="col">
          <div class="row">
            <div class="col">
              <div class="d-flex justify-content-center">
                <img class="logo" src="/imagens/logo-ipl.png">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="titulo justify-content-center">
                <p>Plataforma de</p>
                <p>acompanhamento de</p>
                <p>ENEE</p>
                <p>2018.2019</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 p-0 d-flex align-items-center right-login">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
          <div class="row login-type">
            <div class="col">
              <div class="row">
                <button
                  id="menu-nome"
                  v-on:click.prevent="nomeUtilizador()"
                  class="link-left"
                >{{ $t('nome_utilizador') }}</button>
              </div>
            </div>
            <div class="col">
              <div class="row">
                <button
                  id="menu-cc"
                  v-on:click.prevent="cartaoCid()"
                  class="link-right"
                >{{ $t('cartao_cidadao') }}</button>
              </div>
            </div>
          </div>
          <form class="justify-content-center" v-if="!cartaoCidadao">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <span class="numero">{{ $t('nome_utilizador') }}</span>
                  <input
                    id="email"
                    v-model="user.email"
                    type="text"
                    class="form-control"
                    name="email"
                    required
                    autofocus
                  >
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <span class="senha">{{ $t('palavra_pass') }}</span>
                  <input
                    id="password"
                    v-model="user.password"
                    type="password"
                    class="form-control"
                    name="password"
                    required
                  >
                </div>
              </div>
            </div>
            <button
              class="btn btn-secondary entrar"
              v-on:click.prevent="login()"
              type="submit"
            >Login</button>
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
    login() {
      this.loading = true;
      axios
        .post("api/login", this.user)
        .then(response => {
          console.log(response.headers.authorization);

          this.$store.commit("setUser", response.data.user);
          this.$store.commit("setToken", response.headers.authorization);

          this.loading = false;
          //Vem do role da base de dados da universidade
          if (
            response.data.user.type == "Estudante" &&
            response.data.user.enee == null
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
          if (response.data.user.type == "Services") {
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
    },
    cartaoCid() {
      this.cartaoCidadao = true;
    },
    nomeUtilizador() {
      this.cartaoCidadao = false;
    }
  }
};
</script>

