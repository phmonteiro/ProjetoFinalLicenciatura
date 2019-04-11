<template>
  <div class="main row">
    <div class="col-lg-6 d-flex align-items-center left-login">
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
    <div class="col-lg-6 d-flex align-items-center right-login">
      <div class="col-lg-3"></div>
      <div class="col-lg-6">
        <div class="row login-type">
          <div class="col">
            <div class="row">
              <button
                id="menu-nome"
                v-on:click.prevent="nomeUtilizador()"
                class="link-left"
              >Nome de utilizador</button>
            </div>
          </div>
          <div class="col">
            <div class="row">
              <button
                id="menu-cc"
                v-on:click.prevent="cartaoCid()"
                class="link-right"
              >Cartão de cidadão</button>
            </div>
          </div>
        </div>
        <form class="justify-content-center" v-if="!cartaoCidadao">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <span class="numero">Nome de utilizador</span>
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
                <span class="senha">Password</span>
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
          <button class="btn btn-secondary entrar" v-on:click.prevent="login()" type="submit">Login</button>
        </form>
      </div>
      <div class="col-lg-3"></div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {
        email: "",
        password: ""
      },
      cartaoCidadao: false
    };
  },
  methods: {
    login() {
      axios
        .post("api/login", this.user)
        .then(response => {
          console.log(response);
          this.$store.commit("setUser", response.data);
          switch (response.data.type) {
            case "Estudante":
              this.$router.push("/student");
              break;
          }
        })
        .catch(error => {
          console.log(error);
          this.$store.commit("clearUserAndToken");
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
