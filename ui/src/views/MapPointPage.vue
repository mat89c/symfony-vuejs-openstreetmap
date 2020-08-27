<template>
  <v-row class="point-info" :class="[(loading === true ? 'loading ma-0' : 'px-4')]">
    <v-col cols="2" sm="1">
      <v-btn fab small to="/">
        <v-icon>mdi-backburger</v-icon>
      </v-btn>
    </v-col>
    <v-col cols="10" sm="3">
      <v-avatar
        class="avatar"
        color="white"
        size="150"
      >
        <img class="avatar__image" :src="point.logo" :alt="point.title">
      </v-avatar>
    </v-col>
    <v-col cols="12" sm="8">
      <h1>{{ point.title }}</h1>
      <p>{{ point.postcode }} {{ point.city }}, {{ point.street }}</p>
      <v-divider></v-divider>
    </v-col>
    <v-col cols="12">
      <p v-html="point.description"></p>
    </v-col>
    <v-col v-for="(img, index) in point.mapPointImage" cols="12" md="4" :key="index">
      <v-img
        class="point-image"
        :src="img"
        aspect-radio="1"
        @click="openImage(index)"
      ></v-img>
    </v-col>

    <LightBox
      ref="lightbox"
      :media="point.mapPointImage ? point.mapPointImage : Array()"
      :show-light-box="false"
      :show-thumbs="false"
    ></LightBox>
  </v-row>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import LightBox from 'vue-image-lightbox';
import '../../node_modules/vue-image-lightbox/dist/vue-image-lightbox.min.css';

export default {
  name: 'MapPointPage',
  computed: {
    ...mapGetters({
      narrow: 'map/narrow',
      point: 'point/point',
      loading: 'point/loading',
    }),
  },
  methods: {
    ...mapActions({
      mapNarrow: 'map/mapNarrow',
      getMapPointById: 'point/getMapPointById',
      setActive: 'mapmarker/setActive',
      mapZoom: 'map/mapZoom',
      mapCenter: 'map/mapCenter',
    }),
    openImage(id) {
      this.$refs.lightbox.showImage(id);
    },
  },
  created() {
    this.mapNarrow(true);
    this.getMapPointById(this.$route.params.id)
      .then(() => {
        this.mapCenter({
          latlng: [this.point.lat, this.point.lng],
        });
      });
    this.setActive(this.$route.params.id);
    this.mapZoom(16);
  },
  props: ['id'],
  components: {
    LightBox,
  },
};
</script>

<style lang="scss">
.point-info {
  max-height: calc(100vh - 48px);
  overflow: auto;
}

.loading {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height:100%;
}

.avatar {
  box-shadow: 0px 0px 20px 0px rgba(255,255,255,0.5);

  &__image {
    padding: 30px;
    border-radius: 0!important;
  }
}

.point-image {
  cursor: pointer
}
</style>
