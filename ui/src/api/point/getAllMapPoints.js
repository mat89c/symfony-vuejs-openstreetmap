function getAllMapPoints() {
  return window.$http({
    url: '/api/points',
    method: 'get',
  });
}

export default getAllMapPoints;
