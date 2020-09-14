function deleteMapPoint(id) {
  return window.$http({
    url: '/point/delete',
    method: 'delete',
    data: {
      id,
    },
  });
}

export default deleteMapPoint;
