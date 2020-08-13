const getters = {
  title: (state) => state.title,
  message: (state) => state.message,
  visibility: (state) => state.visibility,
};

const actions = {
  show({ commit }, { title, message }) {
    commit('SHOW', { title, message });
  },
  hide({ commit }) {
    commit('HIDE');
  },
};

const mutations = {
  SHOW: (state, { title, message }) => {
    state.title = title;
    state.message = message;
    state.visibility = true;
  },
  HIDE: (state) => {
    state.title = '';
    state.message = '';
    state.visibility = false;
  },
};

const state = {
  title: '',
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
