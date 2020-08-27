<template>
  <LMap
      ref="map"
      class="home-point-map"
      :zoom="zoom"
      :center="center"
      @update:bounds="boundsUpdated"
      :fadeAnimation="fadeAnimation"
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
      zoom: 'map/zoom',
      center: 'map/center',
    }),
  },
  methods: {
    boundsUpdated() {
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
    this.$store.dispatch('point/loading', true);
    this.$store.dispatch('point/getAllMapPoints').then(() => this.$store.dispatch('point/loading', false));
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
