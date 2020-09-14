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
    Vue.filter('roles', (roles) => {
      let role = '';
      if (roles.includes('ROLE_ADMIN')) {
        role = 'Admin';
      }

      if (roles.includes('ROLE_USER')) {
        role = 'Użytkownik';
      }

      return role;
    });
    Vue.filter('stripTags', (value) => {
      if (!value) return '';
      return value.replace(/(<([^>]+)>)/ig, '');
    });
    Vue.filter('trimReview', (value) => {
      if (!value) return '';
      return `${value.substring(0, 20)}...`;
    });
    /* eslint-enable no-param-reassign */
  },
};
