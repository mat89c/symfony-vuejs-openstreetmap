import Vue from 'vue';
import VueRouter from 'vue-router';
import TheLayout from '../views/layout/TheLayout.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    component: TheLayout,
    children: [
      {
        path: 'dashboard',
        component: () => import(/* webpackChunkName: 'admin' */'../views/HomePage.vue'),
      },
      {
        path: 'punkty',
        component: () => import(/* webpackChunkName: 'admin' */'../views/MapPointsPage.vue'),
      },
      {
        path: 'uzytkownicy',
        component: () => import(/* webpackChunkName: 'admin' */'../views/UsersPage.vue'),
      },
      {
        path: 'tagi',
        component: () => import(/* webpackChunkName: 'admin' */'../views/TagsPage.vue'),
      },
      {
        path: 'opinie',
        component: () => import(/* webpackChunkName: 'admin' */'../views/ReviewsPage.vue'),
      },
      {
        path: 'wyloguj',
        component: () => import(/* webpackChunkName: 'logout' */'../views/LogoutPage.vue'),
      },
      {
        path: '404',
        component: () => import(/* webpackChunkName: 'admin' */'../views/NotFoundPage.vue'),
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

export default router;
