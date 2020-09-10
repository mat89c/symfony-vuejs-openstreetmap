<template>
  <v-app>
    <v-main>
      <v-container>
        <v-row>
          <v-col cols="12">
            <h1>Logowanie</h1>
          </v-col>
        </v-row>
        <v-form @submit.prevent="onSubmit" ref="form">
          <v-row justify="center">
            <v-col cols="10" sm="6">
              <v-text-field
                label="E-mail"
                v-model="email"
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
        </v-form>
      </v-container>
    </v-main>

    <DialogLoader/>
    <NotificationBar/>
  </v-app>
</template>

<script>
import DialogLoader from './DialogLoader.vue';
import NotificationBar from './NotificationBar.vue';
import login from '../api/auth/login';

export default {
  name: 'LoginComponent',
  data() {
    return {
      email: '',
      password: '',
      showPassword: false,
    };
  },
  components: {
    DialogLoader,
    NotificationBar,
  },
  methods: {
    onSubmit() {
      if (!this.$refs.form.validate()) {
        return;
      }

      this.$store.dispatch('dialogloader/show', 'Logowanie...');
      login(this.email, this.password)
        .then((response) => {
          window.location = response.data.redirect;
        })
        .finally(() => {
          this.$store.dispatch('dialogloader/hide');
        });
    },
  },
};
</script>
