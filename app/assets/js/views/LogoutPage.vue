<template>
  <v-container>

  </v-container>
</template>

<script>
import logout from '../api/auth/logout';

export default {
  name: 'LogoutPage',
  created() {
    this.$store.dispatch('dialogloader/show', 'Wylogowywanie...');

    logout()
      .then((response) => {
        window.location = response.data.redirect;
      })
  },
};
</script>
