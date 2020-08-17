function sendResetPasswordEmail(email) {
  return window.$http({
    url: '/api/send-reset-password-email',
    method: 'post',
    data: {
      email,
    },
  });
}

export default sendResetPasswordEmail;
