<template>
  <v-container>
    <ReviewForm />
  </v-container>
</template>

<script>

export default {
  name: 'ReviewUpdatePage',
  created() {
    alert(this.$route.params.id);
  },
};
</script>
