import Vue from 'vue';
import Vuex from 'vuex';
import dialogloader from './modules/dialogloader.module';
import notificationbar from './modules/notificationbar.module';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    dialogloader,
    notificationbar,
  },
});
