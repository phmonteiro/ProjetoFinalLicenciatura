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
import Vuelidate from 'vuelidate';
import DatePicker from 'vue2-datepicker';
// icons
import {library} from '@fortawesome/fontawesome-svg-core';
import {faEye, faHandshake, faEyeSlash, faArrowAltCircleDown} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

import VueApexCharts from 'vue-apexcharts';
library.add(faEye, faHandshake, faEyeSlash, faArrowAltCircleDown);
Vue.component('font-awesome-icon', FontAwesomeIcon);
// --


window.Vue = require('vue');
Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(Toasted);
Vue.use(VueSpinners);
Vue.use(Vuelidate);
Vue.use(DatePicker);
window.Event = new Vue();

Vue.component('vue-layout', require('./layout.vue').default);
Vue.component('apexchart', VueApexCharts);
Vue.component('nav-bar', require('./components/navbar.vue').default);
Vue.component('edit-user', require('./components/admin/edit.vue').default);
Vue.component('edit-support-hours', require('./components/student/editSupportHours.vue').default);
// Vue.component('meetings', require('./components/services/meetings.vue').default);
// Vue.component('enee-list', require('./components/services/eneeList.vue').default);
Vue.component('set-cm', require('./components/caseManagerResponsible/setCM.vue').default);
Vue.component('eneeOptions', require('./components/director/eneeOptions.vue').default);
Vue.component('eneeEdit', require('./components/director/editENEE.vue').default);
Vue.component('edit-support', require('./components/admin/editSupport.vue').default);
Vue.component('set-inter', require('./components/caseManager/setInteraction.vue').default);
Vue.component('interactionsDetails', require('./components/caseManager/interactionsDetails.vue')).default;


const app = new Vue({
  el: '#app',
  router,
  store,
  i18n,
  created() {
    this.$store.commit('loadTokenAndUserFromSession');
  },
}).$mount('#app');
