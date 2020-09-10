const getters = {
  msg: (state) => state.msg,
  color: (state) => state.color,
  show: (state) => state.show,
};

const actions = {
  showNotification({ commit }, { msg, color, show }) {
    commit('SET_NOTIFICATION_SHOW', { msg, color, show });
  },
  hideNotification({ commit }, show) {
    commit('SET_NOTIFICATION_HIDE', show);
  },
};

const mutations = {
  SET_NOTIFICATION_SHOW: (state, { msg, color, show }) => {
    state.msg = msg;
    state.color = color;
    state.show = show;
  },
  SET_NOTIFICATION_HIDE: (state, show) => {
    state.show = show;
  },
};

const state = {
  msg: '',
  color: '',
  show: false,
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
};
