function getAllTags() {
  return window.$http({
    url: '/tags/all',
    method: 'get',
  });
}

export default getAllTags;
