import Vue from 'vue';
import VueApollo from 'vue-apollo';
import apolloClient from './apolloClient';

export default new VueApollo({
  defaultClient: apolloClient,
});

Vue.use(VueApollo);
