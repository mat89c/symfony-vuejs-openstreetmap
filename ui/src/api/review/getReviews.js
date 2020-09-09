function getReviews(pointId, page) {
  return window.$http({
    url: `/api/reviews/${pointId}/${page}`,
    method: 'get',
  });
}

export default getReviews;
