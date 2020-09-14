<template>
  <v-card>
    <v-img
      :src="point.logo"
      height="250"
    ></v-img>

    <v-card-title>{{ point.title }}</v-card-title>

    <v-card-subtitle>{{ point.postcode }} {{ point.city }}, {{ point.street }}</v-card-subtitle>

    <v-card-text>
      <div>Dodano przez: {{ point.user.name }}</div>
      <div>Dodano dnia: {{ point.createdAt | date }}</div>
      <div class="mb-2">Status: {{ point.isActive ? 'Aktywny' : 'Nieaktywny' }}</div>
      <v-chip
        v-for="(item, index) in point.mapPointCategories"
        :key="index"
        small
        class="mr-1 mt-1"
      >{{ item.name }}</v-chip>
    </v-card-text>

    <v-card-actions class="text-right">
      <v-btn small>
        <v-icon left>mdi-eye</v-icon>
        Zobacz
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: 'LastAddedMapPoints',
  props: {
    point: {
      type: Object,
      required: true,
    },
  },
};
</script>
