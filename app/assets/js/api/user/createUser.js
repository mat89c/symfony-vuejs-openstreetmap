function createUser(user) {
  return window.$http({
    url: '/user/create',
    method: 'post',
    data: {
      user,
    },
  });
}

export default createUser;
