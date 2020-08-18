<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Aktywuj konto użytkownika.</h1>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <p>{{ msg }}</p>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import apiActivateAccount from '@/api/user/activateAccount';

export default {
  name: 'UserActivatedPage',
  data() {
    return {
      msg: 'Proszę czekać, trwa weryfikacja...',
    };
  },
  created() {
    this.$store.dispatch('dialogloader/show', 'Proszę czekać, trwa weryfikacja...');

    apiActivateAccount(this.$route.params.token)
      .then(() => {
        this.$router.push('/');
        this.$store.dispatch('dialogpopup/show', {
          title: 'Aktywowano konto użytkownika',
          message: 'Pomyślnie zakończono rejestrację użytkownika. Możesz teraz się zalogować.',
        });
      })
      .catch((error) => {
        this.msg = `
          Weryfikacja nie powiodła się.
          ${error.response.data.errors.message}
          Proszę skontaktować się z administratorem.
        `;
      })
      .finally(() => {
        this.$store.dispatch('dialogloader/hide');
      });
  },
};
</script>
