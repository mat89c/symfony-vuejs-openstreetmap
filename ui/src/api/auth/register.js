function register(name, email, password) {
  return window.$http({
    url: '/api/register',
    method: 'post',
    data: {
      name,
      email,
      password,
    },
  });
}
export default register;
