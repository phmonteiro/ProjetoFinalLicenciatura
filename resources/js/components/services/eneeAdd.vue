<template>
  <div class="container pt-2 pb-3">
    <form @submit.prevent="validateBeforeSubmit">
      <h2>{{$t('adicionar_aluno')}}</h2>
      <div class="row">
        <div class="col-md-12 pt-4">
          <h4>{{$t('identificação_estudante')}}</h4>
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
                    max="999999999"
                    v-model="student.number"
                    disabled
                  >
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
                  >
                </div>
                <div class="col">
                  <label for="phone-number">{{$t('contacto_telefónico')}}</label>
                  <input
                    v-validate="{ required: true, digits:9 }"
                    :class="{'input': true, 'is-danger': errors.has('phone-number') }"
                    class="form-control"
                    name="phone-number"
                    id="phone-number"
                    min="1"
                    max="999999999"
                    v-model="student.phoneNumber"
                  >
                  <i v-show="errors.has('phone-number')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('phone-number')"
                    class="help is-danger"
                  >{{ errors.first('phone-number') }}</span>
                </div>
                <div class="col">
                  <label for="birth-date">{{$t('data_nascimento')}}</label>
                  <input
                    v-validate="{ required: true, date_format:'dd/MM/yyyy' }"
                    :class="{'input': true, 'is-danger': errors.has('birth-date') }"
                    class="form-control"
                    value
                    id="birth-date"
                    name="birth-date"
                    placeholder="dd/mm/yyyy"
                    v-model="student.birthDate"
                  >
                  <i v-show="errors.has('birth-date')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('birth-date')"
                    class="help is-danger"
                  >{{ errors.first('birth-date') }}</span>
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
                    name="email"
                    placeholder="email@mail.com"
                    id="email"
                    v-model="student.email"
                    disabled
                  >
                </div>
                <div class="col-4">
                  <label for="gender">{{$t('género')}}</label>
                  <select
                    v-validate="'required' "
                    class="custom-select"
                    name="identification"
                    single
                    v-model="student.gender"
                  >
                    <option selected value="masculino">{{$t('masculino')}}</option>
                    <option value="feminino">{{$t('feminino')}}</option>
                    <option value="outro">{{$t('outro')}}</option>
                  </select>
                  <i v-show="errors.has('identification')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('identification')"
                    class="help is-danger"
                  >{{ errors.first('identification') }}</span>
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
                    name="zipCode"
                    placeholder="1234-567"
                    v-model="student.zipCode"
                  >
                  <i v-show="errors.has('zipCode')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('zipCode')"
                    class="help is-danger"
                  >{{ errors.first('zipCode') }}</span>
                </div>
                <div class="col">
                  <label for="area">{{$t('localidade')}}</label>
                  <input
                    v-validate="'required'"
                    type="text"
                    class="form-control"
                    id="area"
                    name="area"
                    v-model="student.area"
                  >
                  <i v-show="errors.has('area')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('area')"
                    class="help is-danger"
                  >{{ errors.first('area') }}</span>
                </div>
              </div>
            </div>

            <div class="container-full-width">
              <div class="row">
                <div class="col">
                  <h5>{{$t('doc_identificação')}}</h5>
                  <select
                    v-validate="'required'"
                    class="custom-select"
                    name="identification"
                    single
                    v-model="student.identificationDocument"
                  >
                    <option selected value="cc">{{$t('cartao_cidadao')}}</option>
                    <option value="ccond">{{$t('carta_conducao')}}</option>
                    <option value="passp">{{$t('passaporte')}}</option>
                  </select>
                  <i v-show="errors.has('identification')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('identification')"
                    class="help is-danger"
                  >{{ errors.first('identification') }}</span>
                </div>
                <div class="col">
                  <label for="number">Nº</label>
                  <input
                    v-validate="'required'"
                    class="form-control"
                    name="number"
                    id="number"
                    v-model="student.identificationNumber"
                  >
                  <i v-show="errors.has('number')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('number')"
                    class="help is-danger"
                  >{{ errors.first('number') }}</span>
                </div>
              </div>
            </div>

            <div class="container-full-width">
              <div class="row">
                <div class="col">
                  <label for="niss">NISS</label>
                  <input
                    v-validate="{required:true,digits:11}"
                    class="form-control"
                    name="niss"
                    id="NISS"
                    v-model="student.niss"
                  >
                  <i v-show="errors.has('niss')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('niss')"
                    class="help is-danger"
                  >{{ errors.first('niss') }}</span>
                </div>
                <div class="col">
                  <label for="nif">NIF</label>
                  <input
                    v-validate="{required:true,digits:9}"
                    class="form-control"
                    name="nif"
                    id="NIF"
                    v-model="student.nif"
                  >
                  <i v-show="errors.has('nif')" class="fa fa-warning"></i>
                  <span v-show="errors.has('nif')" class="help is-danger">{{ errors.first('nif') }}</span>
                </div>
                <div class="col">
                  <label for="sns">SNS</label>
                  <input
                    v-validate="{required:true,digits:9}"
                    class="form-control"
                    name="sns"
                    id="SNS"
                    v-model="student.sns"
                  >
                  <i v-show="errors.has('sns')" class="fa fa-warning"></i>
                  <span v-show="errors.has('sns')" class="help is-danger">{{ errors.first('sns') }}</span>
                </div>
              </div>
            </div>

            <div class="dropdown-divider"></div>

            <div class="container-full-width">
              <div class="row">
                <div class="col">
                  <label for="curricular-year">{{$t('ano_curricular')}}</label>
                  <input
                    v-validate="'required'"
                    class="form-control"
                    name="curricular-year"
                    id="year"
                    min="1"
                    max="10"
                    v-model="student.curricularYear"
                  >
                  <i v-show="errors.has('curricular-year')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('curricular-year')"
                    class="help is-danger"
                  >{{ errors.first('curricular-year') }}</span>
                </div>
                <div class="col">
                  <label for="enruledYear">{{$t('ano_matricula')}}</label>
                  <input
                    v-validate="{required:true,digits:4}"
                    class="form-control"
                    id="enruledYear"
                    name="enruledYear"
                    v-model="student.enruledYear"
                  >
                  <i v-show="errors.has('enruledYear')" class="fa fa-warning"></i>
                  <span
                    v-show="errors.has('enruledYear')"
                    class="help is-danger"
                  >{{ errors.first('enruledYear') }}</span>
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
                    v-model="student.responsibleName"
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
                    v-model="student.responsiblePhone"
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
                    v-model="student.responsibleKin"
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
                    v-model="student.responsibleEmail"
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
                    v-model="student.emergencyName"
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
                    v-model="student.emergencyPhone"
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
                    v-model="student.emergencyKin"
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
                    v-model="student.emergencyEmail"
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

            <h5>{{$t('tipos_nee')}}</h5>
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
                  >
                </div>
              </div>
            </div>

            <div class="container-full-width">
              <div class="row">
                <div class="col">
                  <label>{{$t('analise_funcional')}}</label>
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
                  <label for="description">{{$t('descrição_apoio')}}</label>
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
                  <label for="neeType">{{$t('relatorio_medico')}}</label>
                  <div class="field">
                    <input type="file" id="files" ref="files" v-on:change="handleFiles()" multiple>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button class="btn btn-primary" type="submit">{{$t('adicionar')}}</button>
        </div>
      </div>
    </form>
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
        name: null,
        number: null,
        email: null,
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
        .post("api/eneeAdd", formData)
        .then(response => {
          this.$toasted.success("Estudante com nee adicionado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error("Erro ao adicionar estudante.", {
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
