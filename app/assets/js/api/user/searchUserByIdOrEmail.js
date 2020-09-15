function searchUserByIdOrEmail(value) {
  return window.$http({
    url: '/user/search',
    method: 'get',
    params: {
      value,
    },
  });
}

export default searchUserByIdOrEmail;
