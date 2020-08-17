<template>
  <v-navigation-drawer
    app
    clipped
    v-model="visibility"
  >
    <v-list dense>
      <v-list-item
        v-for="page in filteredPages"
        :key="page.title"
        link
        :to="page.path"
        @click="hideNavigation"
      >
        <v-list-item-action>
          <v-icon>{{ page.icon }}</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>{{ page.title }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
import pages from '@/variables/pages';

export default {
  name: 'TheNavigation',
  computed: {
    visibility: {
      get() { return this.$store.getters['navigation/visibility']; },
      set(visibility) { this.$store.dispatch('navigation/setVisibility', visibility); },
    },
    filteredPages() {
      const userLogged = this.$store.getters['user/token'];
      const filteredPages = this.pages.filter((page) => {
        if ('showIfUserLogged' in page && userLogged === '' && page.showIfUserLogged) {
          return false;
        }

        if ('showIfUserLogged' in page && userLogged !== '' && !page.showIfUserLogged) {
          return false;
        }
        return page;
      });

      return filteredPages;
    },
  },
  methods: {
    hideNavigation() {
      this.visibility = false;
    },
  },
  data: () => ({
    pages,
  }),
};
</script>
