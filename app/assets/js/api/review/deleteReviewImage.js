function deleteReviewImage(id) {
  return window.$http({
    url: '/review/image/delete',
    method: 'delete',
    data: {
      id,
    },
  });
}

export default deleteReviewImage;
