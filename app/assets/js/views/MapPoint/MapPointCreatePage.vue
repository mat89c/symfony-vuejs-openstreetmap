<template>
  <v-container>
    <MapPointForm
      :title="title"
      :action="action"
      :mapPoint="defaultMapPoint"
    />
  </v-container>
</template>

<script>
import MapPointForm from '../../components/MapPointForm.vue'

export default {
  name: 'MapPointCreatePage',
  components: {
    MapPointForm,
  },
  data() {
    return {
      title: 'Dodaj punkt',
      action: 'onCreateMapPoint',
      defaultMapPoint: {
        title: '',
        street: '',
        postcode: '',
        city: '',
        description: '',
        color: '#ffaa00',
        lat: '',
        lng: '',
        logo: '',
        images: [],
        categories: [],
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
