<template>
  <LMap
      ref="map"
      class="home-point-map"
      :zoom="zoom"
      :center="center"
      @update:bounds="onBoundsUpdated()"
      @update:zoom="onZoomUpdated($event)"
    >
      <LTileLayer :url="url" :attribution="attribution"></LTileLayer>
      <HomePointMapMarker />
    </LMap>
</template>

<script>
import { mapGetters } from 'vuex';
import { LMap, LTileLayer } from 'vue2-leaflet';
import HomePointMapMarker from '@/components/HomePointMapMarker.vue';

export default {
  name: 'HomePointMap.vue',
  components: {
    LMap,
    LTileLayer,
    HomePointMapMarker,
  },
  computed: {
    ...mapGetters({
      center: 'map/center',
      checkedCategories: 'categories/checkedCategories',
      mapBounds: 'map/bounds',
      page: 'point/page',
    }),
    zoom: {
      get() { return this.$store.getters['map/zoom']; },
      set(zoom) { this.$store.dispatch('map/mapZoom', zoom); },
    },
  },
  methods: {
    onBoundsUpdated() {
      this.updateBounds();
      this.fixMapSize();
    },
    onZoomUpdated(zoom) {
      if (this.$route.name === 'HomePointList' && this.zoom !== zoom) {
        this.zoom = zoom;
        this.updateBounds();
        this.refreshMapPoints();
      }
    },
    refreshMapPoints() {
      this.$store.dispatch('point/loading', true);
      this.$store.dispatch('point/getAllMapPoints', {
        checkedCategories: this.checkedCategories,
        mapBounds: this.mapBounds,
        page: this.page,
      }).finally(() => this.$store.dispatch('point/loading', false));
    },
    updateBounds() {
      const bounds = this.$refs.map.mapObject.getBounds();
      this.$store.dispatch('map/setBounds', {
        southWest: bounds.getSouthWest(),
        northEast: bounds.getNorthEast(),
      });

      this.$store.dispatch('point/setPage', 1);
    },
    fixMapSize() {
      if (this.timeout) clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        if (typeof this.$refs.map !== 'undefined') {
          this.$refs.map.mapObject.invalidateSize();
        }
      }, 500);
    },
  },
  data() {
    return {
      url: 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      fadeAnimation: true,
    };
  },
  created() {
    this.refreshMapPoints();
  },
};
</script>

<style lang="scss">
  .home-point-map {
    z-index: 5;
    height: calc(100vh - 48px)!important;
    min-width: 100%;
  }
</style>
