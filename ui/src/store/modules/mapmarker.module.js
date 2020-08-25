const getters = {
  active: (state) => state.active,
};

const actions = {
  setActive({ commit }, active) {
    commit('SET_MAPMARKER_ACTIVE', active);
  },
};

const mutations = {
  SET_MAPMARKER_ACTIVE: (state, active) => {
    state.active = active;
  },
};

const state = {
  active: null,
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
};
