import store from '@/store';

function checkTokenExpiriesDate() {
  if (Math.round(new Date().getTime() / 1000) < store.getters['user/expiriesDate']) {
    return true;
  }

  if (store.getters['user/expiriesDate']) {
    store.dispatch('user/logout');
    store.dispatch('dialogpopup/show', {
      title: 'Sesja wygasła',
      message: 'Zaloguj się ponownie',
    });
  }

  return false;
}

export default checkTokenExpiriesDate;
