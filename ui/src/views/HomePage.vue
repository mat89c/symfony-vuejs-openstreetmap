<template>
  <v-container fluid class="pa-0">
    <v-row class="ma-0">
      <v-col
        class="left-side pa-0"
        cols="12"
        md="3"
        :class="{wide: narrow == true, hideOnMobile: mobileShowMap}"
      >
        <router-view />
      </v-col>

      <v-col
        class="right-side pa-0 d-md-flex"
        cols="12"
        md="9"
        :class="{narrow: narrow == true, 'd-none': !mobileShowMap}"
      >
        <HomePointMap />
      </v-col>
    </v-row>

    <v-btn
      fixed
      fab
      bottom
      right
      class="hidden-md-and-up toggle-mobile-view-button"
      @click="toggleMobileView"
    >
      <v-icon v-if="mobileShowMap">mdi-format-list-bulleted-square</v-icon>
      <v-icon v-else>mdi-map</v-icon>
    </v-btn>

    <BottomSheet />
  </v-container>
</template>

<script>
import HomePointMap from '@/components/HomePointMap.vue';
import BottomSheet from '@/components/BottomSheet.vue';

export default {
  name: 'HomePage',
  components: {
    HomePointMap,
    BottomSheet,
  },
  methods: {
    toggleMobileView() {
      this.mobileShowMap = !this.mobileShowMap;
    },
  },
  computed: {
    narrow: {
      get() { return this.$store.getters['map/narrow']; },
    },
  },
  data() {
    return {
      mobileShowMap: false,
    };
  },
};
</script>

<style lang="scss">
  .left-side, .right-side {
    transition: width 1s ease, max-width 1s ease, flex 1s ease;
  }
  .wide {
    width: 60%;
    max-width: 60%;
    flex: 0 0 60%;

    @media (max-width: 958px) {
      width: 100%;
      max-width: 100%;
      flex: 0 0 100%;
    }
  }

  .narrow {
    width: 40%;
    max-width: 40%;
    flex: 0 0 40%;

    @media (max-width: 958px) {
      width: 100%;
      max-width: 100%;
      flex: 0 0 100%;
    }
  }

  .hideOnMobile {
    display: none!important;
  }

  .showOnMobile {
    display: flex!important;
  }

  .toggle-mobile-view-button {
    z-index: 99!important;
  }
</style>
