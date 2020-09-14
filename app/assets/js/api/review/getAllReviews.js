function getAllReviews(page, status) {
  return window.$http({
    url: '/reviews',
    method: 'get',
    params: {
      page,
      status,
    },
  });
}

export default getAllReviews;
