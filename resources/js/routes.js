/* eslint-disable max-len */
import VueRouter from 'vue-router';
import store from './vuex.js';

const routes = [{
  path: '/',
  component: require('./components/login.vue').default,
},
{
  path: '/student',
  component: require('./components/student/dashboard.vue').default,
  name: 'student',
  meta: {
    middlewareAuth: true,
  },
  children: [{
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
    path: '/student/supportHours',
    name: 'supportHours',
    component: require('./components/student/supportHours.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/student/edit',
    name: 'editProfile',
    component: require('./components/student/editProfile.vue').default,
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
  children: [{
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
  },
  ],
},
{
  path: '/director',
  component: require('./components/director/dashboard.vue').default,
  meta: {
    middlewareAuth: true,
  },
  children: [{
    path: '/director/manageRequestENEE',
    name: 'manageRequestENEE',
    component: require('./components/director/manageRequestENEE.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/director/ENEE',
    name: 'manageENEE',
    component: require('./components/director/manageENEE.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  ],
},
{
  path: '/coordinator',
  component: require('./components/coordinator/dashboard.vue').default,
  meta: {
    middlewareAuth: true,
  },
  children: [{
    path: '/coordinator/eneeRequests',
    name: 'eneeRequests',
    component: require('./components/coordinator/eneeRequests.vue').default,
    meta: {
      middlewareAuth: true,
    },
  }],
},
{
  path: '/caseManagerResponsible',
  component: require('./components/caseManagerResponsible/dashboard.vue').default,
  meta: {
    middlewareAuth: true,
  },
  children: [{
    path: '/caseManagerResponsible/cmList',
    name: 'cmList',
    component: require('./components/caseManagerResponsible/cmList.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/caseManagerResponsible/manageCM',
    name: 'manageCM',
    component: require('./components/caseManagerResponsible/manageCM.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  ],
},
{
  path: '/services',
  component: require('./components/services/dashboard.vue').default,
  meta: {
    middlewareAuth: true,
  },
  children: [{
    path: '/services/meetings',
    name: 'meetings',
    component: require('./components/services/meetings.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/services/eneeList',
    name: 'eneeList',
    component: require('./components/services/eneeList.vue').default,
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
    path: '/services/eneeOpinion',
    name: 'eneeOpinion',
    component: require('./components/services/eneeOpinion.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/services/eneeAdd',
    name: 'eneeAdd',
    component: require('./components/services/eneeAdd.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  ],
},
{
  path: '/caseManager',
  component: require('./components/caseManager/dashboard.vue').default,
  meta: {
    middlewareAuth: true,
  },
  children: [
    {
      path: '/caseManager/cmEneeList',
      name: 'cmEneeList',
      component: require('./components/caseManager/cmEneeList.vue').default,
      meta: {
        middlewareAuth: true,
      },
    },
    {
      path: '/caseManager/meetingsEneeList',
      name: 'meetingsEneeList',
      component: require('./components/caseManager/meetingsEneeList.vue').default,
      meta: {
        middlewareAuth: true,
      },
    },
    {
      path: '/caseManager/statistics',
      name: 'statistics',
      component: require('./components/caseManager/statistics.vue').default,
      meta: {
        middlewareAuth: true,
      },
    },
  ],
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
  if (to.fullPath == '/' && store.state.user != null && store.state.user.type == 'Estudante') {
    next('/student');
    return;
  }
  if (to.fullPath == '/' && store.state.user != null && store.state.user.type == 'Administrator') {
    next('/admin');
    return;
  }
  if (to.fullPath == '/' && store.state.user != null && store.state.user.type == 'Services' && store.state.user.type == 'SAPE' &&
  store.state.user.type == 'SAS' &&
  store.state.user.type == 'CRID' &&
  store.state.user.type == 'UED' &&
  store.state.user.type == 'DST' &&
  store.state.user.type == 'SA') {
    next('/services');
    return;
  }
  if (to.fullPath == '/' && store.state.user != null && store.state.user.type == 'Director') {
    next('/director');
    return;
  }
  if (to.fullPath == '/' && store.state.user != null && store.state.user.type == 'CaseManagerResponsible') {
    next('/CaseManagerResponsible');
    return;
  }
  if (to.fullPath == '/' && store.state.user != null && store.state.user.type == 'Coordinator') {
    next('/coordinator');
    return;
  }
  if (to.fullPath == '/' && store.state.user != null && store.state.user.type == 'CaseManager') {
    next('/caseManager');
    return;
  }
  next();
});


export default router;
