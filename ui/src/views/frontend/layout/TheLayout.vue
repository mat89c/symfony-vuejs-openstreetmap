<template>
  <v-app>
    <TheNavigation/>
    <TheContent/>
    <NotificationBar/>
  </v-app>
</template>

<script>
import TheNavigation from '@/views/frontend/layout/TheNavigation.vue';
import TheContent from '@/views/frontend/layout/TheContent.vue';
import NotificationBar from '@/components/NotificationBar.vue';

export default {
  name: 'TheLayout',
  components: {
    TheNavigation,
    TheContent,
    NotificationBar,
  },
};
</script>
