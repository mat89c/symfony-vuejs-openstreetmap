<template>
  <v-app-bar
    app
    clipped-left
    dense
  >
    <v-app-bar-nav-icon @click="toggleNavigationVisibility"/>
    <v-toolbar-title><strong>Plaże i kąpieliska</strong></v-toolbar-title>

    <v-spacer></v-spacer>
    <div class="mr-5 d-none d-sm-block" v-if="this.$store.getters['user/name']">
      Witaj {{ this.$store.getters['user/name'] }}
    </div>
    <v-btn
      v-if="filterBtnVisibility"
      icon
      @click="showBottomSheet()"
    >
      <v-icon>mdi-magnify</v-icon>
    </v-btn>
  </v-app-bar>
</template>

<script>

export default {
  name: 'TheHeader',
  computed: {
    navigationVisibility: {
      get() { return this.$store.getters['navigation/visibility']; },
      set(visibility) { this.$store.dispatch('navigation/setVisibility', visibility); },
    },
    filterBtnVisibility: {
      get() { return this.$store.getters['categories/btnVisibility']; },
    },
  },
  methods: {
    toggleNavigationVisibility() {
      this.navigationVisibility = !this.navigationVisibility;
    },
    showBottomSheet() {
      this.$store.dispatch('categories/setBottomSheetVisibility', true);
    },
  },
};
</script>
