function getUser() {
  return window.$http({
    url: '/api/get-user',
    method: 'get',
  });
}

export default getUser;
