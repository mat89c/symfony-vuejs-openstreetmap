<template>
  <v-container>
    <ReviewForm
      :title="title"
      :action="action"
      :review="defaultReview"
    />
  </v-container>
</template>

<script>
import ReviewForm from '../../components/ReviewForm.vue'

export default {
  name: 'ReviewCreatePage',
  components: {
    ReviewForm,
  },
  data() {
    return {
      title: 'Dodaj opinię',
      action: 'onCreateReview',
      defaultReview: {
        content: '',
        rating: 5,
        isActive: false,
      },
    };
  },
};
</script>
