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

              <v-dialog v-model="dialog" max-width="500px">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon left>mdi-account</v-icon>
                    Dodaj
                  </v-btn>
                </template>
                <v-card>
                  <v-toolbar dense flat>
                    <v-toolbar-title>{{ dialogTitle }}</v-toolbar-title>
                  </v-toolbar>

                  <v-card-text>
                    <v-text-field
                      label="Nazwa"
                      v-model="editedItem.name"
                      :rules="$rules.required"
                      autofocus
                      class="mt-3"
                    ></v-text-field>

                    <v-switch
                      class="mt-5"
                      v-model="editedItem.isActive"
                      :label="`Status: ${tagStatus}`"
                    ></v-switch>
                  </v-card-text>

                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="onSaveTag">Zapisz</v-btn>
                    <v-btn @click="closeDialog">Anuluj</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>

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
              <v-btn icon small @click="onEdit(item)">
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
import getAllTagsWithPagination from '../api/tag/getAllTagsWithPagination';
import deleteTag from '../api/tag/deleteTag';
import createTag from '../api/tag/createTag';
import updateTag from '../api/tag/updateTag';

export default {
  name: 'TagsPage',
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
  computed: {
    dialogTitle() {
      return this.editedIndex === -1 ? 'Dodaj tag' : 'Edytuj tag'
    },
    tagStatus() {
      return this.editedItem.isActive ? 'Aktywny' : 'Nieaktywny';
    },
  },
  data () {
    return {
      dialog: false,
      editedIndex: -1,
      page: 1,
      pageCount: 0,
      itemsPerPage: 0,
      totalItems: 0,
      editedItem: {
        id: null,
        name: '',
        isActive: false,
        countMapPoint: 0,
      },
      defaultItem: {
        id: null,
        name: '',
        isActive: false,
        countMapPoint: 0,
      },
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
    if (this.isActive !== null) {
      this.status = this.isActive;
    }
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
      getAllTagsWithPagination(page, status)
        .then((response) => {
          this.tags = response.data.data.tags;
          this.itemsPerPage = response.data.data.itemsPerPage;
          this.totalItems = response.data.data.totalItems;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    onSaveTag() {
      if (this.editedIndex > -1) {
        this.$store.dispatch('dialogloader/show', 'Trwa aktualizacja...');

        updateTag(this.editedItem)
          .then((response) => {
            Object.assign(this.tags[this.editedIndex], response.data.data);
            this.closeDialog();
          })
          .finally(() => this.$store.dispatch('dialogloader/hide'));
      } else {
        this.$store.dispatch('dialogloader/show', 'Trwa dodawanie...');

        createTag(this.editedItem)
          .then((response) => {
            this.tags.unshift(response.data.data);
            this.closeDialog();
          })
          .finally(() => this.$store.dispatch('dialogloader/hide'));
      }
    },
    onEdit(item) {
      this.editedIndex = this.tags.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    closeDialog() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
  },
};
</script>
