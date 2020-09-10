<template>
  <v-snackbar
    right
    bottom
    v-model="show"
    :color="color"
  >
    {{ msg }}
  <v-btn text @click="show = false">
    <v-icon medium color="#fff">mdi-close</v-icon>
  </v-btn>
  </v-snackbar>
</template>

<script>
export default {
  name: 'NotificationBar',
  computed: {
    msg: {
      get() { return this.$store.getters['notificationbar/msg']; },
    },
    color: {
      get() { return this.$store.getters['notificationbar/color']; },
    },
    show: {
      get() { return this.$store.getters['notificationbar/show']; },
      set(value) { this.$store.dispatch('notificationbar/hideNotification', value); },
    },
  },
};
</script>
