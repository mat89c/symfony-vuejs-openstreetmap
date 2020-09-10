const getters = {
  message: (state) => state.message,
  visibility: (state) => state.visibility,
};

const actions = {
  show({ commit }, message) {
    commit('SHOW', message);
  },
  hide({ commit }) {
    commit('HIDE');
  },
};

const mutations = {
  SHOW: (state, message) => {
    state.message = message;
    state.visibility = true;
  },
  HIDE: (state) => {
    state.message = '';
    state.visibility = false;
  },
};

const state = {
  message: '',
  visibility: false,
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
};
