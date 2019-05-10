<template>
  <div class="container" v-if="student != null">
    <h2>Formulário de candidatura ao estatuto de ENEE</h2>
    <div class="row">
      <div class="col-md-12 pt-4">
        <h4>Identificação Estudante</h4>
        <div class="form-group">
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="student-number">Nr. Estudante</label>
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
                <label for="student-name">Nome</label>
                <input
                  type="text"
                  class="form-control"
                  id="student-name"
                  placeholder="Nome Estudante"
                  name="student-name"
                  v-model="student.name"
                  disabled
                >
              </div>
              <div class="col">
                <label for="phone-number">Contacto Telefónico</label>
                <input
                  class="form-control"
                  type="number"
                  name="phone-number"
                  id="phone-number"
                  min="1"
                  max="999999999"
                  v-model="student.phoneNumber"
                >
              </div>
              <div class="col">
                <label for="birth-date">Data Nascimento</label>
                <input
                  class="form-control"
                  type="date"
                  value
                  id="birth-date"
                  name="birth-date"
                  v-model="student.birthDate"
                >
              </div>
            </div>
          </div>

          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="email">E-mail</label>
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
            </div>
          </div>

          <div class="container-full-width">
            <div class="row">
              <div class="col-6">
                <label for="residence">Residencia em época de aulas</label>
                <input
                  type="text"
                  class="form-control"
                  id="residence"
                  placeholder="Morada"
                  name="residence"
                  v-model="student.residence"
                  v-on:change="getResidence()"
                >
              </div>
              <div class="col">
                <label for="zipCode">Código Postal</label>
                <input
                  class="form-control"
                  pattern="\d\d\d\d[-]\d\d\d"
                  id="zipCode"
                  name="zipCode"
                  placeholder="1234-567"
                  v-model="student.zipCode"
                  v-on:keyup="getZipCode()"
                >
              </div>
              <div class="col">
                <label for="area">Localidade</label>
                <input
                  type="text"
                  class="form-control"
                  id="area"
                  placeholder="Localidade"
                  name="area"
                  v-model="student.area"
                >
              </div>
            </div>
          </div>

          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <h5>Doc. Identificacao</h5>
                <select
                  class="custom-select"
                  name="identification"
                  single
                  v-model="student.identificationDocument"
                >
                  <option selected value="cc">Cartao Cidadao</option>
                  <option value="ccond">Carta Conducao</option>
                  <option value="passp">Passaporte</option>
                </select>
              </div>
              <div class="col">
                <label for="number">Nº</label>
                <input
                  class="form-control"
                  type="number"
                  name="number"
                  id="number"
                  v-model="student.identificationNumber"
                >
              </div>
            </div>
          </div>

          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="niss">NISS</label>
                <input
                  class="form-control"
                  type="number"
                  name="niss"
                  id="NISS"
                  placeholder="Niss"
                  v-model="student.niss"
                >
              </div>
              <div class="col">
                <label for="nif">NIF</label>
                <input class="form-control" type="number" name="nif" id="NIF" v-model="student.nif">
              </div>
              <div class="col">
                <label for="sns">SNS</label>
                <input class="form-control" type="number" name="sns" id="SNS" v-model="student.sns">
              </div>
            </div>
          </div>

          <div class="dropdown-divider"></div>

          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="curricular-year">Ano Curricular</label>
                <input
                  class="form-control"
                  type="number"
                  name="curricular-year"
                  id="year"
                  min="1"
                  max="10"
                  v-model="student.curricularYear"
                >
              </div>
              <div class="col">
                <label for="enruledYear">Ano 1ª matricula</label>
                <input
                  type="text"
                  class="form-control"
                  id="enruledYear"
                  name="enruledYear"
                  v-model="student.enruledYear"
                >
              </div>
            </div>
          </div>

          <div class="dropdown-divider"></div>

          <h5>Identificação do Reponsavel</h5>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="responsibleName">Nome</label>
                <input
                  type="text"
                  class="form-control"
                  id="responsibleName"
                  placeholder
                  name="responsibleName"
                  v-model="student.responsibleName"
                >
              </div>
              <div class="col">
                <label for="responsiblePhone">Contacto telefónico</label>
                <input
                  class="form-control"
                  type="number"
                  name="responsiblePhone"
                  id="responsiblePhone"
                  min="1"
                  max="9999999999"
                  v-model="student.responsiblePhone"
                >
              </div>
              <div class="col">
                <label for="responsibleKin">Parentesco</label>
                <input
                  type="text"
                  class="form-control"
                  id="responsibleKin"
                  placeholder
                  name="responsibleKin"
                  v-model="student.responsibleKin"
                >
              </div>
              <div class="col">
                <label for="responsibleEmail">E-mail</label>
                <input
                  type="email"
                  class="form-control"
                  id="responsibleEmail"
                  placeholder
                  name="responsibleEmail"
                  v-model="student.responsibleEmail"
                >
              </div>
            </div>
          </div>

          <h5>Identificação do Contacto de Emergencia</h5>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="emergencyName">Nome</label>
                <input
                  type="text"
                  class="form-control"
                  id="emergencyName"
                  placeholder
                  name="emergencyName"
                  v-model="student.emergencyName"
                >
              </div>
              <div class="col">
                <label for="emergencyPhone">Contacto telefónico</label>
                <input
                  class="form-control"
                  type="number"
                  name="emergencyPhone"
                  id="emergencyPhone"
                  v-model="student.emergencyPhone"
                >
              </div>
              <div class="col">
                <label for="emergencyKin">Parentesco</label>
                <input
                  type="text"
                  class="form-control"
                  id="emergencyKin"
                  placeholder
                  name="emergencyKin"
                  v-model="student.emergencyKin"
                >
              </div>
              <div class="col">
                <label for="emergencYEmail">E-mail</label>
                <input
                  type="email"
                  class="form-control"
                  id="emergencyEmail"
                  placeholder
                  name="emergencyEmail"
                  v-model="student.emergencyEmail"
                >
              </div>
            </div>
          </div>

          <div class="dropdown-divider"></div>
          <h5>NEE</h5>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="neeType">Tipo de NEE</label>
                <input
                  type="text"
                  class="form-control"
                  id="neeType"
                  placeholder
                  name="neeType"
                  v-model="student.neeType"
                >
              </div>
              <div class="col">
                <label for="neeSeverity">Grau de Incapaciade(%)</label>
                <input
                  class="form-control"
                  type="number"
                  name="neeSeverity"
                  id="neeSeverity"
                  min="1"
                  max="99"
                  v-model="student.neeSeverity"
                >
              </div>
            </div>
          </div>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="neeSeverity">Descrição de apoios anteriormente usufruidos</label>
                <textarea
                  class="form-control"
                  type="text"
                  name="educationalSupport"
                  id="educationalSupport"
                  v-model="student.educationalSupport"
                >Enter text here...</textarea>
              </div>
            </div>
          </div>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="neeType">Relatório médico</label>
                <div class="field">
                  <input type="file" id="files" ref="files" v-on:change="handleFiles()" multiple>
                </div>
              </div>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary" v-on:click.prevent="sendForm()">Submeter</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
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
        curricularYear: null,
        responsibleName: null,
        responsiblePhone: null,
        responsibleKin: null,
        responsibleEmail: null,
        emergencyName: null,
        emergencyPhone: null,
        emergencyKin: null,
        emergencyEmail: null,
        neeType: null,
        neeSeverity: null,
        educationalSupport: null,
        niss: null,
        nif: null,
        sns: null
      }
    };
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  },
  methods: {
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
      formData.append("neeType", this.student.neeType);
      formData.append("neeSeverity", this.student.neeSeverity);
      formData.append("educationalSupport", this.student.educationalSupport);
      formData.append("niss", this.student.niss);
      formData.append("sns", this.student.sns);
      formData.append("nif", this.student.nif);

      axios
        .post("api/subscription/" + this.$store.state.user.id, formData)
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error);
        });
    },
    handleFiles() {
      let uploadedFiles = this.$refs.files.files;
      for (var i = 0; i < uploadedFiles.length; i++) {
        this.files.push(uploadedFiles[i]);
      }
    },
    getZipCode() {
      if (this.student.zipCode.length == 8) {
        axios
          .get("api/zipCode/" + this.student.zipCode)
          .then(response => {
            if (response.data[0].art_desig) {
              this.student.residence = response.data[0].art_desig;
            }
            if (response.data[0].dsc_pos) {
              this.student.area = response.data[0].dsc_pos;
            }
          })
          .catch(error => {
            console.log(error);
          });
      }
    },
    getResidence() {
      axios
        .get("api/residence/" + this.student.residence)
        .then(response => {
          this.student.zipCode =
            response.data[0].cpo_cod4 + "-" + response.data[0].cpo_cod3;
          this.student.area = response.data[0].dsc_pos;
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
