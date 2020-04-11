<template>
  <div class="container pt-2 pb-3" v-if="user != null">
    <div v-if="user.enee!='awaiting' && user.enee!='approved'">
      <form @submit.prevent="validateBeforeSubmit">
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
                    <input
                      v-validate="{ required: true, digits:9 }"
                      :class="{'input': true, 'is-danger': errors.has('phone-number') }"
                      class="form-control"
                      name="Phone Number"
                      id="phone-number"
                      min="1"
                      max="999999999"
                      v-model="student.phoneNumber"
                    />
                    <i v-show="errors.has('Phone Number')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Phone Number')"
                      class="help is-danger"
                    >{{ errors.first('Phone Number') }}</span>
                  </div>
                  <div class="col">
                    <label for="birth-date">{{$t('data_nascimento')}}</label>
                    <input
                      v-validate="{ required: true, date_format:'yyyy-MM-dd' }"
                      :class="{'input': true, 'is-danger': errors.has('birth-date') }"
                      class="form-control"
                      value
                      id="birth-date"
                      name="Birth Date"
                      placeholder="yyyy-mm-dd"
                      v-model="student.birthDate"
                    />
                    <i v-show="errors.has('Birth Date')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Birth Date')"
                      class="help is-danger"
                    >{{ errors.first('Birth Date') }}</span>
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
                    <select
                      v-validate="'required' "
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
                    <i v-show="errors.has('Gender')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Gender')"
                      class="help is-danger"
                    >{{ errors.first('Gender') }}</span>
                  </div>
                </div>
              </div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col-6">
                    <label for="residence">{{$t('residencia')}}</label>
                    <vue-google-autocomplete
                      class="form-control"
                      id="residence"
                      name="residence"
                      v-on:placechanged="getAddressData"
                      country="pt"
                    ></vue-google-autocomplete>
                  </div>
                  <div class="col">
                    <label for="zipCode">{{$t('codigo_postal')}}</label>
                    <input
                      v-validate="'required'"
                      class="form-control"
                      pattern="\d\d\d\d[-]\d\d\d"
                      id="zipCode"
                      name="Zip Code"
                      placeholder="1234-567"
                      v-model="student.zipCode"
                    />
                    <i v-show="errors.has('Zip Code')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Zip Code')"
                      class="help is-danger"
                    >{{ errors.first('Zip Code') }}</span>
                  </div>
                  <div class="col">
                    <label for="area">{{$t('localidade')}}</label>
                    <input
                      v-validate="'required'"
                      type="text"
                      class="form-control"
                      id="area"
                      name="Area"
                      v-model="student.area"
                    />
                    <i v-show="errors.has('Area')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Area')"
                      class="help is-danger"
                    >{{ errors.first('Area') }}</span>
                  </div>
                </div>
              </div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="identification">{{$t('doc_identificação')}}</label>
                    <select
                      v-validate="'required'"
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
                    <i v-show="errors.has('Identification Number')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Identification Number')"
                      class="help is-danger"
                    >{{ errors.first('Identification Number') }}</span>
                  </div>
                  <div class="col">
                    <label for="number">Nº</label>
                    <input
                      v-validate="{required:true}"
                      class="form-control"
                      name="Student Number"
                      id="number"
                      v-model="student.identificationNumber"
                    />
                    <i v-show="errors.has('Student Number')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Student Number')"
                      class="help is-danger"
                    >{{ errors.first('Student Number') }}</span>
                  </div>
                </div>
              </div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="NISS">NISS</label>
                    <input
                      v-validate="{required:true,digits:11}"
                      class="form-control"
                      name="NISS"
                      id="NISS"
                      v-model="student.niss"
                    />
                    <i v-show="errors.has('NISS')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('NISS')"
                      class="help is-danger"
                    >{{ errors.first('NISS') }}</span>
                  </div>
                  <div class="col">
                    <label for="NIF">NIF</label>
                    <input
                      aria-label="NIF"
                      v-validate="{required:true,digits:9}"
                      class="form-control"
                      name="NIF"
                      id="NIF"
                      v-model="student.nif"
                    />
                    <i v-show="errors.has('NIF')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('NIF')"
                      class="help is-danger"
                    >{{ errors.first('NIF') }}</span>
                  </div>
                  <div class="col">
                    <label for="SNS">SNS</label>
                    <input
                      v-validate="{required:true,digits:9}"
                      class="form-control"
                      name="SNS"
                      id="SNS"
                      v-model="student.sns"
                    />
                    <i v-show="errors.has('SNS')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('SNS')"
                      class="help is-danger"
                    >{{ errors.first('SNS') }}</span>
                  </div>
                </div>
              </div>

              <div class="dropdown-divider"></div>

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="year">{{$t('ano_curricular')}}</label>
                    <input
                      v-validate="{required:true,digits:4,min_value:2000}"

                      class="form-control"
                      name="Curricular Year"
                      id="year"
                      min="2000"
                      v-model="student.curricularYear"
                    />
                    <i v-show="errors.has('Curricular Year')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Curricular Year')"
                      class="help is-danger"
                    >{{ errors.first('Curricular Year') }}</span>
                  </div>
                  <div class="col">
                    <label for="enruledYear">{{$t('ano_matricula')}}</label>
                    <input
                      v-validate="{required:true,digits:4}"
                      class="form-control"
                      id="enruledYear"
                      name="Enrollment Year"
                      v-model="student.enruledYear"
                    />
                    <i v-show="errors.has('Enrollment Year')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Enrollment Year')"
                      class="help is-danger"
                    >{{ errors.first('Enrollment Year') }}</span>
                  </div>
                </div>
              </div>

              <div class="dropdown-divider"></div>

              <h3>{{$t('identificação_responsável')}}</h3>
              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="responsibleName">{{$t('nome')}}</label>
                    <input
                      v-validate="'required|regex:[a-zA-Z][a-zA-Z ]+'"
                      type="text"
                      class="form-control"
                      id="responsibleName"
                      name="Responsible Name"
                      v-model="student.responsibleName"
                    />
                    <i v-show="errors.has('Responsible Name')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Responsible Name')"
                      class="help is-danger"
                    >{{ errors.first('Responsible Name') }}</span>
                  </div>
                  <div class="col">
                    <label for="responsiblePhone">{{$t('contacto_telefónico')}}</label>
                    <input
                      v-validate="{ required: true, digits:9 }"
                      class="form-control"
                      name="Responsible Phone"
                      id="responsiblePhone"
                      min="1"
                      max="999999999"
                      v-model="student.responsiblePhone"
                    />
                    <i v-show="errors.has('Responsible Phone')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Responsible Phone')"
                      class="help is-danger"
                    >{{ errors.first('Responsible Phone') }}</span>
                  </div>
                  <div class="col">
                    <label for="responsibleKin">{{$t('parentesco')}}</label>
                    <input
                      v-validate="'required|regex:[a-zA-Z][a-zA-Z ]+'"
                      type="text"
                      class="form-control"
                      id="responsibleKin"
                      name="Kinship"
                      v-model="student.responsibleKin"
                    />
                    <i v-show="errors.has('Kinship')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Kinship')"
                      class="help is-danger"
                    >{{ errors.first('Kinship') }}</span>
                  </div>
                  <div class="col">
                    <label for="responsibleEmail">{{$t('email')}}</label>
                    <input
                      v-validate="{ required: true, email:true }"
                      type="email"
                      class="form-control"
                      id="responsibleEmail"
                      name="Responsible Email"
                      v-model="student.responsibleEmail"
                    />
                    <i v-show="errors.has('Responsible Email')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Responsible Email')"
                      class="help is-danger"
                    >{{ errors.first('Responsible Email') }}</span>
                  </div>
                </div>
              </div>

              <h3>{{$t('contacto_emergencia')}}</h3>
              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="emergencyName">{{$t('nome')}}</label>
                    <input
                      v-validate="'required|regex:[a-zA-Z][a-zA-Z ]+'"
                      type="text"
                      class="form-control"
                      id="emergencyName"
                      name="Emergency Name"
                      v-model="student.emergencyName"
                    />
                    <i v-show="errors.has('Emergency Name')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Emergency Name')"
                      class="help is-danger"
                    >{{ errors.first('Emergency Name') }}</span>
                  </div>
                  <div class="col">
                    <label for="emergencyPhone">{{$t('contacto_telefónico')}}</label>
                    <input
                      v-validate="{ required: true, digits:9 }"
                      class="form-control"
                      name="Emergency Phone"
                      id="emergencyPhone"
                      v-model="student.emergencyPhone"
                    />
                    <i v-show="errors.has('Emergency Phone')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Emergency Phone')"
                      class="help is-danger"
                    >{{ errors.first('Emergency Phone') }}</span>
                  </div>
                  <div class="col">
                    <label for="emergencyKin">{{$t('parentesco')}}</label>
                    <input
                      v-validate="'required|regex:[a-zA-Z][a-zA-Z ]+'"
                      type="text"
                      class="form-control"
                      id="emergencyKin"
                      name="Kinship"
                      v-model="student.emergencyKin"
                    />
                    <i v-show="errors.has('Kinship')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Kinship')"
                      class="help is-danger"
                    >{{ errors.first('Kinship') }}</span>
                  </div>
                  <div class="col">
                    <label for="emergencyEmail">{{$t('email')}}</label>
                    <input
                      v-validate="{ required: true, email:true }"
                      type="email"
                      class="form-control"
                      id="emergencyEmail"
                      name="Emergency Email"
                      v-model="student.emergencyEmail"
                    />
                    <i v-show="errors.has('Emergency Email')" class="fa fa-warning"></i>
                    <span
                      v-show="errors.has('Emergency Email')"
                      class="help is-danger"
                    >{{ errors.first('Emergency Email') }}</span>
                  </div>
                </div>
              </div>

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

              <div class="container-full-width">
                <div class="row">
                  <div class="col">
                    <label for="files">{{$t('relatorio_medico')}}</label>
                    <div class="field">
                      <input
                        type="file"
                        id="files"
                        ref="files"
                        v-on:change="handleFiles()"
                        multiple
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <button class="btn btn-primary" type="submit">{{$t('submeter')}}</button>
          </div>
        </div>
      </form>
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
        sns: null
      },
      index: 0
    };
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  },
  methods: {
    validateBeforeSubmit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.sendForm();
          return;
        }
      });
    },
    getAddressData(addressData, placeResultData, id) {
      this.student.residence = addressData.route;
      this.student.area = addressData.locality;
      this.student.zipCode = addressData.postal_code;
      console.log(this.student.residence);
      let aux = this.student.residence.split(",");

      if (this.student.zipCode == undefined) {
        this.getResidence();
      }
    },
    sendForm() {
      console.log(this.student.birthDate);

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
            "Candidatura ao estatuto de ENEE submetida com sucesso.",
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
    getResidence() {
      console.log(this.student.area);

      axios
        .get(
          "api/residence/" + this.student.residence + "/" + this.student.area
        )
        .then(response => {
          console.log(response);

          this.student.zipCode =
            response.data.cpo_cod4 + "-" + response.data.cpo_cod3;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style>
</style>
