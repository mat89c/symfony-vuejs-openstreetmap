function deleteReview(id) {
  return window.$http({
    url: '/review/delete',
    method: 'delete',
    data: {
      id,
    },
  });
}

export default deleteReview;
