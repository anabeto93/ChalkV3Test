import Vue from 'vue';
import Vuex from 'vuex';

import apolloClient from '../config/apolloClient';
import CoursesQuery from '../graphql/query/CoursesQuery';
import CourseQuery from '../graphql/query/CourseQuery';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    courses: {},
    course: {},
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
  },
});
