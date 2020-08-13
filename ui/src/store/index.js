import Vue from 'vue';
import Vuex from 'vuex';
import user from '@/store/modules/user.module';
import notificationbar from '@/store/modules/notificationbar.module';
import navigation from '@/store/modules/navigation.module';
import dialogpopup from '@/store/modules/dialogpopup.module';
import dialogloader from '@/store/modules/dialogloader.module';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    user,
    notificationbar,
    navigation,
    dialogpopup,
    dialogloader,
  },
});
