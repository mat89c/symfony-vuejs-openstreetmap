function deleteReviewImage(imageId) {
  return window.$http({
    url: '/api/review/image/delete',
    method: 'delete',
    data: {
      imageId,
    },
  });
}

export default deleteReviewImage;
