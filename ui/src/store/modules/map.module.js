const getters = {
  narrow: (state) => state.narrow,
  zoom: (state) => state.zoom,
  center: (state) => state.center,
  bounds: (state) => state.bounds,
};

const actions = {
  mapNarrow({ commit }, narrow) {
    commit('SET_MAP_NARROW', narrow);
  },
  mapZoom({ commit }, zoom) {
    commit('SET_MAP_ZOOM', zoom);
  },
  mapCenter({ commit }, center) {
    commit('SET_MAP_CENTER', center);
  },
  setBounds({ commit }, bounds) {
    commit('SET_BOUNDS', bounds);
  },
};

const mutations = {
  SET_MAP_NARROW: (state, narrow) => {
    state.narrow = narrow;
  },
  SET_MAP_ZOOM: (state, zoom) => {
    state.zoom = zoom;
  },
  SET_MAP_CENTER: (state, center) => {
    state.center = center.latlng;
  },
  SET_BOUNDS: (state, bounds) => {
    state.bounds = bounds;
  },
};

const state = {
  narrow: false,
  zoom: 6,
  center: [52.302, 19.281],
  bounds: {},
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
};
