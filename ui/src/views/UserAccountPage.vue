<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Ustawienia</h1>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <h2>Zresetuj hasło</h2>
        <p>Na Twój adres e-mail zostanie wysłana wiadomość z linkiem resetującym hasło.</p>
        <v-btn @click="onSendResetPasswordEmail">Zresetuj hasło</v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import sendResetPasswordEmail from '@/api/user/sendResetPasswordEmail';

export default {
  name: 'UserAccountPage',
  methods: {
    onSendResetPasswordEmail() {
      this.$store.dispatch('dialogloader/show', 'Proszę czekać, trwa wysyłanie wiadomości e-mail.');
      sendResetPasswordEmail(this.$store.getters['user/email'])
        .then((response) => {
          this.$router.push('/')
            .then(() => {
              this.$store.dispatch('dialogpopup/show', {
                title: response.data.title,
                message: response.data.message,
              });
            });
        })
        .finally(() => {
          this.$store.dispatch('dialogloader/hide');
        });
    },
  },
};
</script>
