import Vue from 'vue';
import Vuex from 'vuex';
import store from './store';
import LoginComponent from './components/LoginComponent.vue';
import vuetify from './plugins/vuetify';
import validation from './plugins/validation';
import Http from './service/http';

Vue.use(validation);
Vue.use(Vuex);

window.$http = new Http(store);

new Vue({
  vuetify,
  store,
  render: (h) => h(LoginComponent),
}).$mount('#app');
