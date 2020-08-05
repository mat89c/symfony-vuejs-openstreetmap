import apiLogin from '@/api/auth/login';

const getters = {
  token: (state) => state.token,
};

const actions = {
  async login({ commit }, { username, password }) {
    const response = await apiLogin(username, password);

    if (response) {
      window.$cookies.set('user_token', response.data.token, 3600);
      commit('SET_TOKEN', response.data.token);
    }
  },
  async logout({ commit }) {
    window.$cookies.remove('user_token');
    commit('SET_TOKEN', '');
  },
};

const mutations = {
  SET_TOKEN: (state, token) => { state.token = token; },
};

const state = {
  token: '',
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
};
