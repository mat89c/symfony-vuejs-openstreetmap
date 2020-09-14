<template>
  <v-container>
    <v-row>
      <v-col>
        <v-data-table
          :headers="headers"
          :items="users"
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
              <v-toolbar-title>Lista użytkowników</v-toolbar-title>
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

          <template v-slot:item.roles="{ item }">
            {{ item.roles | roles }}
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
import getAllUsers from '../api/user/getAllUsers';
import deleteUser from '../api/user/deleteUser';

export default {
  name: 'UsersPage',
  components: {
    DialogConfirm,
  },
  data () {
    return {
      page: 1,
      pageCount: 0,
      itemsPerPage: 0,
      totalItems: 0,
      users: [],
      status: null,
      loading: true,
      headers: [
        { text: '#', align: 'start', value: 'id' },
        { text: 'Nazwa', value: 'name' },
        { text: 'E-mail', value: 'email' },
        { text: 'Dodanych punktów', value: 'countMapPoint' },
        { text: 'Dodanych opinii', value: 'countReview' },
        { text: 'Role', value: 'roles' },
        { text: 'Status', value: 'isActive' },
        { text: 'Akcje', value: 'actions' },
      ],
      statuses: [],
    };
  },
  created() {
    this.statuses = statuses;
    this.fetchUsers(this.page, this.status);
  },
  methods: {
    onPageUpdated(page) {
      this.loading = true;
      this.fetchUsers(page, this.status);
    },
    onDelete(id) {
      this.$refs.confirm.open('Usuń użytkownika', 'Czy usunąć użytkownika?')
        .then((confirm) => {
          if (confirm) {
            this.$store.dispatch('dialogloader/show', 'Trwa usuwanie użytkownika...');

            deleteUser(id)
              .then((response) => {
                const index = this.users.findIndex((item) => item.id === id);
                this.$delete(this.users, index);

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
      this.fetchUsers(this.page, this.status);
    },
    fetchUsers(page, status) {
      getAllUsers(page, status)
        .then((response) => {
          this.users = response.data.data.users;
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
