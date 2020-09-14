<template>
  <v-container>
    <v-row>
      <v-col>
        <v-data-table
          :headers="headers"
          :items="tags"
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
              <v-toolbar-title>Lista tagów</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn>
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

          <template v-slot:item.isActive="{ item }">
            {{ item.isActive ? 'Aktywny' : 'Nieaktywny' }}
          </template>

          <template v-slot:item.actions="{ item }">
            <div class="d-flex flex-nowrap">
              <v-btn icon small>
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
import getAllTags from '../api/tag/getAllTags';
import deleteTag from '../api/tag/deleteTag';

export default {
  name: 'TagsPage',
  components: {
    DialogConfirm,
  },
  data () {
    return {
      page: 1,
      pageCount: 0,
      itemsPerPage: 0,
      totalItems: 0,
      tags: [],
      status: null,
      loading: true,
      headers: [
        { text: '#', align: 'start', value: 'id' },
        { text: 'Nazwa', value: 'name' },
        { text: 'Ilość przypisanych punktów', value: 'countMapPoint' },
        { text: 'Status', value: 'isActive' },
        { text: 'Akcje', value: 'actions' },
      ],
      statuses: [],
    };
  },
  created() {
    this.statuses = statuses;
    this.fetchTags(this.page, this.status);
  },
  methods: {
    onPageUpdated(page) {
      this.loading = true;
      this.fetchTags(page, this.status);
    },
    onDelete(id) {
      this.$refs.confirm.open('Usuń tag', 'Czy usunąć tag?')
        .then((confirm) => {
          if (confirm) {
            this.$store.dispatch('dialogloader/show', 'Trwa usuwanie...');

            deleteTag(id)
              .then((response) => {
                const index = this.tags.findIndex((item) => item.id === id);
                this.$delete(this.tags, index);

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
      this.fetchTags(this.page, this.status);
    },
    fetchTags(page, status) {
      getAllTags(page, status)
        .then((response) => {
          this.tags = response.data.data.tags;
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
