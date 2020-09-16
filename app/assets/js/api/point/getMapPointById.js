function getMapPointById(id) {
  return window.$http({
    url: `/point/${id}`,
    method: 'get',
  });
}

export default getMapPointById;
