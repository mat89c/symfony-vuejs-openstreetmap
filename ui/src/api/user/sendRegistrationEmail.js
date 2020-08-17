function sendRegistrationEmail(email) {
  return window.$http({
    url: '/api/send-registration-email',
    method: 'post',
    data: {
      email,
    },
  });
}

export default sendRegistrationEmail;
