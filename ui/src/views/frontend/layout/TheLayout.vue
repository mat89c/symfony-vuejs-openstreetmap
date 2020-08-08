<template>
  <v-app>
    <TheHeader/>
    <TheNavigation :key="navigationKey"/>
    <TheContent/>
    <NotificationBar/>
  </v-app>
</template>

<script>
import TheHeader from '@/views/frontend/layout/TheHeader.vue';
import TheNavigation from '@/views/frontend/layout/TheNavigation.vue';
import TheContent from '@/views/frontend/layout/TheContent.vue';
import NotificationBar from '@/components/NotificationBar.vue';

export default {
  name: 'TheLayout',
  components: {
    TheHeader,
    TheNavigation,
    TheContent,
    NotificationBar,
  },
  computed: {
    navigationKey: {
      get() { return this.$store.getters['navigation/key']; },
    },
  },
};
</script>
