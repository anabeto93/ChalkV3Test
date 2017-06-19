// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';

import App from './App';
import router from './router';
import apolloProvider from './config/apollo';

Vue.config.productionTip = false;

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  apolloProvider,
  template: '<App/>',
  components: { App },
});
