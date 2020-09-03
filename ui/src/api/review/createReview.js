function createReview(rating, review, reviewImages, mapPointId) {
  const formData = new FormData();
  formData.set('rating', rating);
  formData.set('review', review);
  formData.set('mapPointId', mapPointId);
  reviewImages.forEach((image) => {
    formData.append('reviewImages[]', image);
  });
  return window.$http({
    url: '/api/review/create',
    method: 'post',
    data: formData,
    headers: { 'Content-Type': 'multipart/form-data' },
  });
}

export default createReview;
