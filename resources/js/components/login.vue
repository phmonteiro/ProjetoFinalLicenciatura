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
                />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="titulo justify-content-center">
                <h1>100%IN</h1>
                <h2>2019.2020</h2>
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
            @submit.prevent="login"
            v-if="!cartaoCidadao"
            autocomplete="on"
          >
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <span aria-label="Label Emal" class="numero">{{ $t('nome_utilizador') }}</span>

                    <ValidationProvider name="email" rules="required|email" v-slot="{ errors }">
                        <input
                            aria-label="Email"
                            id="email"
                            v-model="user.email"
                            type="text"
                            class="form-control"
                            name="email"
                            placeholder="email@my.ipleiria.pt"
                            required
                            autofocus
                        />
                        <span>{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <span aria-label="Label Password" class="senha">{{ $t('palavra_pass') }}</span>

                    <ValidationProvider name="password" rules="required" v-slot="{ errors }">
                        <input
                            aria-label="Password"
                            id="password"
                            v-model="user.password"
                            type="password"
                            class="form-control"
                            name="password"
                            required
                        />
                        <span>{{ errors[0] }}</span>
                    </ValidationProvider>
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
import moment from 'moment';
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
      label: this.$t('a_carregar')
    };
  },
  methods: {
    login() {
      axios
        .post("api/login", this.user)
        .then(response => {

            this.loading = false;

            if(response.data.user.inactive==1 && response.data.user.type == "Estudante"){
                axios
                    .get('api/getActiveEmail/'+this.user.email)
                    .then(activeAccountResponse=>{
                        this.$toasted.error("Esta conta encontra-se inactiva, pois detetámos que já existe no nosso sistema uma conta mais recente associada ao mesmo estudante. A sua conta activa está associada ao seguinte email: "+activeAccountResponse.data , {
                            duration: 60000,
                            position: "top-center",
                            theme: "outline"
                        });
                    })
                    .catch(error=>{
                        console.log(error);
                    });

                return;
            }

            this.$store.commit("setUser", response.data.user);
            this.$store.commit("setToken", response.headers.authorization);

            //Temporary function!!!!!
          if(response.status==201 && response.data.user.type == "Estudante"){
              this.$router.push("/tempEditProfile");
              return;
          }
            // END --- Temporary function!!!!!

            if(response.status==226 && response.data.user.type == "Estudante"){
              this.$router.push("/transferAccountPage");
              return;
          }

          if(response.data.user.type == "Estudante" && response.data.user.enee == "expired"){

              this.$router.push("/studentForm");

              return;
          }

            // if(response.data.user.enee == null && response.data.user.type == "Estudante"){
            //     axios
            //         .post('api/getWebServiceUserInfo',{"login": this.user.email,"password":this.user.password})
            //             .then(response=>{
            //             })
            //             .catch(error=>{
            //                 console.log(error);
            //             })
            // }
            if (response.data.user.type == "Professor") {
                this.$router.push("/teacher");
                return;
            }

            if (response.data.user.type == "CaseManagerResponsible") {
                this.$router.push("/caseManagerResponsible");
            return;
          }
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
          if (response.data.user.type == "Coordinator") {
            this.$router.push("/coordinator");
            return;
          }
          if (response.data.user.type == "CaseManager" && response.data.user.inactive != 1) {
            this.$router.push("/caseManager");
            return;
          }else{
              this.$toasted.error("Conta inactiva. Por favor contacte o Responsável de Gestores de Caso para mais informações.", {
                  duration: 10000,
                  position: "top-center",
                  theme: "bubble"
              });
              this.$store.commit("clearUserAndToken");
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

