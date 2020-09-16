function updateUser(user) {
  return window.$http({
    url: '/user/update',
    method: 'patch',
    data: {
      user,
    },
  });
}

export default updateUser;
