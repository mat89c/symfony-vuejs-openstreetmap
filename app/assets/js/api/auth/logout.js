function logout() {
  return window.$http({
    url: '/logout',
    method: 'get',
  });
}

export default logout;
