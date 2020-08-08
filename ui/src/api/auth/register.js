function register(username, password) {
  window.$http({
    url: 'api/register',
    method: 'post',
    data: {
      username,
      password,
    },
  });
}
export default register;
