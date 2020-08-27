<template>
  <div
    class="home-point-list pa-0 overflow-y-auto"
    :class="{loading: loading === true}"
  >
    <v-progress-linear
      v-if="loading"
      indeterminate
    ></v-progress-linear>
    <v-progress-linear
      v-if="loading"
      indeterminate
    ></v-progress-linear>
    <v-list-item
      v-else
      class="py-0"
      two-line
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
      </v-list-item-content>
    </v-list-item>
  </div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
  name: 'HomePointList',
  methods: {
    ...mapActions({
      setActive: 'mapmarker/setActive',
      mapNarrow: 'map/mapNarrow',
      mapZoom: 'map/mapZoom',
      mapCenter: 'map/mapCenter',
    }),
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
  },
  created() {
    this.mapNarrow(false);
    this.mapZoom(7);
    this.mapCenter({
      latlng: [52.302, 19.281],
    });
  },
  filters: {
    stripTags: (value) => {
      if (!value) return '';
      return value.replace(/(<([^>]+)>)/ig, '');
    },
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
</style>
