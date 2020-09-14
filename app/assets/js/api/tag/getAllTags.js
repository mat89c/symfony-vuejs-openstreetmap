function getAllTags(page, status) {
  return window.$http({
    url: '/tags',
    method: 'get',
    params: {
      page,
      status,
    },
  });
}

export default getAllTags;
