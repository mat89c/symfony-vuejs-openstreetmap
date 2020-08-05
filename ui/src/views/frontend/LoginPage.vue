<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Zaloguj się</h1>
      </v-col>
    </v-row>
    <v-form @submit="onSubmit">
      <v-row justify="center">
        <v-col cols="10" sm="6">
          <v-text-field
            label="E-mail"
            v-model="username"
          ></v-text-field>
          <v-text-field
            label='Hasło'
            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
            :type="showPassword ? 'text' : 'password'"
            @click:append="showPassword = !showPassword"
            v-model="password"
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
export default {
  name: 'LoginPage',
  data: () => ({
    username: '',
    password: '',
    showPassword: false,
  }),
  methods: {
    onSubmit() {

    },
  },
};
</script>
