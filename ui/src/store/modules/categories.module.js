const getters = {
  btnVisibility: (state) => state.btnVisibility,
  checkedCategories: (state) => state.checkedCategories,
  bottomSheetVisibility: (state) => state.bottomSheetVisibility,
};

const actions = {
  setCheckedCategories({ commit }, checkedCategories) {
    commit('SET_CHECKED_CATEGORIES', checkedCategories);
  },
  showBtn({ commit }) {
    commit('SET_BTN_VISIBILITY', true);
  },
  hideBtn({ commit }) {
    commit('SET_BTN_VISIBILITY', false);
  },
  setBottomSheetVisibility({ commit }, visibility) {
    commit('SET_BOTTOM_SHEET_VISIBILITY', visibility);
  },
};

const mutations = {
  SET_CHECKED_CATEGORIES: (state, checkedCategories) => {
    state.checkedCategories = checkedCategories;
  },
  SET_BTN_VISIBILITY: (state, visibility) => {
    state.btnVisibility = visibility;
  },
  SET_BOTTOM_SHEET_VISIBILITY: (state, visibility) => {
    state.bottomSheetVisibility = visibility;
  },
};

const state = {
  btnVisibility: false,
  checkedCategories: [],
  bottomSheetVisibility: false,
};

export default {
  state,
  getters,
  actions,
  mutations,
  namespaced: true,
};
