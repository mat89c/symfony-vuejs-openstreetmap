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
    Vue.filter('stripTags', (value) => {
      if (!value) return '';
      return value.replace(/(<([^>]+)>)/ig, '');
    });
    /* eslint-enable no-param-reassign */
  },
};
