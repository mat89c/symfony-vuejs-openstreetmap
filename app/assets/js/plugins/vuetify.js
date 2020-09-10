import Vue from 'vue';
import Vuetify from 'vuetify';
import pl from 'vuetify/src/locale/pl.ts';
import 'vuetify/dist/vuetify.min.css';

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
