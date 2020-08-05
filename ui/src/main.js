import Vue from 'vue';
import Vuex from 'vuex';
import VueCookies from 'vue-cookies';
import App from './App.vue';
import router from './router';
import store from './store';
import vuetify from './plugins/vuetify';
import validation from './plugins/validation';

Vue.config.productionTip = false;

Vue.use(validation);
Vue.use(Vuex);
Vue.use(VueCookies);

new Vue({
  router,
  store,
  vuetify,
  render: (h) => h(App),
}).$mount('#app');
