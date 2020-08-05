import http from '@/service/http';

function login(username, password) {
  return http({
    url: '/api/login_check',
    method: 'post',
    data: {
      username,
      password,
    },
  });
}
export default login;
