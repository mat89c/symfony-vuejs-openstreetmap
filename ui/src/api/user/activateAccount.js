function activateAccount(token) {
  return window.$http({
    url: '/api/activate-account',
    method: 'patch',
    data: {
      token,
    },
  });
}

export default activateAccount;
