function getAllMapPoints(page, status) {
  return window.$http({
    url: '/points',
    method: 'get',
    params: {
      page,
      status,
    },
  });
}

export default getAllMapPoints;
