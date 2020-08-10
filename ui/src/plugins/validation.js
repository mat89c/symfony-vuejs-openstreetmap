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
      minLength: [
        (v) => v.length >= 6 || 'Hasło powinno zawierać co najmniej 6 znaków.',
      ],
    };
    /* eslint-enable no-param-reassign */
  },
};
