// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import { ApolloClient, createNetworkInterface } from 'apollo-client';
import VueApollo from 'vue-apollo';

import App from './App';
import router from './router';

const apolloClient = new ApolloClient({
  networkInterface: createNetworkInterface({
    uri: 'http://api.chalkboardeducation.dev/api/graphql/', // don't forget to set last slash to the uri for prevent redirect
    transportBatching: true,
  }),
  connectDevTools: true,
});

const apolloProvider = new VueApollo({
  defaultClient: apolloClient,
});

Vue.use(VueApollo);

Vue.config.productionTip = false;

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  apolloProvider,
  template: '<App/>',
  components: { App },
});
