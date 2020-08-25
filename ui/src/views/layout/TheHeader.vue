<template>
  <v-app-bar
    app
    clipped-left
    dense
  >
    <v-app-bar-nav-icon @click="toggleNavigationVisibility"/>
    <v-toolbar-title><strong>DemoApp</strong></v-toolbar-title>
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
  },
  methods: {
    toggleNavigationVisibility() {
      this.navigationVisibility = !this.navigationVisibility;
    },
  },
};
</script>
