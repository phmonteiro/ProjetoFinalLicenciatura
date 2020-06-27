<template>
  <div class="container mt-3">
      <ValidationObserver v-slot="{ handleSubmit }">
      <div class="form-group">
      <h4>Criar nova interação</h4>
        <label for="inputName">Nome</label>
      <input
        type="text"
        class="form-control"
        v-model="user.name"
        name="name"
        id="inputName"
        disabled
      >
    </div>

    <div class="form-group">
      <label for="inputEmail">E-mail</label>
      <input
        type="text"
        class="form-control"
        v-model="user.email"
        name="email"
        id="inputEmail"
        disabled
      >
    </div>
    <div class="form-group">
        <div>
            <label for="interactionDate">Data da interação:</label>
            <ValidationProvider name="date" rules="required" v-slot="{ errors }">
                <date-picker type="date" ref="interactionDate" name="interactionDate" id="interactionDate" v-model="data.interactionDate" valueType="format" lang="pt-br"></date-picker>
                <br>
                <code>{{ errors[0] }}</code>
            </ValidationProvider>

            <br><br>

            <label>Hora: </label>
            <ValidationProvider name="time" rules="required" v-slot="{ errors }">
                <vue-timepicker :minute-interval="5" name="time" format="HH:mm" v-model="data.interactionTime"></vue-timepicker>
                <br>
                <code>{{ errors[0] }}</code>
            </ValidationProvider>

        </div>

    </div>


    <div class="form-group">
      <label for="service">Serviço:</label>
        <ValidationProvider name="service" rules="required" v-slot="{ errors }">
            <b-form-select name="service" v-model="data.service" class="mb-3">
                <option :value="null" disabled>-- Selecionar o serviço pretendido --</option>
                <option value="SAPE">SAPE</option>
                <option value="SAS">SAS</option>
                <option value="Escola">Serviços Escolares</option>
                <option value="Biblioteca">Biblioteca</option>
                <option value="Direção">Direção</option>
                <option value="Gestor-Caso">Gestor de Caso</option>
                <option value="Professor-Tutor">Professor Orientador</option>
            </b-form-select>
            <code>{{ errors[0] }}</code>
        </ValidationProvider>
    </div>

      <div class="form-group">
          <label>Meio de Contacto:</label>
          <ValidationProvider name="contactMedium" rules="required" v-slot="{ errors }">
              <b-form-select name="contactMedium" id="contactMedium"  v-model="data.contactMedium" class="mb-3">
                  <option :value="null" disabled>-- Selecione o meio de contacto --</option>
                  <option value="conferencia">Video Conferência</option>
                  <option value="presencial">Presencial</option>
                  <option value="telefone">Telefone</option>
              </b-form-select>
              <code>{{ errors[0] }}</code>
          </ValidationProvider>
      </div>

      <div class="form-group" v-if="data.contactMedium==='conferencia'">
          <label for="software">Software:</label>
          <ValidationProvider name="Software" rules="required" v-slot="{ errors }">
              <input class="form-control" v-model="data.software" type="text" id="software" name="software">
              <br>
              <code>{{ errors[0] }}</code>
          </ValidationProvider>
      </div>

     <div class="form-group" v-if="data.contactMedium==='presencial'">
         <label for="local">Local:</label>
         <ValidationProvider name="local" rules="required" v-slot="{ errors }">
             <input class="form-control" v-model="data.place" type="text" id="local" name="local">
             <br>
             <code>{{ errors[0] }}</code>
         </ValidationProvider>
     </div>

    <div class="form-group">
      <label for="information">Informação</label>
        <ValidationProvider name="information" rules="required" v-slot="{ errors }">
      <textarea
          class="form-control"
          id="information"
          v-model="data.information"
          name="information"
          rows="3"
      ></textarea>
            <code>{{ errors[0] }}</code>
        </ValidationProvider>

    </div>

    <div class="form-group p-2">
      <label for="files">Anexos Opcionais</label>
      <div class="field p-1">
        <input type="file" id="files" ref="files" v-on:change="handleFiles()" multiple>
      </div>
    </div>

    <b-button variant="outline-success" type="submit" v-on:click.prevent="handleSubmit(save)">Guardar</b-button>
    <b-button variant="outline-info" v-on:click.prevent="cancel()">Cancelar</b-button>
      </ValidationObserver>
  </div>
</template>
<script>
import loginVue from '../login.vue';
import VueTimepicker from 'vue2-timepicker/src/vue-timepicker.vue';

export default {
    components:{
        VueTimepicker
    },
  props: ["user", "studentCMs"],
  data: function() {
    return {
      data: {
        email: "",
        interactionDate: "",
        service: null,
        information: "",
        place:null,
        contactMedium:null,
        software:null,
        interactionTime:null,
      },

      meeting: {
        info: null,
        date: null
      },
      files: [],
    };
  },
    directives: {
        focus: {
            // directive definition
            inserted: function (el) {
                el.focus()
            }
        }
    },
  methods: {
    cancel() {
      this.resetFields();
      this.$emit("cancel-edit");
    },

    save: function() {
          if(this.contactMedium==="presencial"){
              this.software=null;
          }else if(this.contactMedium==="conferencia"){
              this.place=null;
          }else{
              this.software=null;
              this.place=null;
          }

          this.$emit("save-interaction", this.data, this.files);
    },
    resetFields(){
        this.data.email="";
        this.data.interactionDate="";
        this.data.service=null;
        this.data.place=null;
        this.data.contactMedium=null;
        this.data.software=null;
        this.data.interactionTime=null;
        this.data.information="";
    },
    handleFiles() {
      this.files = [];
      let uploadedFiles = this.$refs.files.files;
      for (var i = 0; i < uploadedFiles.length; i++) {
        this.files.push(uploadedFiles[i]);
      }
    }
  },
  mounted() {}
};
</script>
