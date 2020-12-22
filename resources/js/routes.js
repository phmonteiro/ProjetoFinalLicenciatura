/* eslint-disable max-len */
import VueRouter from 'vue-router';
import store from './vuex.js';

const routes = [{
  path: '/',
  component: require('./components/login.vue').default,
},
// STUDENT
{
  path: '/student',
  component: require('./components/student/dashboard.vue').default,
  name: 'student',
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
      path: '/student/supportHoursRequestsList',
      name: 'supportHoursRequestsList',
      component: require('./components/student/supportHoursRequestsList.vue').default,
      meta: {
        middlewareAuth: true,
      },
    },
    {
      path: '/student/transferAccountStatus',
      name: 'transferAccountStatus',
      component: require('./components/student/transferAccountStatus.vue').default,
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
    //  TEMPORARY FUNCTION
    {
      path: '/student/tempEditProfile',
      name: 'tempEditProfile',
      component: require('./components/student/temporaryEditProfile.vue').default,
      meta: {
        middlewareAuth: true,
      },
    },
    //   END TEMPORARY FUNCTION

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
  path: '/transferAccountPage',
  component: require('./components/student/transferAccountPage.vue').default,
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
// ADMIN
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
    path: '/admin/defineSupportHours',
    name: 'definirHorasApoio',
    component: require('./components/admin/definirHorasApoio.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/admin/editDirectors',
    name: 'editDirectors',
    component: require('./components/admin/addDirector.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/admin/addService',
    name: 'addService',
    component: require('./components/admin/addService.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/admin/configs',
    name: 'configs',
    component: require('./components/admin/webServiceManagement.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/admin/addResponsibleCM',
    name: 'addResponsibleCM',
    component: require('./components/admin/addResponsibleCM.vue').default,
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
  {
    path: '/admin/addCoordinator',
    name: 'addCoordinator',
    component: require('./components/admin/addCoordinator.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  ],
},
// DIRECTOR
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
// COORDINATOR
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
  },
  {
    path: '/coordinator/editProfile',
    name: 'editProfileCC',
    component: require('./components/coordinator/editProfile.vue').default,
    meta: {
      middlewareAuth: true,
    },
  }],
},
//  TEACHER
{
  path: '/teacher',
  component: require('./components/teacher/dashboard.vue').default,
  meta: {
    middlewareAuth: true,
  },
  children: [{
    path: '/teacher/supportRequests',
    name: 'teacherSupportRequests',
    component: require('./components/teacher/supportRequests.vue').default,
    meta: {
      middlewareAuth: true,
    },
  }],
},
// CASE MANAGER RESPONSIBLE
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
    path: '/caseManagerResponsible/addCMtoENE',
    name: 'addCaseManagerToENE',
    component: require('./components/caseManagerResponsible/addCaseManagerToENE.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/caseManagerResponsible/deactivateCM',
    name: 'deactivateCM',
    component: require('./components/caseManagerResponsible/deactivateCM.vue').default,
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
  {
    path: '/caseManagerResponsible/listCMs',
    name: 'listCMs',
    component: require('./components/caseManagerResponsible/listCMs.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/caseManagerResponsible/addCM',
    name: 'addCM',
    component: require('./components/caseManagerResponsible/addCM.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  {
    path: '/caseManagerResponsible/subHistory',
    name: 'subHistory',
    component: require('./components/caseManagerResponsible/subHistory.vue').default,
    meta: {
      middlewareAuth: true,
    },
  },
  ],
},
// SERVICES
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
// CASE MANAGER
{
  path: '/caseManager',
  component: require('./components/caseManager/dashboard.vue').default,
  meta: {
    middlewareAuth: true,
  },
  children: [
    {
      path: '/caseManager/supportHoursRequests',
      name: 'supportHoursRequests',
      component: require('./components/caseManager/supportHoursRequests.vue').default,
      meta: {
        middlewareAuth: true,
      },
    },
    {
      path: '/caseManager/addENE',
      name: 'addENE',
      component: require('./components/services/eneeAdd.vue').default,
      meta: {
        middlewareAuth: true,
      },
    },
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
      path: '/caseManager/substitution',
      name: 'substitution',
      component: require('./components/caseManager/substitution.vue').default,
      meta: {
        middlewareAuth: true,
      },
    },
    {
      path: '/director/supportRequests',
      name: 'supportRequests',
      component: require('./components/director/supportRequests.vue').default,
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
    {
      path: '/caseManager/calendar',
      name: 'calendar',
      component: require('./components/caseManager/calendar.vue').default,
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
  if (to.fullPath == '/' && store.state.user != null && store.state.user.type == 'Administrador') {
    next('/admin');
    return;
  }
  if (to.fullPath == '/' && store.state.user != null && (store.state.user.type == 'Services' || store.state.user.type == 'SAPE' ||
  store.state.user.type == 'SAS' ||
  store.state.user.type == 'CRID'||
  store.state.user.type == 'UED' ||
  store.state.user.type == 'DST' ||
  store.state.user.type == 'SA')) {
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
  if (to.fullPath == '/' && store.state.user != null && store.state.user.type == 'Teacher') {
    next('/teacher');
    return;
  }
  next();
});


export default router;
