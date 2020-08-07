import Vue from 'vue';
import Vuex from 'vuex';
import user from '@/store/modules/user.module';
import notificationbar from '@/store/modules/notificationbar.module';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    user,
    notificationbar,
  },
});
