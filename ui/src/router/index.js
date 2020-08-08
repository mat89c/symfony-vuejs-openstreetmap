import Vue from 'vue';
import VueRouter from 'vue-router';
import TheLayout from '@/views/frontend/layout/TheLayout.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    component: TheLayout,
    children: [
      {
        path: 'logowanie',
        component: () => import(/* webpackChunkName: 'front' */'@/views/frontend/LoginPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
      {
        path: 'rejestracja',
        component: () => import(/* webpackChunkName: 'front */'@/views/frontend/RegistrationPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
      {
        path: 'wyloguj',
        component: () => import(/* webpackChunkName: 'front' */'@/views/frontend/LogoutPage.vue'),
        meta: {
          requiresAuth: true,
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
