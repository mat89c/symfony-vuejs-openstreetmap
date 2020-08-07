const getters = {
  visibility: (state) => state.visibility,
  key: (state) => state.key,
};

const actions = {
  setVisibility({ commit }, visibility) {
    commit('SET_VISIBILITY', visibility);
  },
  refresh({ commit }, key) {
    commit('REFRESH', key);
  },
};

const mutations = {
  SET_VISIBILITY: (state, visibility) => {
    state.visibility = visibility;
  },
  REFRESH: (state, key) => {
    state.key = key;
  },
};

const state = {
  visibility: false,
  key: 0,
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
};
