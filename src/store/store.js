import Vue from 'vue';
import Vuex from 'vuex';

import apolloClient from '../config/apolloClient';
import CoursesQuery from '../graphql/query/CoursesQuery';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    courses: {},
  },
  mutations: {
    SET_COURSES(state, courses) {
      state.courses = courses;
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
  },
});
