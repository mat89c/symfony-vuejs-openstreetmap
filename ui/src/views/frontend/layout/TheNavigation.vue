<template>
  <v-navigation-drawer
    app
    clipped
    v-model="show"
  >
    <v-list dense>
      <v-list-item
        v-for="page in pages"
        :key="page.title"
        link
        :to="page.path"
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
  data: () => ({
    show: true,
    pages,
  }),
};
</script>
