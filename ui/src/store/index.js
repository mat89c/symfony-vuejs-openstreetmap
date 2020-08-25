import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';
import user from '@/store/modules/user.module';
import notificationbar from '@/store/modules/notificationbar.module';
import navigation from '@/store/modules/navigation.module';
import dialogpopup from '@/store/modules/dialogpopup.module';
import dialogloader from '@/store/modules/dialogloader.module';
import map from '@/store/modules/map.module';
import mapmarker from '@/store/modules/mapmarker.module';
import point from '@/store/modules/point.module';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    user,
    notificationbar,
    navigation,
    dialogpopup,
    dialogloader,
    map,
    mapmarker,
    point,
  },
  plugins: [
    createPersistedState({
      paths: ['user'],
      key: 'user',
    }),
  ],
});
