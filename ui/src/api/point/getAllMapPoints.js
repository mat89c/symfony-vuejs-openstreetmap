function getAllMapPoints(checkedCategories) {
  return window.$http({
    url: '/api/points',
    method: 'get',
    params: {
      checkedCategories,
    },
  });
}

export default getAllMapPoints;
