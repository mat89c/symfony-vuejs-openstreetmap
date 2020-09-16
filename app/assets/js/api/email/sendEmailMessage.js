function sendEmailMessage(message) {
  return window.$http({
    url: '/email/send-message',
    method: 'post',
    data: {
      message,
    },
  });
}

export default sendEmailMessage;
