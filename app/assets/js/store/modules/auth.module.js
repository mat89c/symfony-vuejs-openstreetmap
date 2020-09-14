const getters = {
  id: (state) => state.id,
  name: (state) => state.name,
  email: (state) => state.email,
  roles: (state) => state.roles,
};

const actions = {
  setUser({ commit }, user) {
    commit('SET_USER', user);
  },
};

const mutations = {
  SET_USER: (state, user) => {
    state.id = user.id;
    state.name = user.name;
    state.email = user.email;
    state.roles = user.roles;
  },
};

const state = {
  id: '',
  name: '',
  email: '',
  roles: [],
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
};
