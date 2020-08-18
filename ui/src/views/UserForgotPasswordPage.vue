<template>
  <v-container>
    <v-row>
      <v-col>
        <h1 class="mb-10">Znajdź swoje konto</h1>
        <v-form @submit.prevent="onSubmit" ref="form">
          <v-row justify="center">
            <v-col cols="10" sm="6">
              <p>
                Wprowadź adres e-mail, aby wyszukać swoje konto i
                wysłać wiadomość z linkiem resetującym hasło.
                </p>
              <v-text-field
                label="Adres e-mail"
                v-model="email"
                :rules="$rules.required && $rules.email"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row justify="center">
            <v-col class="mt-4 text-right" cols="10" sm="6">
              <v-btn type="submit">Szukaj</v-btn>
            </v-col>
          </v-row>
        </v-form>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import sendResetPasswordEmail from '@/api/user/sendResetPasswordEmail';

export default {
  name: 'UserForgotPasswordPage',
  data() {
    return {
      email: '',
    };
  },
  methods: {
    onSubmit() {
      if (!this.$refs.form.validate()) {
        return;
      }

      this.$store.dispatch('dialogloader/show', 'Proszę czekać, trwa wyszukiwanie adresu e-mail...');

      sendResetPasswordEmail(this.email)
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
