import apiGetAllMapPoints from '@/api/point/getAllMapPoints';
import apiGetMapPointById from '@/api/point/getMapPointById';

const getters = {
  points: (state) => state.points,
  loading: (state) => state.loading,
  point: (state) => state.point,
  latlng: (state) => state.latlng,
};

const actions = {
  async getAllMapPoints({ commit }) {
    const response = await apiGetAllMapPoints();

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
};

const state = {
  points: [],
  loading: false,
  point: {},
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
};
