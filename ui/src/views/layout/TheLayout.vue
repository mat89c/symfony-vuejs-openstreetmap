<template>
  <v-app>
    <TheHeader/>
    <TheNavigation :key="navigationKey"/>
    <TheContent/>
    <NotificationBar/>
    <DialogPopup/>
    <DialogLoader/>
  </v-app>
</template>

<script>
import TheHeader from '@/views/layout/TheHeader.vue';
import TheNavigation from '@/views/layout/TheNavigation.vue';
import TheContent from '@/views/layout/TheContent.vue';
import NotificationBar from '@/components/NotificationBar.vue';
import DialogPopup from '@/components/DialogPopup.vue';
import DialogLoader from '@/components/DialogLoader.vue';

export default {
  name: 'TheLayout',
  components: {
    TheHeader,
    TheNavigation,
    TheContent,
    NotificationBar,
    DialogPopup,
    DialogLoader,
  },
  computed: {
    navigationKey: {
      get() { return this.$store.getters['navigation/key']; },
    },
  },
};
</script>
