import Vue from 'vue';
import Vuex from 'vuex';

// Remove data fixtures when API was fully plugged
import data from '../data/data';

import apolloClient from '../config/apolloClient';
import CoursesQuery from '../graphql/query/CoursesQuery';
import CourseQuery from '../graphql/query/CourseQuery';
// import CategoriesQuery from '../graphql/query/CategoriesQuery';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    courses: {},
    course: {},
    categories: {},
  },
  mutations: {
    SET_COURSES(state, courses) {
      state.courses = courses;
      const object = {};
      state.courses.map((course) => {
        object[course.uuid] = course;
        return object;
      });
      state.courses = object;
    },
    SET_COURSE(state, course, uuid) {
      state.courses[uuid] = course;
    },
    SET_CATEGORIES(state, categories) {
      state.categories = categories;
      const object = {};
      state.categories.map((category) => {
        object[category.uuidCourse] = category;
        return object;
      });
    },
  },
  actions: {
    GET_COURSES(context) {
      apolloClient.query({
        query: CoursesQuery,
      }).then((result) => {
        context.commit('SET_COURSES', result.data.courses);
      });
    },
    GET_COURSE(context, uuid) {
      apolloClient.query({
        query: CourseQuery,
        variables: {
          uuid,
        },
      }).then((result) => {
        context.commit('SET_COURSE', result.data.course, result.data.course.uuid);
      });
    },
    GET_CATEGORIES(context/* , uuidCourse */) {
      context.commit('SET_CATEGORIES', data.courses[0].categories);
      /* UnComment the following when API be plugged
      apolloClient.query({
        query: CategoriesQuery,
        variables: {
          uuidCourse,
        },
      }).then((result) => {
        context.commit('SET_CATEGORIES', result.data.categories);
      }); */
    },
  },
});
