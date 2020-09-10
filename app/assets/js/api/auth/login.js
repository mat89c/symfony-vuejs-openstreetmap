function login(email, password) {
  return window.$http({
    url: '/login',
    method: 'post',
    data: {
      email,
      password,
    },
  });
}

export default login;
