/*jshint esversion: 6 */

import Vue from 'vue';
import Vuex from 'vuex';
import VuexPersist from 'vuex-persist';


Vue.use(Vuex);

const vuexSessionStorage = new VuexPersist({
    key: 'vuex', // The key to store the state on in the storage provider.
    storage: window.sessionStorage, // or window.sessionStorage or localForage
    // Function that passes the state and returns the state with only the objects you want to store.
    // reducer: state => state,
    // Function that passes a mutation and lets you decide if it should update the state in sessionStorage.
    // filter: mutation => (true)
  })

const store = new Vuex.Store({
    state: {
        token: "",
        user: null,
        languagePref: 'en'
    },
    mutations: {
        clearUserAndToken: (state) => {
            state.user = null;
            state.token = "";
            sessionStorage.removeItem('user');
            sessionStorage.removeItem('token');
            axios.defaults.headers.common.Authorization = undefined;
        },
        clearUser: (state) => {
            state.user = null;
            sessionStorage.removeItem('user');
        },
        clearToken: (state) => {
            state.token = "";
            sessionStorage.removeItem('token');
            axios.defaults.headers.common.Authorization = undefined;
        },
        setUser: (state, user) => {
            state.user = user;
            sessionStorage.setItem('user', JSON.stringify(user));
        },
        setToken: (state, token) => {
            state.token = token;
            sessionStorage.setItem('token', token);
            axios.defaults.headers.common.Authorization = "Bearer " + token;
        },
        loadTokenAndUserFromSession: (state) => {
            state.token = "";
            state.user = null;
            let token = sessionStorage.getItem('token');
            let user = sessionStorage.getItem('user');
            if (token) {
                state.token = token;
                axios.defaults.headers.common.Authorization = "Bearer " + token;
            }
            if (user) {
                state.user = JSON.parse(user);
            }
        },
        getUser: (state) => {
            axios.get('/api/get-user').then(response => {
                state.user = response.data.data;
            }).catch(response => {
                if (response.status === 401) {
                    this.logout();
                }
            })
        },

        check(state) {
            return !!state.token;
        },

        setLang: (state, lang) => {
            state.languagePref = lang;
            localStorage.setItem('lang', lang);
        }
    },
    plugins: [vuexSessionStorage.plugin]

});

export default store;
