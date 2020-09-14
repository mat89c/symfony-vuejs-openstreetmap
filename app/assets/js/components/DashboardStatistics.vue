<template>
  <v-card>
    <v-card-title class="flex justify-space-between">
      <v-avatar
        color="grey"
        rounded="rounded"
        size="50"
        class="dashboard__avatar"
      >
        <v-icon size="30">{{ icon }}</v-icon>
      </v-avatar>
      <div class="text-right dashboard__stats">
        <span class="grey--text text-caption dashboard__stats-title">{{ title }}</span>
        <h3 class="text-h4">{{ inactives }}</h3>
      </div>
    </v-card-title>
    <v-card-subtitle>
      <v-divider></v-divider>
      <v-btn
        right
        small
        class="mt-2"
      >
        <v-icon left>mdi-eye</v-icon>
        Zobacz
      </v-btn>
    </v-card-subtitle>
  </v-card>
</template>

<script>
export default {
  name: 'DashboardStatistics',
  props: {
    inactives: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      required: true,
    },
    icon: {
      type: String,
      required: true,
    }
  },
};
</script>

<style lang="scss">
.dashboard {
  &__avatar {
    position: relative;
    top: -40px;
  }

  &__stats {
    position: relative;
    top: -20px;

    @media (max-width: 1263px) {
      width: 100%;
    }

    &-title {
      white-space: nowrap;
    }
  }
}
</style>
