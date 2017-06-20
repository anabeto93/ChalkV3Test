import Vue from 'vue';
import Router from 'vue-router';

import CoursesList from '@/views/Course/ListView';
import CourseView from '@/views/Course/View';
import Home from '@/views/HomeView';

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/courses/list',
      name: 'coursesList',
      component: CoursesList,
    },
    {
      path: '/courses/view/:uuid',
      name: 'courseView',
      component: CourseView,
    },
  ],
});
