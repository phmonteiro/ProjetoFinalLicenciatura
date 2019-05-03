import VueRouter from 'vue-router';
import store from './vuex.js';

const routes = [
  {
    path: '/',
    component: require('./components/login.vue').default,
  },
  {
    path: '/student',
    component: require('./components/student/dashboard.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/admin',
    component: require('./components/admin/dashboard.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/services',
    component: require('./components/services/dashboard.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/services/contacts/:id',
    component: require('./components/services/contactDetails.vue').default,
    meta: {
      middlewareAuth: true,
    },
    name: 'contactDetails',
  },
  {
    path: '/subscription',
    component: require('./components/student/subscription.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/studentForm',
    component: require('./components/student/noStatusPage.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },

];

const router = new VueRouter({
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.meta.middlewareAuth && (store.state.user == null)) {
    next('/');
    return;
  }
  if (to.fullPath=='/' && store.state.user != null && store.state.user.type=='Estudante') {
    next('/student');
    return;
  }
  if (to.fullPath=='/' && store.state.user != null && store.state.user.type=='Administrator') {
    next('/admin');
    return;
  }
  if (to.fullPath=='/' && store.state.user != null && store.state.user.type=='Services') {
    next('/services');
    return;
  }
  next();
});


export default router;
