<template>
  <div>
    <LMarker
        v-for="point in points"
        :key="point.id"
        :lat-lng="[point.lat, point.lng]"
        @click="toPointPage(point.id)"
        @mouseover="onHover(point.id, point.logo)"
      >
        <LIcon
          :icon-anchor="[0, 22.5]"
        >
          <div class='myBounceDiv'>
            <div :style="{'--bg-color': point.color}" class='pin'></div>
              <div
                :style="{'--bg-color': point.color}"
                class='pulse'
                :id="`marker-${point.id}`"
              ></div>
          </div>
        </LIcon>
        <LTooltip class="tooltip">
          <div>
            <img
              class="tooltip__image"
              :data-src="point.logo.thumb"
              :alt="point.title"
              :id="imageId(point.id)"
            >
          </div>
          <div class="tooltip__info">
            {{ point.title }}
            <br>
            <v-chip
              x-small
              class="mr-1"
              v-for="category in point.mapPointCategories"
              :key="category.id"
            >{{ category.name }}</v-chip>
          </div>
        </LTooltip>
      </LMarker>
  </div>
</template>

<script>
import { LMarker, LIcon, LTooltip } from 'vue2-leaflet';
import { mapGetters } from 'vuex';

export default {
  name: 'HomePointMapMarker',
  components: {
    LMarker,
    LIcon,
    LTooltip,
  },
  computed: {
    ...mapGetters({
      points: 'point/points',
      active: 'mapmarker/active',
    }),
  },
  methods: {
    toPointPage(id) {
      this.$router.push({ name: 'MapPointPage', params: { id } });
    },
    imageId(id) {
      return `image-${id}`;
    },
    onHover(id, src) {
      if (this.$el.querySelector(`#image-${id}`)) {
        this.$el.querySelector(`#image-${id}`).setAttribute('src', src);
      }
    },
  },
};
</script>

<style lang="scss">
.pulse {
  background: rgba(0,0,0,0.3);
  border-radius: 50%;
  height: 14px;
  width: 14px;
  position: absolute;
  left: 50%;
  top: 50%;
  margin: 11px 0px 0px -12px;
  transform: rotateX(55deg);
  z-index: -2;
  box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
  &.active:after {
    content: "";
    border-radius: 50%;
    height: 40px;
    width: 40px;
    position: absolute;
    margin: -13px 0 0 -13px;
    animation: pulsate 1s ease-out;
    animation-iteration-count: infinite;
    opacity: 0;
    box-shadow: 0 0 1px 7px var(--bg-color);
    animation-delay: 0.1s;
  }
}

.tooltip {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;

  &__image {
    width: 60px;
    margin-right: 10px;
  }

  &__info {
    width: 190px;
    white-space: pre-wrap;
  }
}

@keyframes pulsate {
  0%, 100% {
    transform: scale(0.1, 0.1);
    opacity: 0;
  }

  50% {
    opacity: 1;
  }

  100% {
    transform: scale(1.2, 1.2);
    opacity: 0;
  }
}
</style>
