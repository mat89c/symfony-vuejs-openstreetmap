function getDashboarData() {
  return window.$http({
    url: '/get-dashboard-data',
    method: 'get',
  });
}

export default getDashboarData;
