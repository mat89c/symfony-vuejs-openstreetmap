import axios from 'axios';

export default function Http(store) {
  const service = axios.create({
    baseURL: '/',
    responseType: 'json',
  });

  // service.interceptors.request.use((config) => {
  //   if (store.getters['user/token'] !== '') {
  //     /* eslint no-param-reassign: "error" */
  //     config.headers.Authorization = `Bearer ${store.getters['user/token']}`;
  //   }
  //   return config;
  // });

  service.interceptors.response.use(
    (response) => response,
    (error) => {
      if (typeof error.response !== 'undefined' && error.response.status === 401) {
        store.dispatch('notificationbar/showNotification', { msg: 'Nieprawidłowe dane logowania.', color: 'error', show: true });
      } else if (typeof error.response !== 'undefined' && error.response.status === 403) {
        store.dispatch('notificationbar/showNotification', { msg: 'Nie posiadasz wymaganych uprawnień.', color: 'error', show: true });
      } else if (typeof error.response !== 'undefined' && error.response.status === 404) {
        store.dispatch('notificationbar/showNotification', { msg: 'Błąd 404', color: 'error', show: true });
      } else if (typeof error.response !== 'undefined' && typeof error.response.data.errors !== 'undefined') {
        store.dispatch('notificationbar/showNotification', { msg: error.response.data.errors.message, color: 'error', show: true });
      } else {
        store.dispatch('notificationbar/showNotification', { msg: 'Nieznany błąd. Proszę skontaktować się z administratorem.', color: 'error', show: true });
      }

      return Promise.reject(error);
    },
  );

  return service;
}
