import Vue from 'vue';
import Vuetify from 'vuetify/lib';
import pl from 'vuetify/src/locale/pl.ts';

Vue.use(Vuetify);

export default new Vuetify({
  lang: {
    locales: { pl },
    current: 'pl',
  },
  theme: {
    dark: true,
  },
});
