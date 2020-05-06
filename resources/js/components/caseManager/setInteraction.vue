<template>
  <div class="container mt-3">
    <div class="form-group">
      <h4>Criar nova interação</h4>
      <label for="inputName">{{this.$t('nome')}}</label>
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
            <label for="decision">{{this.$t('data_interação')}}:</label>
            <date-picker v-validate="'date_format:yyyy-mm-dd'" type="date" ref="interactionDate" name="interactionDate" id="interactionDate" v-model="data.interactionDate" valueType="format" lang="pt-br"></date-picker>
            <code>Caso nao especificado, a data de hoje será assumida.</code>
            <br><br>
            <label for="decision">{{this.$t('data_interação')}} ({{this.$t('opcional')}}): </label>
            <input type="time" v-model="data.interactionTime" valueType="format" lang="pt-br">
        </div>

    </div>


    <div class="form-group">
      <label for="decision">Servico:</label>
      <b-form-select v-model="data.service" class="mb-3">
        <template slot="first">
          <option :value="null" disabled>-- {{this.$t('selecionar_serviço')}} --</option>
        </template>

        <option value="SAPE">SAPE</option>
        <option value="SAS">SAS</option>
        <option value="Escola">{{this.$t('escola')}}</option>
        <option value="Biblioteca">{{this.$t('biblioteca')}}</option>
        <option value="Direção">{{this.$t('direção')}}</option>
<!--        <option value="Professor-Tutor">Professor-Tutor</option>-->
        <option value="Gestor-Caso">{{this.$t('gestor_caso')}}</option>
      </b-form-select>
    </div>

      <div class="form-group">
          <label>Meio de Contacto:</label>
          <b-form-select name="contactMedium" id="contactMedium" v-validate="'required'" v-model="data.contactMedium" class="mb-3">
              <template slot="first">
                  <option :value="null" disabled>-- {{this.$t('selecione_meio_contacto')}} --</option>
              </template>

              <option value="conferencia">Video Conferência</option>
              <option value="presencial">Presencial</option>
              <option value="telefone">Telefone</option>
          </b-form-select>
            <code v-if="data.contactMedium==null">{{this.$t('falta_meio_contacto')}}</code>
      </div>

      <div class="form-group" v-if="data.contactMedium==='conferencia'">
          <label for="local">Software:</label>       <code>{{this.$t('indique_software_interação')}}.</code>
          <input class="form-control" v-model="data.software" type="text" id="software" name="software">
      </div>

     <div class="form-group" v-if="data.contactMedium==='presencial'">
         <label for="local">Local:</label>       <code>{{this.$t('indique_local_interação')}}.</code>
         <input class="form-control" v-model="data.place" type="text" id="local" name="local">
     </div>

    <div class="form-group">
      <label for="decision">Medida</label>
      <textarea class="form-control" id="decision" v-model="data.decision" name="decision" rows="3"></textarea>
    </div>

    <div class="form-group">
      <label for="information">{{this.$t('informação')}}</label>
      <textarea
        class="form-control"
        id="information"
        v-model="data.information"
        name="information"
        rows="3"
      ></textarea>
    </div>

    <div class="form-group">
      <label for="nextInteraction">Proxima interação:</label>
      <date-picker type="date" v-validate="'date_format:yyyy-mm-dd|after:interactionDate'" name="nextInteraction" id="nextInteraction" v-model="data.nextInteraction" valueType="format" lang="pt-br"></date-picker>
        <code v-if="data.errorData">A data introduzida tem que ser maior que a data da primeira interação.</code>
    </div>

    <div class="form-group p-2">
      <label for="files">Anexos Opcionais</label>
      <div class="field p-1">
        <input type="file" id="files" ref="files" v-on:change="handleFiles()" multiple>
      </div>
    </div>

    <b-button variant="outline-success" v-on:click.prevent="save()">Guardar</b-button>
    <b-button variant="outline-info" v-on:click.prevent="cancel()">Cancelar</b-button>
  </div>
</template>
<script>
import loginVue from '../login.vue';
export default {
  props: ["user", "studentCMs"],
  data: function() {
    return {
      data: {
        email: "",
        interactionDate: "",
        nextInteraction: "",
        service: "",
        decision: "",
        information: "",
        place:null,
        contactMedium:null,
        software:null,
        interactionTime:null,
        errorData:null,
      },

      meeting: {
        info: null,
        date: null
      },
      files: [],
    };
  },
  methods: {
    cancel() {
      this.$emit("cancel-edit");
    },
      validateDate(){

        if(this.data.nextInteraction>this.data.interactionDate){
            return true;
        }else{
            return false;
        }
     },
    save: function() {
      if(this.validateDate()) {
          this.data.errorData=false;
          console.log(this.data.nextInteraction);

          if(this.contactMedium==="presencial"){
              this.software=null;
          }else if(this.contactMedium==="conferencia"){
              this.place=null;
          }else{
              this.software=null;
              this.place=null;
          }

          this.$emit("save-interaction", this.data, this.files);
      }else{
          this.data.errorData = true;
      }
      //this.data = Object.assign({}, {});
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
