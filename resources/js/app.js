/* eslint-disable no-unused-vars */
/* eslint-disable max-len */
require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
import router from './routes.js';
import store from './vuex.js';
import i18n from './plugins/i18n';
import Toasted from 'vue-toasted';
import {VueSpinners} from '@saeris/vue-spinners';
import DatePicker from 'vue2-datepicker';
import TimeSelector from 'vue-timeselector';
// import VeeValidate from 'vee-validate';
import {ValidationProvider, ValidationObserver, extend} from 'vee-validate';
import VueApexCharts from 'vue-apexcharts';
import feather from 'vue-icon';
import M16yPlugin from 'vue-accessibility-widget';
import VueTimepicker from 'vue2-timepicker';
import JsonExcel from 'vue-json-excel';
// icons
import {library} from '@fortawesome/fontawesome-svg-core';
import {faEye, faHandshake, faEyeSlash, faArrowCircleDown, faBook, faBars} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import 'material-design-icons-iconfont/dist/material-design-icons.css'; // Ensure you are using css-loader


library.add(faEye, faHandshake, faEyeSlash, faArrowCircleDown, faBook, faBars);
Vue.component('font-awesome-icon', FontAwesomeIcon);
// --
import fullCalendar from 'vue-fullcalendar';
import VModal from 'vue-js-modal';
import moment from 'moment';


window.Vue = require('vue');
Vue.use(VueRouter);
Vue.use(feather, 'v-icon');
Vue.use(BootstrapVue);
Vue.use(Toasted);
Vue.use(VueSpinners);
Vue.use(DatePicker);
// Vue.use(VeeValidate, {fieldsBagName: 'formFields'});
Vue.use(TimeSelector);
Vue.use(M16yPlugin);
Vue.use(VueTimepicker);
Vue.use(VModal);
window.Event = new Vue();

Vue.component('downloadExcel', JsonExcel);
Vue.component('vue-layout', require('./layout.vue').default);
Vue.component('apexchart', VueApexCharts);
Vue.component('nav-bar', require('./components/navbar.vue').default);
Vue.component('edit-user', require('./components/admin/edit.vue').default);
Vue.component('edit-support-hours', require('./components/student/editSupportHours.vue').default);
Vue.component('set-cm', require('./components/caseManagerResponsible/setCM.vue').default);
Vue.component('eneeOptions', require('./components/director/eneeOptions.vue').default);
Vue.component('eneeEdit', require('./components/director/editENEE.vue').default);
Vue.component('edit-support', require('./components/admin/editSupport.vue').default);
Vue.component('set-inter', require('./components/caseManager/setInteraction.vue').default);
Vue.component('manage-plan', require('./components/caseManager/managePlan.vue').default);
Vue.component('set-meeting', require('./components/caseManager/setMeeting.vue').default);
Vue.component('interactionsDetails', require('./components/caseManager/interactionsDetails.vue').default);
Vue.component('historial-academico', require('./components/caseManager/historialAcademicoENE.vue').default);
Vue.component('ene-historic', require('./components/caseManager/showENEHistoric.vue').default);
Vue.component('eneeServiceEvaluation', require('./components/services/eneeServiceEvaluation.vue').default);
Vue.component('increase-hours', require('./components/caseManager/increaseSupportHours').default);
Vue.component('show-hours', require('./components/caseManager/studentSupportHours').default);
Vue.component('full-calendar', fullCalendar);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);

// eslint-disable-next-line camelcase
import {required, email, regex, alpha, digits, numeric, alpha_spaces, between, max, min, alpha_num} from 'vee-validate/dist/rules';
import {localize} from 'vee-validate';
import en from 'vee-validate/dist/locale/en.json';
import pt from 'vee-validate/dist/locale/pt_PT.json';

localize({
  en,
  pt,
});

localize('pt');

