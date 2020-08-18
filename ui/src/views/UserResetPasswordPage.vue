<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Zmiana hasła</h1>
        <p v-if="!tokenValid">{{ msg }}</p>
        <v-form v-if="tokenValid" @submit.prevent="onSubmit" ref="form">
          <v-row justify="center">
            <v-col cols="10" sm="6">
              <v-text-field
                label="Nowe hasło"
                v-model="password"
                :rules="$rules.required && $rules.minLength"
                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                :type="showPassword ? 'text' : 'password'"
              ></v-text-field>
              <v-text-field
                label="Powtórz hasło"
                v-model="password2"
                :rules="$rules.required && passwordComparision"
                :append-icon="showPassword2 ? 'mdi-eye' : 'mdi-eye-off'"
                :type="showPassword2 ? 'text' : 'password'"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row justify="center">
            <v-col class="mt-4 text-right" cols="10" sm="6">
              <v-btn type="submit">Zatwierdź nowe hasło</v-btn>
            </v-col>
          </v-row>
        </v-form>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import resetPassword from '@/api/user/resetPassword';
import checkResetPasswordTokenValid from '@/api/user/checkResetPasswordTokenValid';

export default {
  name: 'UserResetPasswordPage',
  data() {
    return {
      showPassword: false,
      showPassword2: false,
      msg: 'Proszę czekać, trwa weryfikacja...',
      tokenValid: false,
      password: '',
      password2: '',
      passwordComparision: [
        (v) => this.password === v || 'Podane hasła różnią się.',
      ],
    };
  },
  created() {
    this.$store.dispatch('dialogloader/show', 'Proszę czekać, trwa weryfikacja...');

    checkResetPasswordTokenValid(this.$route.params.token)
      .then((response) => {
        if (response.status === 200) {
          this.tokenValid = true;
        }
      })
      .catch(() => {
        this.msg = `
          Weryfikacja nie powiodła się. Proszę skontaktować się z administratorem.
        `;
      })
      .finally(() => {
        this.$store.dispatch('dialogloader/hide');
      });
  },
  methods: {
    onSubmit() {
      if (!this.$refs.form.validate()) {
        return;
      }

      this.$store.dispatch('dialogloader/show', 'Proszę czekać, trwa zmiana hasła.');
      resetPassword(this.$route.params.token, this.password)
        .then((response) => {
          this.$router.push('/logowanie')
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
