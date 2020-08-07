import axios from 'axios';

export default function Http(store) {
  const service = axios.create({
    baseURL: 'http://127.0.0.1:8000',
    responseType: 'json',
  });

  service.interceptors.request.use((config) => {
    if (window.$cookies.isKey('user_token')) {
      /* eslint no-param-reassign: "error" */
      config.headers.Authorization = `Bearer ${window.$cookies.get('user_token')}`;
    }
    return config;
  });

  service.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response.status === 401) {
        store.dispatch('notificationbar/showNotification', { msg: 'Nieprawidłowe dane logowania', color: 'error', show: true });
      }
      return Promise.reject(error);
    },
  );

  return service;
}
