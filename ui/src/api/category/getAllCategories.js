function getAllCategories() {
  return window.$http({
    url: '/api/categories',
    method: 'get',
  });
}

export default getAllCategories;
