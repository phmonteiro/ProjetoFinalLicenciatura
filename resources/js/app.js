require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue'
import router from './routes.js';
import store from './vuex.js';
import i18n from './plugins/i18n';

window.Vue = require('vue');
Vue.use(VueRouter);
Vue.use(BootstrapVue);

window.Event = new Vue();

Vue.component('vue-layout', require('./layout.vue').default);
Vue.component('nav-bar', require('./components/navbar.vue').default);


const app = new Vue({
    el: '#app',
    router,
    store,
    i18n,
    created() {
        this.$store.commit('loadTokenAndUserFromSession');
    }
}).$mount('#app');