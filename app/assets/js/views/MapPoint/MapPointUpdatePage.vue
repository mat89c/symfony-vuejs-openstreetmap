<template>
  <v-container>
    <MapPointForm
      :title="title"
      :action="action"
      :mapPoint="mapPoint"
    />
  </v-container>
</template>

<script>
import MapPointForm from '../../components/MapPointForm.vue'
import getMapPointById from '../../api/point/getMapPointById'

export default {
  name: 'MapPointUpdatePage',
  components: {
    MapPointForm,
  },
  created() {
    getMapPointById(this.$route.params.id)
      .then((response) => {
        this.mapPoint = response.data.data;
      });
  },
  data() {
    return {
      title: 'Edytuj punkt',
      action: 'onUpdateMapPoint',
      mapPoint: {
        logo: {
          src: ''
        },
        mapPointCategories: [],
        newMapPointImages: [],
        newLogo: null,
        user: {
          id: null,
        },
      },
    };
  },
};
</script>
