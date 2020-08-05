<template>
  <v-app>
    <TheNavigation/>
    <TheContent/>
  </v-app>
</template>

<script>
import TheNavigation from '@/views/frontend/layout/TheNavigation.vue';
import TheContent from '@/views/frontend/layout/TheContent.vue';

export default {
  name: 'TheLayout',
  components: {
    TheNavigation,
    TheContent,
  },
};
</script>
