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
      postcode: [
        (v) => /^[0-9]{2}-[0-9]{3}$/.test(v) || 'Nieprawidłowy format kodu pocztowego.',
      ],
      logoSize: [
        (file) => (file && file.size < 2097152) || 'Wielkość pliku nie powinna przekraczać 2MB.',
      ],
      imagesSize: [
        (files) => !files || !files.some((file) => file.size > 2097152) || 'Wielkość każdego pliku nie powinna przekraczać 2MB.',
      ],
      comboboxRequired: [
        (v) => v.length !== 0 || 'Proszę wybrać kategorię.',
      ],
    };
    /* eslint-enable no-param-reassign */
  },
};
