export default {
  install(Vue) {
    /* eslint-disable no-param-reassign */
    Vue.prototype.$rules = {
      required: [
        (v) => !!v || 'Pole jest wymagane.',
      ],
      email: [
        (v) => /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(v) || 'Nieprawidłowy adres e-mail.',
      ],
      imagesSize: [
        (files) => !files || !files.some((file) => file.size > 2097152) || 'Wielkość każdego pliku nie powinna przekraczać 2MB.',
      ],
    };
    /* eslint-enable no-param-reassign */
  },
};
