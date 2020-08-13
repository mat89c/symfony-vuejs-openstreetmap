<template>
  <v-dialog max-width="300" v-model="visibility" persistent>
    <v-card dark>
      <v-card-text class="pt-2">
        {{ message }}
        <v-progress-linear
          indeterminate
        ></v-progress-linear>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'DialogLoader',
  computed: {
    message: {
      get() { return this.$store.getters['dialogloader/message']; },
    },
    visibility: {
      get() { return this.$store.getters['dialogloader/visibility']; },
    },
  },
};
</script>
