function searchMapPointByIdOrName(value) {
  return window.$http({
    url: '/point/search',
    methods: 'get',
    params: {
      value,
    },
  });
}

export default searchMapPointByIdOrName;
