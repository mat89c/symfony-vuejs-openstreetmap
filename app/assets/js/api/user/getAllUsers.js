function getAllUsers(page, status) {
  return window.$http({
    url: '/users',
    method: 'get',
    params: {
      page,
      status,
    },
  });
}

export default getAllUsers;
