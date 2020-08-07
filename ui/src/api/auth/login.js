function login(username, password) {
  return window.$http({
    url: '/api/login_check',
    method: 'post',
    data: {
      username,
      password,
    },
  });
}
export default login;
