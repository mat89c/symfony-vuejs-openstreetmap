<template>
  <v-row class="point-info" :class="[(loading === true ? 'loading ma-0' : 'px-4')]">
    <v-col cols="2">
      <v-btn fab small to="/">
        <v-icon>mdi-backburger</v-icon>
      </v-btn>
    </v-col>
    <v-col cols="10 text-right">
      <em>
        <small class="d-block">Dodano przez: {{ username }}</small>
        <small class="d-block">Utworzono: {{ point.createdAt | date }}</small>
      </em>
    </v-col>
    <v-col cols="12">
      <v-img
        :src="logo.src"
        :alt="point.title"
      ></v-img>
    </v-col>
    <v-col cols="12">
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
        :src="img.thumb"
        aspect-radio="1"
        @click="openImage(index)"
      ></v-img>
    </v-col>

    <v-col cols="12">
      <h3>Opinie i oceny użytkowników</h3>
      <v-divider class="mb-2"></v-divider>
      <MapPointReviewCreate v-if="allowCreateReview"/>
      <MapPointReview v-if="reviews.length" :reviews="reviews" @onReviewUpdated="onReviewUpdated"/>
      <span v-else>Brak</span>
    </v-col>

    <LightBox
      ref="lightbox"
      :media="images"
      :show-light-box="false"
      :show-thumbs="false"
    ></LightBox>
  </v-row>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import LightBox from 'vue-image-lightbox';
import '../../node_modules/vue-image-lightbox/dist/vue-image-lightbox.min.css';
import MapPointReviewCreate from '@/components/MapPointReviewCreate.vue';
import MapPointReview from '@/components/MapPointReview.vue';

export default {
  name: 'MapPointPage',
  data() {
    return {
      images: [],
      username: '',
      logo: '',
      reviews: [],
    };
  },
  computed: {
    ...mapGetters({
      narrow: 'map/narrow',
      point: 'point/point',
      loading: 'point/loading',
    }),
    allowCreateReview() {
      return !this.reviews.some((review) => review.user.id === this.$store.getters['user/id']) && this.$store.getters['user/id'] !== '';
    },
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
    onReviewUpdated(index) {
      this.$delete(this.reviews, index);
    },
  },
  created() {
    this.mapNarrow(true);
    this.getMapPointById(this.$route.params.id)
      .then(() => {
        this.mapCenter({
          latlng: [this.point.lat, this.point.lng],
        });
        this.images = this.point.mapPointImage;
        this.username = this.point.user.name;
        this.logo = this.point.logo;
        this.reviews = this.point.reviews;
      });
    this.setActive(this.$route.params.id);
    this.mapZoom(16);
  },
  components: {
    LightBox,
    MapPointReviewCreate,
    MapPointReview,
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
