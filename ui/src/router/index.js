import Vue from 'vue';
import VueRouter from 'vue-router';
import TheLayout from '@/views/layout/TheLayout.vue';
import checkTokenExpiriesDate from '@/helper/checkTokenExpiriesDate';
import store from '../store';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    component: TheLayout,
    children: [
      {
        path: '',
        component: () => import(/* webpackChunkName: 'front' */'@/views/HomePage.vue'),
        meta: {
          requiresAuth: false,
        },
        children: [
          {
            path: '',
            component: () => import(/* webpackChunkName: 'front' */'@/components/HomePointList.vue'),
            meta: {
              requiresAuth: false,
            },
          },
        ],
      },
      {
        path: 'logowanie',
        component: () => import(/* webpackChunkName: 'user' */'@/views/LoginPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
      {
        path: 'rejestracja',
        component: () => import(/* webpackChunkName: 'auth' */'@/views/RegistrationPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
      {
        path: 'wyloguj',
        component: () => import(/* webpackChunkName: 'user' */'@/views/LogoutPage.vue'),
        meta: {
          requiresAuth: true,
        },
      },
      {
        name: 'UserNotActivatedPage',
        path: 'konto-nieaktywne',
        component: () => import(/* webpackChunkName: 'auth' */'@/views/UserNotActivatedPage.vue'),
        meta: {
          requiresAuth: false,
        },
        props: true,
      },
      {
        path: 'aktywuj-konto/:token',
        component: () => import(/* webpackChunkName: 'auth' */'@/views/UserActivatedPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
      {
        path: 'moje-konto',
        component: () => import(/* webpackChunkName: 'user' */'@/views/UserAccountPage.vue'),
        meta: {
          requiresAuth: true,
        },
      },
      {
        path: 'resetuj-haslo/:token',
        component: () => import(/* webpackChunkName: 'auth' */'@/views/UserResetPasswordPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
      {
        path: 'resetuj-haslo',
        component: () => import(/* webpackChunkName: 'auth' */'@/views/UserResetPasswordPage.vue'),
        meta: {
          requiresAuth: false,
        },
        props: true,
      },
      {
        path: 'zapomnialem-haslo',
        component: () => import(/* webpackChunkName: 'auth' */'@/views/UserForgotPasswordPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
      {
        path: 'dodaj-punkt',
        component: () => import(/* webpackChunkName: 'front' */'@/views/MapPointCreatePage.vue'),
        meta: {
          requiresAuth: true,
        },
      },
      {
        path: '404',
        component: () => import(/* webpackChunkName: 'front' */'@/views/NotFoundPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
    ],
  },
  {
    path: '*',
    redirect: '/404',
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

  if ((to.path === '/logowanie' || to.path === '/rejestracja') && checkTokenExpiriesDate(store.getters['user/expiriesDate'])) {
    next({ path: '/moje-konto' });
  }

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!checkTokenExpiriesDate(store.getters['user/expiriesDate'])) {
      next({ path: '/logowanie' });
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
