import Vue from 'vue';
import VueRouter from 'vue-router';
import TheLayout from '@/views/layout/TheLayout.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    component: TheLayout,
    children: [
      {
        path: 'logowanie',
        component: () => import(/* webpackChunkName: 'front' */'@/views/LoginPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
      {
        path: 'rejestracja',
        component: () => import(/* webpackChunkName: 'front' */'@/views/RegistrationPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
      {
        path: 'wyloguj',
        component: () => import(/* webpackChunkName: 'front' */'@/views/LogoutPage.vue'),
        meta: {
          requiresAuth: true,
        },
      },
      {
        name: 'UserNotActivatedPage',
        path: 'konto-nieaktywne',
        component: () => import(/* webpackChunkName: 'front' */'@/views/UserNotActivatedPage.vue'),
        meta: {
          requiresAuth: false,
        },
        props: true,
      },
      {
        path: 'aktywuj-konto/:token',
        component: () => import(/* webpackChunkName: 'front */'@/views/UserActivatedPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
      {
        path: 'ustawienia',
        component: () => import(/* webpackChunkName: 'front' */'@/views/UserAccountPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
    ],
  },
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.path === '/konto-nieaktywne' && from.path !== '/logowanie') {
    next({ path: '/' });
  }

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!window.$cookies.get('user_token')) {
      next({ path: '/logowanie' });
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
