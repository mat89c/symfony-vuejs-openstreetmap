<template>
  <v-container>
    <v-row class="mt-5">
      <v-col cols="12" sm="3">
         <DashboardStatistics
          :inactives="inactiveUsers"
          :title="cardItems.inactiveUsers.title"
          :icon="cardItems.inactiveUsers.icon"
        />
      </v-col>

      <v-col cols="12" sm="3">
         <DashboardStatistics
          :inactives="inactiveMapPoints"
          :title="cardItems.inactiveMapPoints.title"
          :icon="cardItems.inactiveMapPoints.icon"
        />
      </v-col>

      <v-col cols="12" sm="3">
        <DashboardStatistics
          :inactives="inactiveReviews"
          :title="cardItems.inactiveReviews.title"
          :icon="cardItems.inactiveReviews.icon"
        />
      </v-col>

      <v-col cols="12" sm="3">
        <DashboardStatistics
          :inactives="inactiveCategories"
          :title="cardItems.inactiveCategories.title"
          :icon="cardItems.inactiveCategories.icon"
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <v-divider></v-divider>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <h3>Ostatnio dodane punkty</h3>
      </v-col>
      <v-col cols="12" sm="4" v-for="(point, index) in lastAddedMapPoints" :key="index">
        <LastAddedMapPoints :point="point"/>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import DashboardStatistics from '../components/DashboardStatistics.vue';
import getDashboardData from '../api/dashboard/getDashboardData';
import dashboardCards from '../variables/dashboardCards';
import LastAddedMapPoints from '../components/LastAddedMapPoints.vue';

export default {
  name: 'HomePage',
  data() {
    return {
      inactiveUsers: '',
      inactiveMapPoints: '',
      inactiveReviews: '',
      inactiveCategories: '',
      cardItems: {},
      lastAddedMapPoints: [],
    };
  },
  components: {
    DashboardStatistics,
    LastAddedMapPoints,
  },
  created() {
    this.cardItems = dashboardCards;

    this.$store.dispatch('dialogloader/show', 'Pobieranie danych...');
    getDashboardData()
      .then((response) => {
        this.inactiveUsers = response.data.data.inactiveUsers;
        this.inactiveMapPoints = response.data.data.inactiveMapPoints;
        this.inactiveReviews = response.data.data.inactiveReviews;
        this.inactiveCategories = response.data.data.inactiveCategories;
        this.lastAddedMapPoints = response.data.data.lastAddedMapPoints;
      })
      .finally(() => this.$store.dispatch('dialogloader/hide'));
  },
};
</script>
