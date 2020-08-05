<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Zaloguj się</h1>
      </v-col>
    </v-row>
    <v-form @submit.prevent="onSubmit" ref="form">
      <v-row justify="center">
        <v-col cols="10" sm="6">
          <v-text-field
            label="E-mail"
            v-model="username"
            :rules="$rules.required && $rules.email"
          ></v-text-field>
          <v-text-field
            label='Hasło'
            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
            :type="showPassword ? 'text' : 'password'"
            @click:append="showPassword = !showPassword"
            v-model="password"
            :rules="$rules.required"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row justify="center">
        <v-col class="mt-4 text-right" cols="10" sm="6">
          <v-btn type="submit">Zaloguj się</v-btn>
        </v-col>
      </v-row>
      <v-row justify="center">
        <v-col class="text-right" cols="10" sm="6">
         <v-btn small text to="/rejestracja">Utwórz konto</v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script>
import { mapActions } from 'vuex';

export default {
  name: 'LoginPage',
  data: () => ({
    username: '',
    password: '',
    showPassword: false,
  }),
  methods: {
    ...mapActions({
      login: 'user/login',
    }),
    onSubmit() {
      if (!this.$refs.form.validate()) {
        return;
      }

      this.login({ username: this.username, password: this.password })
        .then(() => {
          this.$router.push('/');
        });
    },
  },
};
</script>
