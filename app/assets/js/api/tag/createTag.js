function createTag(tag) {
  return window.$http({
    url: '/tag/create',
    method: 'post',
    data: {
      tag,
    },
  });
}

export default createTag;
