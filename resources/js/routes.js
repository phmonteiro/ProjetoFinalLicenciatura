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
    children: [
      {
        path: '/student/contact',
        name: 'contact',
        component: require('./components/student/contact.vue').default,
        meta: {
          middlewareAuth: true,
        },
      },
      {
        path: '/student/myMeetings',
        name: 'myMeetings',
        component: require('./components/student/myMeetings.vue').default,
        meta: {
          middlewareAuth: true,
        },
      },
      {
        path: '/student/serviceRequest',
        name: 'serviceRequest',
        component: require('./components/student/serviceRequest.vue').default,
        meta: {
          middlewareAuth: true,
        },
      },
      {
        path: '/student/setMeeting',
        name: 'setMeeting',
        component: require('./components/student/setMeeting.vue').default,
        meta: {
          middlewareAuth: true,
        },
      },
      {
        path: '/student/usedServices',
        name: 'usedServices',
        component: require('./components/student/usedServices.vue').default,
        meta: {
          middlewareAuth: true,
        },
      },
    ],
  },
  {
    path: '/studentForm',
    component: require('./components/student/noStatusPage.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {

    path: '/subscription',
    component: require('./components/student/subscription.vue').default,
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
    children: [
      {
        path: '/admin/manageSupports',
        name: 'manageSupports',
        component: require('./components/admin/manageSupports.vue').default,
        meta: {
          middlewareAuth: true,
        },
      },
      {
        path: '/admin/editUsers',
        name: 'editUsers',
        component: require('./components/admin/editUsers.vue').default,
        meta: {
          middlewareAuth: true,
        },
      }
    ],
  },
  {
    path: '/director',
    component: require('./components/director/dashboard.vue').default,
    meta: {
      middlewareAuth: true,
    },
    children: [
      {
        path: '/director/manageCM',
        name: 'manageCM',
        component: require('./components/director/manageCM.vue').default,
        meta: {
          middlewareAuth: true,
        },
      },
      {
        path: '/director/manageENEE',
        name: 'manageENEE',
        component: require('./components/director/manageENEE.vue').default,
        meta: {
          middlewareAuth: true,
        },
      }
    ],
  },
  {
    path: '/caseManagerResponsible',
    component: require('./components/caseManagerResponsible/dashboard.vue').default,
    meta: {
      middlewareAuth: true,
    },
    children: [
      {
        path: '/caseManagerResponsible/cmList',
        name: 'cmList',
        component: require('./components/caseManagerResponsible/cmList.vue').default,
        meta: {
          middlewareAuth: true,
        },
      },
      {
        path: '/caseManagerResponsible/cmForApproval',
        name: 'cmForApproval',
        component: require('./components/caseManagerResponsible/cmForApproval.vue').default,
        meta: {
          middlewareAuth: true,
        },
      }
    ],
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
  if (to.fullPath=='/' && store.state.user != null && store.state.user.type=='Director') {
    next('/director');
    return;
  }
  if (to.fullPath=='/' && store.state.user != null && store.state.user.type=='CaseManagerResponsible') {
    next('/CaseManagerResponsible');
    return;
  }
  next();
});


export default router;
