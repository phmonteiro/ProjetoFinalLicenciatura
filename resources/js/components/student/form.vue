<template>
  <div class="container">
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
                  v-model="user.number"
                >
              </div>
              <div class="col">
                <label for="student-name">Nome</label>
                <input
                  type="text"
                  class="form-control is-valid"
                  id="student-name"
                  placeholder="Nome Estudante"
                  name="student-name"
                  v-model="user.name"
                >
              </div>
              <div class="col">
                <label for="phone-number">Contacto Telefónico</label>
                <input
                  class="form-control is-valid"
                  type="number"
                  name="phone-number"
                  id="phone-number"
                  min="1"
                  max="999999999"
                  v-model="user.phoneNumber"
                >
              </div>
              <div class="col">
                <label for="birth-date">Data Nascimento</label>
                <input
                  class="form-control is-valid"
                  type="date"
                  value
                  id="birth-date"
                  name="birth-date"
                  v-model="user.birthDate"
                >
              </div>
            </div>
          </div>

          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="email">E-mail</label>
                <input
                  class="form-control is-valid"
                  type="email"
                  name="email"
                  placeholder="email@mail.com"
                  id="email"
                  v-model="user.email"
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
                  id="residencia"
                  placeholder="Morada"
                  name="residence"
                >
                <!--Falta v-model da morada-->
              </div>
              <div class="col">
                <label for="zip-code-1">Código Postal</label>
                <input
                  class="form-control"
                  type="number"
                  pattern="[0-9]{4}"
                  title="4 digitos"
                  id="zip-code-1"
                  name="zip-code-1"
                >
                <!--Falta v-model do código de postal-->
              </div>
              <div class="col">
                <label for="zip-code-2">&nbsp;</label>
                <input
                  class="form-control"
                  type="number"
                  pattern="[0-9]{3}"
                  title="3 digitos"
                  id="zip-code-2"
                  name="zip-code-2"
                >
                <!--Falta v-model do código de postal-->
              </div>

              <div class="col">
                <label for="area">Localidade</label>
                <input
                  type="text"
                  class="form-control"
                  id="area"
                  placeholder="Localidade"
                  name="area"
                >
                <!--Falta v-model da localidade-->
              </div>
            </div>
          </div>

          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <h5>Doc. Identificacao</h5>
                <select class="custom-select" name="identification" single>
                  <option selected value="cc">Cartao Cidadao</option>
                  <option value="ccond">Carta Conducao</option>
                  <option value="passp">Passaporte</option>
                </select>
                <!--Falta v-model do documento de identificação -->
              </div>
              <div class="col">
                <label for="number">Nº</label>
                <input
                  class="form-control is-valid"
                  type="number"
                  name="number"
                  id="number"
                  min
                  max
                >
                <!--Falta v-model do numero do documento de identificação -->
              </div>
            </div>
          </div>

          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="niss">NISS</label>
                <input
                  class="form-control is-valid"
                  type="number"
                  name="niss"
                  id="NISS"
                  v-model="user.niss"
                >
              </div>
              <div class="col">
                <label for="nif">NIF</label>
                <input
                  class="form-control is-valid"
                  type="number"
                  name="nif"
                  id="NIF"
                  v-model="user.nif"
                >
              </div>
              <div class="col">
                <label for="sns">SNS</label>
                <input
                  class="form-control is-valid"
                  type="number"
                  name="sns"
                  id="SNS"
                  v-model="user.sns"
                >
              </div>
            </div>
          </div>

          <div class="dropdown-divider"></div>

          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="curricular-year">Ano Curricular</label>
                <input
                  class="form-control is-valid"
                  type="number"
                  name="curricular-year"
                  id="year"
                  min
                  max
                  v-model="user.curricularYear"
                >
              </div>
              <div class="col">
                <label for="enruled-year">Ano 1ª matricula</label>
                <input
                  type="text"
                  class="form-control is-valid"
                  id="enruled-year"
                  placeholder
                  name="enruled-year"
                >
                <!--Falta o v-model do 1º ano de matricula-->
              </div>
            </div>
          </div>

          <div class="dropdown-divider"></div>

          <h5>Identificação do Reponsavel</h5>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="responsible-name-1">Nome</label>
                <input
                  type="text"
                  class="form-control"
                  id="responsible-name-1"
                  placeholder
                  name="responsible-name-1"
                >
              </div>
              <div class="col">
                <label for="responsible-phone-1">Contacto telefónico</label>
                <input
                  class="form-control"
                  type="number"
                  name="responsible-phone-1"
                  id="responsible-phone-1"
                  min="1"
                  max="9999999999"
                >
              </div>
              <div class="col">
                <label for="responsible-kin-1">Parentesco</label>
                <input
                  type="text"
                  class="form-control"
                  id="responsible-kin-1"
                  placeholder
                  name="responsible-kin-1"
                >
              </div>
              <div class="col">
                <label for="responsible-email-1">E-mail</label>
                <input
                  type="email"
                  class="form-control"
                  id="responsible-email-1"
                  placeholder
                  name="responsible-email-1"
                >
              </div>
            </div>
          </div>

          <h5>Identificação do Contacto de Emergencia</h5>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="emergency-name">Nome</label>
                <input
                  type="text"
                  class="form-control"
                  id="emergency-name"
                  placeholder
                  name="emergency-name"
                >
              </div>
              <div class="col">
                <label for="emergency-phone">Contacto telefónico</label>
                <input
                  class="form-control"
                  type="number"
                  name="emergency-phone"
                  id="emergency-phone"
                >
              </div>
              <div class="col">
                <label for="emergency-kin">Parentesco</label>
                <input
                  type="text"
                  class="form-control"
                  id="emergency-kin"
                  placeholder
                  name="emergency-kin"
                >
              </div>
              <div class="col">
                <label for="emergency-email">E-mail</label>
                <input
                  type="email"
                  class="form-control"
                  id="emergency-email"
                  placeholder
                  name="emergency-email"
                >
              </div>
            </div>
          </div>

          <div class="dropdown-divider"></div>
          <h5>NEE</h5>
          <div class="container-full-width">
            <div class="row">
              <div class="col">
                <label for="nee-type">Tipo de NEE</label>
                <input
                  type="text"
                  class="form-control is-valid"
                  id="nee-type"
                  placeholder
                  name="nee-type"
                >
              </div>
              <div class="col">
                <label for="nee-severity">Grau de Incapaciade(%)</label>
                <input
                  class="form-control is-valid"
                  type="number"
                  name="nee-severity"
                  id="nee-severity"
                  min="1"
                  max="99"
                >
              </div>
            </div>
          </div>
        </div>
        <button type="submit" href="#" class="btn btn-primary">Submeter</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>

<style>
</style>
