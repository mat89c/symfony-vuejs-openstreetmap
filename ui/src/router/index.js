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
        component: () => import(/* webpackChunkName: 'front-pages' */'@/views/frontend/LoginPage.vue'),
        meta: {
          requiresAuth: false,
        },
      },
      {
        path: 'rejestracja',
        component: () => import(/* webpackChunkName: 'front-pages */'@/views/frontend/RegistrationPage.vue'),
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

export default router;
