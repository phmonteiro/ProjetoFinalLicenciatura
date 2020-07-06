<template>
  <div class="container pt-2 pb-3" v-if="user != null">
    <div v-if="user.enee!='awaiting'">
        <ValidationObserver v-slot="{ handleSubmit }">
      <form @submit.prevent="handleSubmit(sendForm)">
        <h2>{{$t('formulario')}}</h2>
        <div class="row">
          <div class="col-md-12 pt-4">
            <h3>{{$t('identificação_estudante')}}</h3>
            <div class="form-group">
              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="student-number">{{$t('numero_estudante')}}</label>
                    <input
                      class="form-control"
                      type="number"
                      name="student-number"
                      id="student-number"
                      min="1"
                      max="9999999"
                      v-model="student.number"
                      disabled
                    />
                  </div>
                  <div class="col">
                    <label for="student-name">{{$t('nome')}}</label>
                    <input
                      type="text"
                      class="form-control"
                      id="student-name"
                      name="student-name"
                      v-model="student.name"
                      disabled
                    />
                  </div>

                  <div class="col">
                    <label for="phone-number">{{$t('contacto_telefónico')}}</label>
                      <ValidationProvider name="phone" rules="required|digits:9" v-slot="{ errors }">
                          <input
                              class="form-control"
                              name="Phone Number"
                              id="phone-number"
                              v-model="student.phoneNumber"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>

                  <div class="col">
                    <label for="birth-date">{{$t('data_nascimento')}}</label>
                      <ValidationProvider name="birthDate" rules="required" v-slot="{ errors }">
                          <input
                              :disabled="!isNotApproved"
                              class="form-control"
                              value
                              id="birth-date"
                              name="Birth Date"
                              placeholder="yyyy-mm-dd"
                              v-model="student.birthDate"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>
                </div>
              </div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col-8">
                    <label for="email">{{$t('email')}}</label>
                    <input
                      class="form-control"
                      type="email"
                      name="Email"
                      placeholder="email@mail.com"
                      id="email"
                      v-model="student.email"
                      disabled
                    />
                  </div>

                  <div class="col-4">
                    <label for="gender">{{$t('género')}}</label>
                      <ValidationProvider name="gender" rules="required" v-slot="{ errors }">
                          <select
                              :disabled="!isNotApproved"
                              class="custom-select"
                              name="Gender"
                              id="gender"
                              single
                              v-model="student.gender"
                          >
                              <option selected value="masculino">{{$t('masculino')}}</option>
                              <option value="feminino">{{$t('feminino')}}</option>
                              <option value="outro">{{$t('outro')}}</option>
                          </select>
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>
                </div>
              </div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col-6">
                    <label for="residence">{{$t('residencia')}}</label>
                      <ValidationProvider name="residence" rules="required" v-slot="{ errors }">
                          <input
                              class="form-control"
                              id="residence"
                              name="residence"
                              v-model="student.residence"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>

                  <div class="col">
                    <label for="zipCode">{{$t('codigo_postal')}}</label>
                      <ValidationProvider name="zipCode" rules="required" v-slot="{ errors }">
                          <input
                              class="form-control"
                              pattern="\d\d\d\d[-]\d\d\d"
                              id="zipCode"
                              name="Zip Code"
                              placeholder="####-###"
                              v-model="student.zipCode"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>

                  <div class="col">
                    <label for="area">{{$t('localidade')}}</label>
                      <ValidationProvider name="area" rules="required" v-slot="{ errors }">
                          <input
                              type="text"
                              class="form-control"
                              id="area"
                              name="Area"
                              v-model="student.area"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>
                </div>
              </div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="identification">{{$t('doc_identificação')}}</label>
                      <ValidationProvider name="identification" rules="required" v-slot="{ errors }">
                          <select
                              class="custom-select"
                              name="Identification Number"
                              id="identification"
                              single
                              v-model="student.identificationDocument"
                          >
                              <option selected value="cc">{{$t('cartao_cidadao')}}</option>
                              <option value="ccond">{{$t('carta_conducao')}}</option>
                              <option value="passp">{{$t('passaporte')}}</option>
                          </select>
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>
                  <div class="col">
                    <label for="number">{{$t('numero_identificação')}}</label>

                      <ValidationProvider name="identificationNumber" rules="required|alpha_num" v-slot="{ errors }">
                          <input
                              class="form-control"
                              name="ID Number"
                              id="number"
                              v-model="student.identificationNumber"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>
                </div>
              </div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="NISS">NISS</label>
                      <ValidationProvider name="niss" rules="required|digits:11|numeric" v-slot="{ errors }">
                          <input
                              class="form-control"
                              name="NISS"
                              id="NISS"
                              v-model="student.niss"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>

                  <div class="col">
                    <label for="NIF">NIF</label>
                      <ValidationProvider name="nif" rules="required|digits:9|numeric" v-slot="{ errors }">
                          <input
                              :disabled="!isNotApproved"
                              aria-label="NIF"
                              class="form-control"
                              name="NIF"
                              id="NIF"
                              v-model="student.nif"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>

                  <div class="col">
                    <label for="SNS">SNS</label>
                      <ValidationProvider name="sns" rules="required|digits:9|numeric" v-slot="{ errors }">
                          <input
                              class="form-control"
                              name="SNS"
                              id="SNS"
                              v-model="student.sns"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>
                </div>
              </div>

              <div class="dropdown-divider"></div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="year">{{$t('ano_curricular')}}</label>
                      <ValidationProvider name="curricularYear" rules="required|between:1,5|numeric" v-slot="{ errors }">
                          <input
                              min="2000"
                              class="form-control"
                              name="Curricular Year"
                              id="year"
                              v-model="student.curricularYear"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>

                  <div class="col">
                    <label for="enrolledYear">{{$t('ano_matricula')}}</label>
                      <ValidationProvider name="enrolledYear" rules="required|digits:4|numeric" v-slot="{ errors }">
                          <input
                              class="form-control"
                              id="enrolledYear"
                              name="Enrollment Year"
                              v-model="student.enruledYear"
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
                      <ValidationProvider name="responsibleName" rules="required|alpha_spaces" v-slot="{ errors }">
                          <input
                              type="text"
                              class="form-control"
                              id="responsibleName"
                              name="Responsible Name"
                              v-model="student.responsibleName"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>

                  <div class="col">
                    <label for="responsiblePhone">{{$t('contacto_telefónico')}}</label>
                      <ValidationProvider name="responsiblePhone" rules="required|digits:9|numeric" v-slot="{ errors }">
                          <input
                              class="form-control"
                              name="Responsible Phone"
                              id="responsiblePhone"
                              min="1"
                              max="999999999"
                              v-model="student.responsiblePhone"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>

                  <div class="col">
                    <label for="responsibleKin">{{$t('parentesco')}}</label>
                      <ValidationProvider name="responsibleKin" rules="required|alpha_spaces" v-slot="{ errors }">
                          <input
                              type="text"
                              class="form-control"
                              id="responsibleKin"
                              name="Kinship"
                              v-model="student.responsibleKin"
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
                              name="Responsible Email"
                              v-model="student.responsibleEmail"
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
                      <ValidationProvider name="emergencyName" rules="alpha_spaces" v-slot="{ errors }">
                          <input
                              type="text"
                              class="form-control"
                              id="emergencyName"
                              name="Emergency Name"
                              v-model="student.emergencyName"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>

                  <div class="col">
                    <label for="emergencyPhone">{{$t('contacto_telefónico')}}</label>
                      <ValidationProvider name="emergencyPhone" rules="digits:9|numeric" v-slot="{ errors }">
                          <input
                              class="form-control"
                              name="Emergency Phone"
                              id="emergencyPhone"
                              v-model="student.emergencyPhone"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>

                  <div class="col">
                    <label for="emergencyKin">{{$t('parentesco')}}</label>
                      <ValidationProvider name="emergencyKin" rules="alpha_spaces" v-slot="{ errors }">
                          <input
                              type="text"
                              class="form-control"
                              id="emergencyKin"
                              name="Kinship"
                              v-model="student.emergencyKin"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>

                  <div class="col">
                    <label for="emergencyEmail">{{$t('email')}}</label>
                      <ValidationProvider name="emergencyEmail" rules="email" v-slot="{ errors }">
                          <input
                              type="email"
                              class="form-control"
                              id="emergencyEmail"
                              name="Emergency Email"
                              v-model="student.emergencyEmail"
                          />
                          <code>{{ errors[0] }}</code>
                      </ValidationProvider>
                  </div>
                </div>
              </div>
                <div v-if="isNotApproved">
              <div class="dropdown-divider"></div>

              <h3>{{$t('tipos_nee')}}</h3>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <b-form-checkbox
                      v-model="student.neeTypeSight"
                      name="check-button"
                      switch
                    >{{$t('visão')}}</b-form-checkbox>
                  </div>
                  <div class="col">
                    <b-form-checkbox
                      v-model="student.neeTypeCommunication"
                      name="check-button"
                      switch
                    >{{$t('dislexia')}}</b-form-checkbox>
                  </div>
                </div>
              </div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <b-form-checkbox
                      v-model="student.neeTypeEaring"
                      name="check-button"
                      switch
                    >{{$t('audição')}}</b-form-checkbox>
                  </div>
                  <div class="col">
                    <b-form-checkbox
                      v-model="student.neeTypeLearning"
                      name="check-button"
                      switch
                    >{{$t('asperger')}}</b-form-checkbox>
                  </div>
                </div>
              </div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <b-form-checkbox
                      v-model="student.neeTypeMotor"
                      name="check-button"
                      switch
                    >{{$t('motora')}}</b-form-checkbox>
                  </div>
                  <div class="col">
                    <b-form-checkbox
                      v-model="student.neeTypeMental"
                      name="check-button"
                      switch
                    >{{$t('psicológico')}}</b-form-checkbox>
                  </div>
                </div>
              </div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <b-form-checkbox
                      v-model="student.neeTypeAnotherDisease"
                      name="check-button"
                      switch
                    >{{$t('outras_doenças')}}</b-form-checkbox>
                  </div>
                  <div v-if="student.neeTypeAnotherDisease" class="col">
                    <input
                      type="text"
                      class="form-control"
                      id="anotherDisease"
                      name="anotherDisease"
                      v-model="student.neeTypeDisease"
                      placeholder="Indique o nome da doença"
                    />
                  </div>
                </div>
              </div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="functionalAnalysis">{{$t('analise_funcional')}}</label>
                    <textarea
                      class="form-control"
                      type="text"
                      name="functionalAnalysis"
                      id="functionalAnalysis"
                      v-model="student.functionalAnalysis"
                    ></textarea>
                  </div>
                </div>
              </div>

              <div class="dropdown-divider"></div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="educationalSupport">{{$t('descrição_apoio')}}</label>
                    <textarea
                      class="form-control"
                      type="text"
                      name="educationalSupport"
                      id="educationalSupport"
                      v-model="student.educationalSupport"
                    ></textarea>
                  </div>
                </div>
              </div>
                <br>
              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="files">{{$t('documentacao_comprovativa')}}**</label>
                    <div class="field">
                      <input
                        type="file"
                        id="files"
                        ref="files"
                        v-on:change="handleFiles()"
                        multiple
                      />
                        <h6>**{{$t('info_documents')}}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </div>
            <button v-if="isNotApproved" class="btn btn-primary" type="submit">{{$t('submeter')}}</button>
          </div>
        </div>
      </form>
    </ValidationObserver>
        <button v-if="!isNotApproved" class="btn btn-primary" @click="updateTransferredAccount()">{{$t('actualizar_dados')}}</button>
    </div>
  </div>
