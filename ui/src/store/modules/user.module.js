import apiLogin from '@/api/auth/login';
import apiGetUser from '@/api/user/getUser';

const getters = {
  token: (state) => state.token,
  name: (state) => state.name,
  email: (state) => state.email,
  roles: (state) => state.roles,
  isActive: (state) => state.isActive,
};

const actions = {
  async login({ commit }, { username, password }) {
    const response = await apiLogin(username, password);

    if (response) {
      commit('SET_TOKEN', response.data.token);
      apiGetUser().then((user) => {
        commit('SET_USER', user.data);
      });
    }
  },
  logout({ commit }) {
    commit('LOGOUT');
  },
};

const mutations = {
  SET_TOKEN: (state, token) => { state.token = token; },
  SET_USER: (state, user) => {
    state.name = user.data.name;
    state.email = user.data.email;
    state.roles = user.data.roles;
    state.isActive = user.data.isActive;
  },
  LOGOUT: (state) => {
    state.name = '';
    state.email = '';
    state.roles = [];
    state.isActive = false;
    state.token = '';
  },
};

const state = {
  name: '',
  email: '',
  roles: [],
  isActive: false,
  token: '',
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
};
