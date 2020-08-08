const pages = [
  {
    title: 'Zaloguj się',
    path: '/logowanie',
    icon: 'mdi-account-circle',
    showIfUserLogged: false,
  },
  {
    title: 'Załóż konto',
    path: '/rejestracja',
    icon: 'mdi-account-plus',
    showIfUserLogged: false,
  },
  {
    title: 'Wyloguj się',
    path: '/wyloguj',
    icon: 'mdi-account-circle',
    showIfUserLogged: true,
  },
];

export default pages;