</template>

<script>
import VueGoogleAutocomplete from "vue-google-autocomplete";

export default {
  components: { VueGoogleAutocomplete },
  data() {
    return {
      files: [],
      isNotApproved:null,
      isTransferred:null,
      student: {
        name: this.$store.state.user.name,
        number: this.$store.state.user.number,
        email: this.$store.state.user.email,
        phoneNumber: null,
        birthDate: null,
        residence: null,
        zipCode: null,
        area: null,
        identificationDocument: null,
        identificationNumber: null,
        enruledYear: null,
        gender: null,
        curricularYear: null,
        responsibleName: null,
        responsiblePhone: null,
        responsibleKin: null,
        responsibleEmail: null,
        emergencyName: null,
        emergencyPhone: null,
        emergencyKin: null,
        emergencyEmail: null,
        neeTypeSight: false,
        neeTypeEaring: false,
        neeTypeMotor: false,
        neeTypeAnotherDisease: false,
        neeTypeDisease: null,
        neeTypeCommunication: false,
        neeTypeLearning: false,
        neeTypeMental: false,
        functionalAnalysis: null,
        educationalSupport: null,
        niss: null,
        nif: null,
        sns: null,
      },
      index: 0
    };
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  },
  mounted() {
      this.student = this.user;
      this.isTransferred = this.user.transferAccountStatus === "transferred";
      this.isNotApproved = this.user.enee !== "approved";
  },
    methods: {
        updateTransferredAccount(){
            const formData = new FormData();

            formData.append("phoneNumber", this.student.phoneNumber);
            formData.append("residence", this.student.residence);
            formData.append("zipCode", this.student.zipCode);
            formData.append("area", this.student.area);
            formData.append(
                "identificationDocument",
                this.student.identificationDocument
            );
            formData.append(
                "identificationNumber",
                this.student.identificationNumber
            );
            formData.append("enruledYear", this.student.enruledYear);
            formData.append("curricularYear", this.student.curricularYear);
            formData.append("responsibleName", this.student.responsibleName);
            formData.append("responsiblePhone", this.student.responsiblePhone);
            formData.append("responsibleKin", this.student.responsibleKin);
            formData.append("responsibleEmail", this.student.responsibleEmail);
            formData.append("emergencyName", this.student.emergencyName);
            formData.append("emergencyPhone", this.student.emergencyPhone);
            formData.append("emergencyKin", this.student.emergencyKin);
            formData.append("emergencyEmail", this.student.emergencyEmail);
            formData.append("niss", this.student.niss);
            formData.append("sns", this.student.sns);

            axios
                .post("api/updateTransferredAccount", formData)
                .then(response => {
                    this.$store.commit("setUser", response.data);
                    this.$router.push("/student");
                    this.$toasted.success(
                        "Dados atualizados com sucesso.",
                        {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        }
                    );
                })
                .catch(error => {
                        console.log(error);
                        this.$toasted.error("Erro ao atualizar os dados.", {
                            duration: 4000,
                            position: "top-center",
                            theme: "bubble"
                        });
                        });
        } ,

    // getAddressData(addressData, placeResultData, id) {
    //   this.student.residence = addressData.route;
    //   this.student.area = addressData.locality;
    //   this.student.zipCode = addressData.postal_code;
    //   console.log(this.student.residence);
    //   let aux = this.student.residence.split(",");
    //
    //   if (this.student.zipCode == undefined) {
    //     this.getResidence();
    //   }
    // },
    sendForm() {
      const formData = new FormData();
      for (var i = 0; i < this.files.length; i++) {
        formData.append("photo" + i, this.files[i]);
      }
      formData.append("numberPhotos", this.files.length);
      formData.append("name", this.student.name);
      formData.append("number", this.student.number);
      formData.append("email", this.student.email);
      formData.append("phoneNumber", this.student.phoneNumber);
      formData.append("birthDate", this.student.birthDate);
      formData.append("residence", this.student.residence);
      formData.append("zipCode", this.student.zipCode);
      formData.append("area", this.student.area);
      formData.append("gender", this.student.gender);
      formData.append(
        "identificationDocument",
        this.student.identificationDocument
      );
      formData.append(
        "identificationNumber",
        this.student.identificationNumber
      );
      formData.append("enruledYear", this.student.enruledYear);
      formData.append("curricularYear", this.student.curricularYear);
      formData.append("responsibleName", this.student.responsibleName);
      formData.append("responsiblePhone", this.student.responsiblePhone);
      formData.append("responsibleKin", this.student.responsibleKin);
      formData.append("responsibleEmail", this.student.responsibleEmail);
      formData.append("emergencyName", this.student.emergencyName);
      formData.append("emergencyPhone", this.student.emergencyPhone);
      formData.append("emergencyKin", this.student.emergencyKin);
      formData.append("emergencyEmail", this.student.emergencyEmail);
      formData.append("neeTypeSight", this.student.neeTypeSight);
      formData.append("neeTypeEaring", this.student.neeTypeEaring);
      formData.append("neeTypeMotor", this.student.neeTypeMotor);
      formData.append(
        "neeTypeCommunication",
        this.student.neeTypeCommunication
      );
      formData.append("neeTypeLearning", this.student.neeTypeLearning);
      formData.append("neeTypeMental", this.student.neeTypeMental);
      formData.append(
        "neeTypeAnotherDisease",
        this.student.neeTypeAnotherDisease
      );
      formData.append("neeTypeDisease", this.student.neeTypeDisease);
      formData.append("functionalAnalysis", this.student.functionalAnalysis);
      formData.append("educationalSupport", this.student.educationalSupport);
      formData.append("niss", this.student.niss);
      formData.append("sns", this.student.sns);
      formData.append("nif", this.student.nif);

      axios
        .post("api/subscription", formData)
        .then(response => {
          this.$store.commit("setUser", response.data);
          this.$router.push("/student");
          this.$toasted.success(
            "Candidatura ao estatuto de ENE submetida com sucesso.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error("Erro ao fazer pedido de candidatura.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        });
    },
    handleFiles() {
      this.files = [];
      let uploadedFiles = this.$refs.files.files;
      for (var i = 0; i < uploadedFiles.length; i++) {
        this.files.push(uploadedFiles[i]);
      }
    },
    // getResidence() {
    //   console.log(this.student.area);
    //
    //   axios
    //     .get(
    //       "api/residence/" + this.student.residence + "/" + this.student.area
    //     )
    //     .then(response => {
    //       console.log(response);
    //
    //       this.student.zipCode =
    //         response.data.cpo_cod4 + "-" + response.data.cpo_cod3;
    //     })
    //     .catch(error => {
    //       console.log(error);
    //     });
    // }
  }
};
</script>

<style>
</style>
