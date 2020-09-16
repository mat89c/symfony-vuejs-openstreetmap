function updateTag(tag) {
  return window.$http({
    url: '/tag/update',
    method: 'patch',
    data: {
      tag,
    },
  });
}

export default updateTag;
