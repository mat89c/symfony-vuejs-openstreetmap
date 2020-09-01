<template>
  <v-bottom-sheet v-model="visibility">
    <v-sheet class="text-center" height="200px">
      <v-btn
        absolute
        right
        icon
        class="mt-1"
        @click="visibility = false"
      >
        <v-icon>mdi-window-close</v-icon>
      </v-btn>
      <div class="py-3 px-12">
        Szukaj punktów za pomocą tagów.
      </div>
      <v-chip-group
        v-model="checkedCategories"
        column
        multiple
        class="px-5 categories-sheet"
        @click="getMapPointsByCategory()"
      >
        <v-chip
          filter
          v-for="category in categories"
          :key="category.id"
          :value="category.id"
        >
          {{ category.name }}
        </v-chip>
      </v-chip-group>
    </v-sheet>
  </v-bottom-sheet>
</template>

<script>
import getAllCategories from '@/api/category/getAllCategories';

export default {
  name: 'BottomSheet',
  created() {
    getAllCategories()
      .then((response) => {
        if (typeof response.data.data !== 'undefined') {
          this.categories = response.data.data;
        }
      });
  },
  computed: {
    checkedCategories: {
      get() { return this.$store.getters['categories/checkedCategories']; },
      set(value) {
        this.$store.dispatch('categories/setCheckedCategories', value);
        this.$store.dispatch('point/loading', true);
        this.$store.dispatch('point/getAllMapPoints', this.checkedCategories).then(() => this.$store.dispatch('point/loading', false));
      },
    },
    visibility: {
      get() { return this.$store.getters['categories/bottomSheetVisibility']; },
      set(value) { this.$store.dispatch('categories/setBottomSheetVisibility', value); },
    },
  },
  data() {
    return {
      categories: [],
    };
  },
};
</script>

<style lang="scss">
.categories-sheet {
   .v-slide-group__content {
     justify-content: center;
   }
}
</style>
