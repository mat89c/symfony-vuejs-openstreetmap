import apiGetAllMapPoints from '@/api/point/getAllMapPoints';
import apiGetMapPointById from '@/api/point/getMapPointById';

const getters = {
  points: (state) => state.points,
  loading: (state) => state.loading,
  point: (state) => state.point,
  latlng: (state) => state.latlng,
  page: (state) => state.page,
};

const actions = {
  async getAllMapPoints({ commit }, { checkedCategories, mapBounds, page }) {
    const response = await apiGetAllMapPoints(checkedCategories, mapBounds, page);

    if (response) {
      commit('SET_POINTS', response.data.data);
    }
  },
  loading({ commit }, isLoading) {
    commit('SET_LOADING', isLoading);
  },
  async getMapPointById({ commit }, id) {
    const response = await apiGetMapPointById(id);

    if (response) {
      commit('SET_POINT', response.data.data);
    }
  },
  setPage({ commit }, page) {
    commit('SET_PAGE', page);
  },
  async appendMapPointsOnScroll({ commit }, { checkedCategories, mapBounds, page }) {
    const response = await apiGetAllMapPoints(checkedCategories, mapBounds, page);
    if (response) {
      commit('APPEND_POINTS', response.data.data);
    }

    if (response && response.data.data.length) {
      return true;
    }

    return false;
  },
};

const mutations = {
  SET_POINTS: (state, points) => {
    state.points = points;
  },
  SET_LOADING: (state, isLoading) => {
    state.loading = isLoading;
  },
  SET_POINT: (state, point) => {
    state.point = point;
  },
  SET_PAGE: (state, page) => {
    state.page = page;
  },
  APPEND_POINTS: (state, points) => {
    if (points.length > 0) {
      state.points = state.points.concat(points);
    }
  },
};

const state = {
  points: [],
  loading: false,
  point: {},
  page: 1,
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
};
