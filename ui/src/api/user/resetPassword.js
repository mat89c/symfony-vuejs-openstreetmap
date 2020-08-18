function resetPassword(token, password) {
  return window.$http({
    url: '/api/reset-password',
    method: 'patch',
    data: {
      token,
      password,
    },
  });
}

export default resetPassword;
