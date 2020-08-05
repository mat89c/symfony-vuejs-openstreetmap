export default {
  install(Vue) {
    /* eslint-disable no-param-reassign */
    Vue.prototype.$rules = {
      required: [
        (v) => !!v || 'Pole jest wymagane.',
      ],
    };
    /* eslint-enable no-param-reassign */
  },
};
