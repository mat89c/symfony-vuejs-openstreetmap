function updateReview(review, rating, reviewId) {
  return window.$http({
    url: `/api/review/${reviewId}/update`,
    method: 'PATCH',
    data: {
      review,
      rating,
    },
  });
}

export default updateReview;
