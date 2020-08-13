<template>
  <v-app>
    <TheHeader/>
    <TheNavigation :key="navigationKey"/>
    <TheContent/>
    <NotificationBar/>
    <DialogPopup/>
  </v-app>
</template>

<script>
import TheHeader from '@/views/layout/TheHeader.vue';
import TheNavigation from '@/views/layout/TheNavigation.vue';
import TheContent from '@/views/layout/TheContent.vue';
import NotificationBar from '@/components/NotificationBar.vue';
import DialogPopup from '@/components/DialogPopup.vue';

export default {
  name: 'TheLayout',
  components: {
    TheHeader,
    TheNavigation,
    TheContent,
    NotificationBar,
    DialogPopup,
  },
  computed: {
    navigationKey: {
      get() { return this.$store.getters['navigation/key']; },
    },
  },
};
</script>
