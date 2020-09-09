const pages = [
  {
    title: 'Home',
    path: '/',
    icon: 'mdi-navigation',
  },
  {
    title: 'Dodaj punkt',
    path: '/dodaj-punkt',
    icon: 'mdi-map-marker-plus',
    showIfUserLogged: false,
  },
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
    title: 'Moje konto',
    path: '/moje-konto',
    icon: 'mdi-account-cog',
    showIfUserLogged: true,
  },
  {
    title: 'Wyloguj się',
    path: '/wyloguj',
    icon: 'mdi-account-circle',
    showIfUserLogged: true,
  },
];

export default pages;
