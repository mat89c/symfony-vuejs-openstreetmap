import store from '@/store';

function checkTokenExpiriesDate(expiriesData) {
  if (Math.round(new Date().getTime() / 1000) < expiriesData) {
    return true;
  }

  if (expiriesData !== '') {
    store.dispatch('user/logout');
    store.dispatch('dialogpopup/show', {
      title: 'Sesja wygasła',
      message: 'Zaloguj się ponownie',
    });
  }

  return false;
}

export default checkTokenExpiriesDate;
