<template>
  <v-dialog
      v-model="visibility"
      width="500"
    >
      <v-card>
        <v-card-title>{{ title }}</v-card-title>
        <v-card-text>{{ message }}</v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            text
            @click="hide"
          >
            Zamknij
          </v-btn>
      </v-card-actions>
      </v-card>
    </v-dialog>
</template>

<script>
export default {
  name: 'DialogPopup',
  computed: {
    title: {
      get() { return this.$store.getters['dialogpopup/title']; },
    },
    message: {
      get() { return this.$store.getters['dialogpopup/message']; },
    },
    visibility: {
      get() { return this.$store.getters['dialogpopup/visibility']; },
    },
  },
  methods: {
    hide() {
      this.$store.dispatch('dialogpopup/hide');
    },
  },
};
</script>
