function checkResetPasswordTokenValid(token) {
  return window.$http({
    url: '/api/check-reset-password-token',
    method: 'post',
    data: {
      token,
    },
  });
}

export default checkResetPasswordTokenValid;
