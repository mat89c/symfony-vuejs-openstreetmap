function updateMapPoint(point) {
  const formData = new FormData();
  formData.set('id', point.id);
  formData.set('title', point.title);
  formData.set('street', point.street);
  formData.set('city', point.city);
  formData.set('postcode', point.postcode);
  formData.set('description', point.description);
  formData.set('color', point.color);
  formData.set('lat', point.lat);
  formData.set('lng', point.lng);
  formData.set('newLogo', point.newLogo);
  formData.set('user', point.user.id);
  formData.set('isActive', +point.isActive);
  point.mapPointCategories.forEach((category, index) => {
    if (typeof category.name !== 'undefined') {
      formData.append(`categories[${index}][id]`, category.id);
      formData.append(`categories[${index}][name]`, category.name);
    } else {
      formData.append('categories[]', category);
    }
  });

  if (typeof point.newMapPointImages !== 'undefined') {
    point.newMapPointImages.forEach((image) => {
      formData.append('newMapPointImages[]', image);
    });
  }

  return window.$http({
    url: '/point/update',
    method: 'post',
    data: formData,
    headers: { 'Content-Type': 'multipart/form-data' },
  });
}

export default updateMapPoint;
