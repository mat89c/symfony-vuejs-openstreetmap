function getMapPointById(id) {
  return window.$http({
    url: `/api/point/${id}`,
    method: 'get',
  });
}

export default getMapPointById;
