<template>
  <v-container>
    <ReviewForm
      :title="title"
      :action="action"
      :review="review"
    />
  </v-container>
</template>

<script>
import ReviewForm from '../../components/ReviewForm.vue'
import getReviewById from '../../api/review/getReviewById'

export default {
  name: 'ReviewUpdatePage',
  components: {
    ReviewForm,
  },
  created() {
    getReviewById(this.$route.params.id)
      .then((response) => {
        this.review = response.data.data;
      });
  },
  data() {
    return {
      title: 'Edytuj opinię',
      action: 'onUpdateReview',
      review: {},
    };
  },
};
</script>
