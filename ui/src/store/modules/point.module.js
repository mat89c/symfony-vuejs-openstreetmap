const SET_POINTS = 'SET_POINTS';
const SET_LOADING = 'SET_LOADING';
const SET_POINT = 'SET_POINT';

const getters = {
  points: (state) => state.points,
  loading: (state) => state.loading,
  point: (state) => state.point,
  latlng: (state) => state.latlng,
};

const actions = {};

const mutations = {
  [SET_POINTS](state, points) {
    state.points = points;
  },
  [SET_LOADING](state, loading) {
    state.loading = loading;
  },
  [SET_POINT](state, point) {
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
