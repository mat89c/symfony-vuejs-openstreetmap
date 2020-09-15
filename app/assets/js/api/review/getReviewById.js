function getReviewById(id) {
  return window.$http({
    url: `/review/${id}`,
    method: 'get',
  });
}

export default getReviewById;
