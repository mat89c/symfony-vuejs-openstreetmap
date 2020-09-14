function deleteTag(id) {
  return window.$http({
    url: '/tag/delete',
    method: 'delete',
    data: {
      id,
    },
  });
}

export default deleteTag;
