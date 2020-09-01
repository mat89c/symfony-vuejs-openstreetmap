function createReview(rating, review, mapPointId) {
  return window.$http({
    url: '/api/review/create',
    method: 'post',
    data: {
      rating,
      review,
      mapPointId,
    },
  });
}

export default createReview;
