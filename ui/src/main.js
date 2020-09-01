import Vue from 'vue';
import Vuex from 'vuex';
import CKEditor from '@ckeditor/ckeditor5-vue';
import VueLazyLoad from 'vue-lazyload';
import Http from './service/http';
import 'leaflet/dist/leaflet.css';
import App from './App.vue';
import router from './router';
import store from './store';
import vuetify from './plugins/vuetify';
import validation from './plugins/validation';
import filter from './plugins/filter';
import '@/assets/scss/main.scss';

Vue.config.productionTip = false;

Vue.use(validation);
Vue.use(filter);
Vue.use(Vuex);
Vue.use(CKEditor);
Vue.use(VueLazyLoad);

window.$http = new Http(store, router);

new Vue({
  router,
  store,
  vuetify,
  render: (h) => h(App),
}).$mount('#app');
