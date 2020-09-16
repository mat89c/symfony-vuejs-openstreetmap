<template>
  <div
    class="home-point-list pa-0 overflow-y-auto"
    :class="{loading: loading === true}"
  >
    <v-progress-linear
      v-if="loading"
      indeterminate
    ></v-progress-linear>
    <div v-else>
      <v-list-item
        class="py-0"
        three-line
        link
        v-for="point in points"
        :key="point.id"
        @mouseenter="setActive(point.id)"
        @mouseleave="setActive(null)"
        :to="{ name: 'MapPointPage', params: { id: point.id } }"
      >
        <v-list-item-content>
          <v-list-item-title>{{ point.title }}</v-list-item-title>
            <v-list-item-subtitle>{{ point.description | stripTags }}</v-list-item-subtitle>
            <v-list-item-subtitle class="d-flex justify-space-between">
              <div class="list-tag-wrapper">
                <v-chip
                  x-small
                  class="mr-1 mt-1"
                  v-for="category in point.mapPointCategories"
                  :key="category.id"
                >{{ category.name }}</v-chip>
              </div>
              <div v-if="point.rating !== 0" class="flex flex-nowrap align-center">
                <v-icon color="amber" small>mdi-star</v-icon>
                <span class="text-caption list-point-rate">{{ point.rating }}</span>
              </div>
            </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <HomePointListInfiniteScroll />
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex';
import HomePointListInfiniteScroll from '@/components/HomePointListInfiniteScroll.vue';

export default {
  name: 'HomePointList',
  components: {
    HomePointListInfiniteScroll,
  },
  methods: {
    ...mapActions({
      mapNarrow: 'map/mapNarrow',
      mapZoom: 'map/mapZoom',
      mapCenter: 'map/mapCenter',
    }),
    setActive(pointId) {
      const markers = document.getElementsByClassName('pulse');
      if (markers) {
        markers.forEach((marker) => {
          marker.classList.remove('active');
        });
      }
      const marker = document.getElementById(`marker-${pointId}`);
      if (marker) {
        marker.classList.add('active');
      }
    },
  },
  computed: {
    points: {
      get() { return this.$store.getters['point/points']; },
    },
    narrow: {
      get() { return this.$store.getters['map/narrow']; },
    },
    loading: {
      get() { return this.$store.getters['point/loading']; },
      set(value) { this.$store.dispatch('point/loading', value); },
    },
    page: {
      get() { return this.$store.getters['point/page']; },
    },
  },
  created() {
    this.$store.dispatch('categories/showBtn');

    this.mapNarrow(false);
    this.mapZoom(7);
    this.mapCenter({
      latlng: [52.302, 19.281],
    });
  },
  destroyed() {
    this.$store.dispatch('categories/hideBtn');
  },
};
</script>

<style lang="scss">
.loading {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height:100%;
}

.home-point-list {
  max-height: calc(100vh - 48px);
}

.list-point-rate {
  position: relative;
  top: 1px;
}

.list-tag-wrapper {
  width: 100%;
  max-width: calc(100% - 40px);
}
</style>
