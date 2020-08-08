<template>
  <div></div>
</template>

<script>
export default {
  name: 'LogoutPage',
  computed: {
    navigationKey: {
      get() { return this.$store.getters['navigation/key']; },
    },
  },
  created() {
    this.$store.dispatch('user/logout')
      .then(() => {
        this.$router.push('/');
        this.$store.dispatch('navigation/refresh', this.navigationKey + 1);
      });
  },
};
</script>
