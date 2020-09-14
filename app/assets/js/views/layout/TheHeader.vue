<template>
  <v-app-bar
    app
    clipped-left
    dense
  >
    <v-app-bar-nav-icon @click="toggleNavigationVisibility"/>
    <v-toolbar-title><strong>Panel adminstratora</strong></v-toolbar-title>

    <v-spacer></v-spacer>
    <div class="mr-5 d-none d-sm-block" v-if="this.$store.getters['auth/name']">
      Witaj {{ this.$store.getters['auth/name'] }}
    </div>
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
