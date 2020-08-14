<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Konto użytkownika nie jest aktywne.</h1>
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
        <v-btn>Wyślij wiadomość e-mail</v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'UserNotActivatedPage',
};
</script>
