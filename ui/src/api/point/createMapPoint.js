function createMapPoint(point) {
  const formData = new FormData();
  formData.set('title', point.title);
  formData.set('street', point.street);
  formData.set('city', point.city);
  formData.set('postcode', point.postcode);
  formData.set('description', point.description);
  formData.set('color', point.color);
  formData.set('lat', point.lat);
  formData.set('lng', point.lng);
  formData.set('logo', point.logo);
  point.categories.forEach((category, index) => {
    if (typeof category.value !== 'undefined') {
      formData.append(`categories[${index}][id]`, category.value);
      formData.append(`categories[${index}][name]`, category.text);
    } else {
      formData.append('categories[]', category);
    }
  });
  point.images.forEach((image) => {
    formData.append('images[]', image);
  });

  return window.$http({
    url: '/api/point/create',
    method: 'post',
    data: formData,
    headers: { 'Content-Type': 'multipart/form-data' },
  });
}

export default createMapPoint;
