<template>
  <v-card
  v-if="show"
  v-intersect="infiniteScrolling">
    <v-progress-linear
      indeterminate
    ></v-progress-linear>
  </v-card>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'HomePointListInfiniteScroll',
  data() {
    return {
      scrollLoading: false,
      show: true,
    };
  },
  computed: {
    ...mapGetters({
      checkedCategories: 'categories/checkedCategories',
      mapBounds: 'map/bounds',
      page: 'point/page',
    }),
  },
  methods: {
    ...mapActions({
      setPage: 'point/setPage',
      appendMapPointsOnScroll: 'point/appendMapPointsOnScroll',
    }),
    infiniteScrolling(entries, observer, isIntersecting) {
      if (!this.scrollLoading && isIntersecting) {
        this.scrollLoading = true;
        this.setPage(this.page + 1);
        this.appendMapPointsOnScroll({
          checkedCategories: this.checkedCategories,
          mapBounds: this.mapBounds,
          page: this.page,
        }).then((isLastPage) => {
          this.show = isLastPage;
        }).finally(() => { this.scrollLoading = false; });
      }
    },
  },
};
</script>
