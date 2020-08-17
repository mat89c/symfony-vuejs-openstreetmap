<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Konto użytkownika {{ email }} nie jest aktywne.</h1>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        Aby aktywować konto użytkownika,
        dokończ rejestrację klikając w link aktywacyjny
        zamieszczony w wiadomości e-mail.
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <p>Jeśli nie otrzymałeś wiadomości e-mail, wyślij ją ponownie klikając przycisk poniżej.</p>
        <v-btn @click="onClick">Wyślij wiadomość e-mail</v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import sendRegistrationEmail from '@/api/user/sendRegistrationEmail';

export default {
  name: 'UserNotActivatedPage',
  props: {
    email: {
      type: String,
      required: true,
    },
  },
  methods: {
    onClick() {
      this.$store.dispatch('dialogloader/show', 'Proszę czekać, trwa wysyłanie wiadomości e-mail.');

      sendRegistrationEmail(this.email)
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
