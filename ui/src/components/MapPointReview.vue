<template>
    <div>
      <v-card v-for="review in reviews" :key="review.id" class="mb-3">
        <v-card-title class="pb-0 justify-space-between">
          <span v-if="$store.getters['user/id'] === review.user.id">
            Twoja opinia
            <v-btn
              small
            >
              Edytuj
            </v-btn>
          </span>
          <span v-else>
            {{ review.user.name }}
          </span>
          <em><small class="text-caption">Dodano: {{ review.createdAt | date }}</small></em>
        </v-card-title>
        <v-card-text>
          <v-rating
            :value="review.rating"
            class="mb-1"
            color="amber"
            length="10"
            dense
            background-color="grey  "
            readonly
            size="20"
          ></v-rating>
          <em v-html="review.content"></em>
        </v-card-text>
      </v-card>
    </div>
</template>

<script>
export default {
  name: 'MapPointReview',
  props: {
    reviews: {
      type: Array,
      required: true,
    },
  },
};
</script>
