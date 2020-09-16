function deleteMapPointImage(id) {
  return window.$http({
    url: '/point/image/delete',
    method: 'delete',
    data: {
      id,
    },
  });
}

export default deleteMapPointImage;
