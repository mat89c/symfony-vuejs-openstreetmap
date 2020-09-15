import Vue from 'vue';
import Vuex from 'vuex';
import CKEditor from '@ckeditor/ckeditor5-vue';
import VueLazyLoad from 'vue-lazyload';
import App from './App.vue';
import router from './router';
import store from './store';
import vuetify from './plugins/vuetify';
import validation from './plugins/validation';
import filter from './plugins/filter';
import Http from './service/http';

Vue.use(validation);
Vue.use(filter);
Vue.use(Vuex);
Vue.use(CKEditor);
Vue.use(VueLazyLoad);

window.$http = new Http(store);

new Vue({
  router,
  vuetify,
  store,
  beforeMount() {
    this.$store.dispatch('auth/setUser', JSON.parse(this.$el.attributes['data-user'].value));
  },
  render: (h) => h(App),
}).$mount('#app');
