<template>
  <v-container>
    <v-row>
      <v-col>
        <v-data-table
          :headers="headers"
          :items="reviews"
          :items-per-page="itemsPerPage"
          :server-items-length="totalItems"
          :page.sync="page"
          @update:page="onPageUpdated"
          @page-count="pageCount = $event"
          :loading="loading"
          disable-sort
          hide-default-footer
          class="elevation-1"
        >

          <template v-slot:top>
            <v-toolbar flat>
              <v-toolbar-title>Lista opinii</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn :to="{ name: 'ReviewCreatePage' }">
                <v-icon left>mdi-account</v-icon>
                Dodaj
              </v-btn>
              <v-select
                class="ml-2 statuses"
                solo
                placeholder="Status"
                clearable
                dense
                :items="statuses"
                label="Status"
                v-model="status"
                @change="onStatusUpdate"
              ></v-select>
            </v-toolbar>
          </template>

          <template v-slot:item.content="{ item }">
            {{ item.content | stripTags | trimReview }}
          </template>

          <template v-slot:item.createdAt="{ item }">
            {{ item.createdAt | date }}
          </template>

          <template v-slot:item.isActive="{ item }">
            {{ item.isActive ? 'Aktywny' : 'Nieaktywny' }}
          </template>

          <template v-slot:item.actions="{ item }">
            <div class="d-flex flex-nowrap">
              <v-btn :to="{ name: 'ReviewUpdatePage', params: { id: item.id } }"  icon small>
                <v-icon small>mdi-pencil</v-icon>
              </v-btn>

              <v-btn icon small @click="onDelete(item.id)">
                <v-icon small>mdi-delete</v-icon>
              </v-btn>
            </div>
          </template>
        </v-data-table>
        <v-pagination v-model="page" :length="pageCount"></v-pagination>
      </v-col>
    </v-row>

    <DialogConfirm ref="confirm"/>
  </v-container>
</template>

<script>
import statuses from '../variables/statuses';
import DialogConfirm from '../components/DialogConfirm.vue';
import getAllReviews from '../api/review/getAllReviews'
import deleteReview from '../api/review/deleteReview';

export default {
  name: 'ReviewsPage',
  components: {
    DialogConfirm,
  },
  data () {
    return {
      page: 1,
      pageCount: 0,
      itemsPerPage: 0,
      totalItems: 0,
      reviews: [],
      status: null,
      loading: true,
      headers: [
        { text: '#', align: 'start', value: 'id' },
        { text: 'Treść', value: 'content' },
        { text: 'Ocena', value: 'rating' },
        { text: 'Dodano przez', value: 'user.name' },
        { text: 'Status', value: 'isActive' },
        { text: 'Utworzono', value: 'createdAt' },
        { text: 'Punkt', value: 'mapPoint.title' },
        { text: 'Akcje', value: 'actions' },
      ],
      statuses: [],
    };
  },
  created() {
    this.statuses = statuses;
    this.fetchReviews(this.page, this.status);
  },
  methods: {
    onPageUpdated(page) {
      this.loading = true;
      this.fetchReviews(page, this.status);
    },
    onDelete(id) {
      this.$refs.confirm.open('Usuń opinię', 'Czy usunąć opinię?')
        .then((confirm) => {
          if (confirm) {
            this.$store.dispatch('dialogloader/show', 'Trwa usuwanie...');

            deleteReview(id)
              .then((response) => {
                const index = this.reviews.findIndex((item) => item.id === id);
                this.$delete(this.reviews, index);

                this.$store.dispatch('dialogpopup/show', {
                  title: response.data.title,
                  message: response.data.message,
                });
              })
              .finally(() => this.$store.dispatch('dialogloader/hide'));
          }
        });
    },
    onStatusUpdate() {
      this.loading = true;
      this.fetchReviews(this.page, this.status);
    },
    fetchReviews(page, status) {
      getAllReviews(page, status)
        .then((response) => {
          this.reviews = response.data.data.reviews;
          this.itemsPerPage = response.data.data.itemsPerPage;
          this.totalItems = response.data.data.totalItems;
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>
