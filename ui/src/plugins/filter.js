export default {
  install(Vue) {
    /* eslint-disable no-param-reassign */
    Vue.filter('date', (value) => {
      if (value) {
        const date = new Date(value.date);
        const formatedDate = date.toLocaleDateString();
        return formatedDate;
      }
      return '';
    });
    /* eslint-enable no-param-reassign */
  },
};
