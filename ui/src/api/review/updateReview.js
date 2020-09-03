function updateReview(review, rating, reviewId, reviewImages) {
  const formData = new FormData();
  formData.set('review', review);
  formData.set('rating', rating);
  reviewImages.forEach((image) => {
    formData.append('reviewImages[]', image);
  });
  return window.$http({
    url: `/api/review/${reviewId}/update`,
    method: 'post',
    data: formData,
    headers: { 'Content-Type': 'multipart/form-data' },
  });
}

export default updateReview;