localize({
  en: {
    names: {
      email: 'E-mail Address',
      password: 'Password',
      responsibleName: 'Responsible Name',
      responsiblePhone: 'Responsible Phone',
      responsibleKin: 'Responsible Kinship',
      responsibleEmail: 'Responsible Email',
      emergencyName: 'Emergency Contact Name',
      emergencyPhone: 'Emergency Contact Phone',
      emergencyKin: 'Emergency Contact Kingship',
      emergencyEmail: 'Emergency Contact Email',
      support: 'Support',
      motivo: 'Reason',
      service: 'Service',
      comment: 'Comment',
      phone: 'Phone Number',
      gender: 'Gender',
      birthDate: 'Birth Date',
      zipCode: 'Zip Code',
      residence: 'Residence',
      area: 'Area',
      identification: 'Identification',
      identificationNumber: 'Identification Number',
      niss: 'Social Security Number',
      nif: 'Taxpayer Identification Number',
      sns: 'National Health System Number',
      curricularYear: 'Curricular Year',
      enrolledYear: 'Enrolled Year',
      studentNumber: 'Student Number',
      studentName: 'Student Name',
      substitute: 'Case Manager Substitute',
      subType: 'Type of Substitution',
      contactMedium: 'Contact Medium',
      decision: 'Decision',
      information: 'Information',
      time: 'Time',
      date: 'Date',
      nextInteraction: 'Next interaction',
      software: 'Software',
      hoursLimit: 'Hours Limit',
      nomeDoApoio: 'Support name',
      categoriaApoio: 'Support Category',
      teachers: 'Teachers',
      duracaoEne: 'Duração do Estatuto de Estudante com Necessidades Específicas',
    },
  },
  pt: {
    names: {
      duracaoEne: 'Duração do Estatuto de Estudante com Necessidades Específicas',
      email: 'Endereço de Email',
      password: 'Palava-Passe',
      responsibleName: 'Nome do responsável',
      responsiblePhone: 'Número do responsável',
      responsibleKin: 'Grau de Parentesco do Responsável',
      responsibleEmail: 'Endereço de Email do Responsável',
      emergencyName: 'Nome do Contacto de Emergência',
      emergencyPhone: 'Número do Contacto de Emergência',
      emergencyKin: 'Grau de parentesco do Contacto de Emergência',
      emergencyEmail: 'Email do Contacto de Emergência',
      support: 'Apoio',
      motivo: 'Motivo',
      comment: 'Comentário',
      service: 'Serviço',
      phone: 'Número de Telefone',
      gender: 'Género',
      birthDate: 'Data de Nascimento',
      zipCode: 'Código Postal',
      residence: 'Residência',
      area: 'Localidade',
      identification: 'Identificação',
      identificationNumber: 'Número de Identificação',
      niss: 'Número de Identificação da Segurança Social',
      nif: 'Número de Identificação Fiscal',
      sns: 'Número do Sistema Nacional de Saúde',
      curricularYear: 'Ano Curricular',
      enrolledYear: 'Ano de Ingresso',
      studentNumber: 'Número de Estudante',
      studentName: 'Nome do Estudante',
      substitute: 'Gestor de Caso Substituto',
      subType: 'Tipo de Substituição',
      contactMedium: 'Meio de Contacto',
      decision: 'Medida',
      information: 'Informação',
      time: 'Hora',
      date: 'Data',
      nextInteraction: 'Próxima interação',
      software: 'Software',
      hoursLimit: 'Limite de Horas',
      nomeDoApoio: 'Nome do Apoio',
      categoriaApoio: 'Categoria do Apoio',
      teachers: 'Professores',
    },
  },
});

extend('email', email);
extend('required', required);
extend('regex', regex);
extend('alpha', alpha);
extend('digits', digits);
extend('numeric', numeric);
extend('alpha_spaces', alpha_spaces);
extend('max', max);
extend('min', min);
extend('alpha_num', alpha_num);
extend('between', between);

extend('isDefault', {
  message: 'Tem de selecionar um Gestor de Caso',
  validate: (value) => {
    return value !== 'default' && value !== '';
  },
});


const app = new Vue({
  el: '#app',
  router,
  store,
  i18n,
  created() {
    this.$store.commit('loadTokenAndUserFromSession');
  },
}).$mount('#app');
