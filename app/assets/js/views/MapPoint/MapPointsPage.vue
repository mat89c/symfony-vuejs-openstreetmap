<template>
  <v-container>
    <v-row>
      <v-col>
        <v-data-table
          :headers="headers"
          :items="mapPoints"
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
              <v-toolbar-title>Lista punktów</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn :to="{ name: 'PointCreatePage' }">
                <v-icon left>mdi-map-marker-plus</v-icon>
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

          <template v-slot:item.createdAt="{ item }">
            {{ item.createdAt | date }}
          </template>

          <template v-slot:item.isActive="{ item }">
            {{ item.isActive ? 'Aktywny' : 'Nieaktywny' }}
          </template>

          <template v-slot:item.actions="{ item }">
            <div class="d-flex flex-nowrap">
              <v-btn icon small :to="{ name: 'PointUpdatePage', params: { id: item.id } }">
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
import getAllMapPoints from '../../api/point/getAllMapPoints';
import DialogConfirm from '../../components/DialogConfirm.vue';
import deleteMapPoint from '../../api/point/deleteMapPoint';
import statuses from '../../variables/statuses';

export default {
  name: 'MapPointsPage',
  components: {
    DialogConfirm,
  },
  props: {
    isActive: {
      type: Number,
      required: false,
      default: null
    },
  },
  data () {
    return {
      page: 1,
      pageCount: 0,
      itemsPerPage: 0,
      totalItems: 0,
      mapPoints: [],
      status: null,
      loading: true,
      headers: [
        { text: '#', align: 'start', value: 'id' },
        { text: 'Nazwa', value: 'title' },
        { text: 'Miasto', value: 'city' },
        { text: 'Ocena', value: 'rating' },
        { text: 'Liczba opinii', value: 'numberOfReviews' },
        { text: 'Dodano przez', value: 'user.name' },
        { text: 'Utworzono', value: 'createdAt' },
        { text: 'Status', value: 'isActive'},
        { text: 'Akcje', value: 'actions' },
      ],
      statuses: [],
    };
  },
  created() {
    this.statuses = statuses;
    if (this.isActive !== null) {
      this.status = this.isActive;
    }
    this.fetchMapPoints(this.page, this.status);
  },
  methods: {
    onPageUpdated(page) {
      this.loading = true;
      this.fetchMapPoints(page, this.status);
    },
    onDelete(id) {
      this.$refs.confirm.open('Usuń punkt', 'Czy usunąć punkt?')
        .then((confirm) => {
          if (confirm) {
            this.$store.dispatch('dialogloader/show', 'Trwa usuwanie punktu...');

            deleteMapPoint(id)
              .then((response) => {
                const index = this.mapPoints.findIndex((item) => item.id === id);
                this.$delete(this.mapPoints, index);
                this.totalItems -= 1;

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
      this.fetchMapPoints(this.page, this.status);
    },
    fetchMapPoints(page, status) {
      getAllMapPoints(page, status)
        .then((response) => {
          this.mapPoints = response.data.data.mapPoints;
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
