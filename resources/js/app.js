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
import VeeValidate from 'vee-validate';
import VueApexCharts from 'vue-apexcharts';
import feather from 'vue-icon';
import M16yPlugin from 'vue-accessibility-widget';

// icons
import {library} from '@fortawesome/fontawesome-svg-core';
import {faEye, faHandshake, faEyeSlash, faArrowCircleDown, faBook, faBars} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import 'material-design-icons-iconfont/dist/material-design-icons.css'; // Ensure you are using css-loader


library.add(faEye, faHandshake, faEyeSlash, faArrowCircleDown, faBook, faBars);
Vue.component('font-awesome-icon', FontAwesomeIcon);
// --
import fullCalendar from 'vue-fullcalendar';

Vue.component('full-calendar', fullCalendar);

window.Vue = require('vue');
Vue.use(VueRouter);
Vue.use(feather, 'v-icon');
Vue.use(BootstrapVue);
Vue.use(Toasted);
Vue.use(VueSpinners);
Vue.use(DatePicker);
Vue.use(VeeValidate, {fieldsBagName: 'formFields'});
Vue.use(TimeSelector);
Vue.use(M16yPlugin);
window.Event = new Vue();

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
Vue.component('eneeServiceEvaluation', require('./components/services/eneeServiceEvaluation.vue').default);
Vue.component('increase-hours', require('./components/caseManager/increaseSupportHours').default);

const app = new Vue({
  el: '#app',
  router,
  store,
  i18n,
  created() {
    this.$store.commit('loadTokenAndUserFromSession');
  },
}).$mount('#app');
