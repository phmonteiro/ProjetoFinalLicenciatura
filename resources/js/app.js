require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
import router from './routes.js';
import store from './vuex.js';
import i18n from './plugins/i18n';
import Toasted from 'vue-toasted';
import {VueSpinners} from '@saeris/vue-spinners';


window.Vue = require('vue');
Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(Toasted);
Vue.use(VueSpinners);

window.Event = new Vue();

Vue.component('vue-layout', require('./layout.vue').default);

Vue.component('nav-bar', require('./components/navbar.vue').default);

Vue.component('edit-user', require('./components/admin/edit.vue').default);

Vue.component('meetings', require('./components/services/meetings.vue').default);
Vue.component('enee-list', require('./components/services/eneeList.vue').default);

// eslint-disable-next-line no-unused-vars
const app = new Vue({
  el: '#app',
  router,
  store,
  i18n,
  created() {
    this.$store.commit('loadTokenAndUserFromSession');
  },
}).$mount('#app');
