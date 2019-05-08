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


window.Vue = require('vue');
Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(Toasted);
Vue.use(VueSpinners);
Vue.use(Vuelidate);

window.Event = new Vue();

Vue.component('vue-layout', require('./layout.vue').default);
Vue.component('nav-bar', require('./components/navbar.vue').default);
Vue.component('edit-user', require('./components/admin/edit.vue').default);
Vue.component('meetings', require('./components/services/meetings.vue').default);
Vue.component('enee-list', require('./components/services/eneeList.vue').default);

const app = new Vue({
  el: '#app',
  router,
  store,
  i18n,
  created() {
    this.$store.commit('loadTokenAndUserFromSession');
  },
}).$mount('#app');
