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
        component: () => import(/* webpackChunkName: 'admin-home' */'../views/HomePage.vue'),
      },
      {
        name: 'PointsPage',
        path: 'punkty',
        component: () => import(/* webpackChunkName: 'points' */'../views/MapPoint/MapPointsPage.vue'),
      },
      {
        name: 'PointCreatePage',
        path: 'punkty/dodaj',
        component: () => import(/* webpackChunkName: 'point-create' */'../views/MapPoint/MapPointCreatePage.vue'),
      },
      {
        name: 'PointUpdatePage',
        path: 'punkty/edytuj/:id',
        component: () => import(/* webpackChunkName: 'point-update' */'../views/MapPoint/MapPointUpdatePage.vue'),
      },
      {
        path: 'uzytkownicy',
        component: () => import(/* webpackChunkName: 'users' */'../views/User/UsersPage.vue'),
      },
      {
        path: 'tagi',
        component: () => import(/* webpackChunkName: 'tags' */'../views/TagsPage.vue'),
      },
      {
        name: 'ReviewsPage',
        path: 'opinie',
        component: () => import(/* webpackChunkName: 'reviews' */'../views/Review/ReviewsPage.vue'),
      },
      {
        name: 'ReviewCreatePage',
        path: 'opinie/dodaj',
        component: () => import(/* webpackChunkName: 'review-create' */'../views/Review/ReviewCreatePage.vue'),
      },
      {
        name: 'ReviewUpdatePage',
        path: 'opinie/edytuj/:id',
        component: () => import(/* webpackChunkName: 'review-update' */'../views/Review/ReviewUpdatePage.vue'),
      },
      {
        path: 'wyloguj',
        component: () => import(/* webpackChunkName: 'logout' */'../views/LogoutPage.vue'),
      },
      {
        path: '404',
        component: () => import(/* webpackChunkName: 'not-found' */'../views/NotFoundPage.vue'),
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
