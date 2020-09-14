function deleteUser(id) {
  return window.$http({
    url: '/user/delete',
    method: 'delete',
    data: {
      id,
    },
  });
}

export default deleteUser;
