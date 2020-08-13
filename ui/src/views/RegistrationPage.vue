<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Załóż nowe konto</h1>
      </v-col>
    </v-row>
    <v-form @submit.prevent="onSubmit" ref="form">
      <v-row justify="center">
        <v-col cols="10" sm="6">
          <v-text-field
            label="Nazwa"
            v-model="name"
            :rules="$rules.required"
          ></v-text-field>
          <v-text-field
            label="E-mail"
            v-model="email"
            :rules="$rules.required && $rules.email"
          ></v-text-field>
          <v-text-field
            label="Hasło"
            v-model="password"
            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
            :type="showPassword ? 'text' : 'password'"
            @click:append="showPassword = !showPassword"
            :rules="$rules.required && $rules.minLength"
          ></v-text-field>
          <v-text-field
            label="Powtórz hasło"
            v-model="password2"
            :append-icon="showPassword2 ? 'mdi-eye' : 'mdi-eye-off'"
            :type="showPassword2 ? 'text' : 'password'"
            @click:append="showPassword2 = !showPassword2"
            :rules="$rules.required && passwordComparision"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row justify="center">
        <v-col class="mt-4 text-right" cols="10" sm="6">
          <v-btn type="submit">Załóż konto</v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script>
import register from '@/api/auth/register';

export default {
  name: 'RegistrationPage',
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password2: '',
      showPassword: false,
      showPassword2: false,
      passwordComparision: [
        (v) => this.password === v || 'Podane hasła różnią się.',
      ],
    };
  },
  methods: {
    onSubmit() {
      if (!this.$refs.form.validate()) {
        return;
      }

      register(this.name, this.email, this.password)
        .then((response) => {
          this.$router.push('/');
          this.$store.dispatch('dialogpopup/show', {
            title: response.data.title,
            message: response.data.message,
          });
        });
    },
  },
};
</script>
