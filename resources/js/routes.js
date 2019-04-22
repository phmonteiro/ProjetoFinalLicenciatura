import VueRouter from 'vue-router';
import store from './vuex.js';

let routes = [
    {
        path: '/',
        component: require('./components/login.vue').default
    },
    {
        path: '/student',
        component: require('./components/student/dashboard.vue').default,
        meta: {
            middlewareAuth: true
        },
    },
    {
        path: '/admin',
        component: require('./components/admin/dashboard.vue').default,
        meta: {
            middlewareAuth: true
        }
    },
    {
        path: '/services',
        component: require('./components/services/dashboard.vue').default,
        meta: {
            middlewareAuth: true
        },
    }
];

const router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {
    if (to.meta.middlewareAuth && (store.state.user == null)) {
        next('/');
        return;
    }
    next();
});

export default router;
