import Vue from 'vue';
import Vuex from 'vuex';
import dialogloader from './modules/dialogloader.module';
import notificationbar from './modules/notificationbar.module';
import navigation from './modules/navigation.module';
import auth from './modules/auth.module';
import dialogpopup from './modules/dialogpopup.module';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    dialogloader,
    notificationbar,
    navigation,
    auth,
    dialogpopup,
  },
});
