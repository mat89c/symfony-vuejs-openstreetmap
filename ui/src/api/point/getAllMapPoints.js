function getAllMapPoints(checkedCategories, mapBounds, page) {
  return window.$http({
    url: '/api/points',
    method: 'get',
    params: {
      checkedCategories,
      mapBounds,
      page,
    },
  });
}

export default getAllMapPoints;
