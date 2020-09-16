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
                  <v-form @submit.prevent="onSaveItem" ref="form">
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

                      <v-text-field
                        label="E-mail"
                        v-model="editedItem.email"
                        :rules="$rules.required && $rules.email"
                      ></v-text-field>

                      <v-select
                        label="Uprawnienia"
                        :items="userRoles"
                        v-model="editedItem.role"
                        :rules="$rules.required"
                      ></v-select>

                      <v-switch
                        v-if="isEditing"
                        v-model="showPasswordFields"
                        label="Czy zmienić hasło?"
                      ></v-switch>

                      <v-text-field
                        v-if="showPasswordFields || !isEditing"
                        label="Hasło"
                        v-model="editedItem.password"
                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="showPassword ? 'text' : 'password'"
                        @click:append="showPassword = !showPassword"
                        :rules="$rules.required && $rules.minLength"
                      ></v-text-field>

                      <v-text-field
                        v-if="showPasswordFields || !isEditing"
                        label="Powtórz hasło"
                        v-model="password"
                        :append-icon="showPassword2 ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="showPassword2 ? 'text' : 'password'"
                        @click:append="showPassword2 = !showPassword2"
                        :rules="$rules.required"
                      ></v-text-field>

                      <v-switch
                        class="mt-5"
                        v-model="editedItem.isActive"
                        :label="`Status: ${userStatus}`"
                      ></v-switch>
                    </v-card-text>

                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn type="submit">Zapisz</v-btn>
                      <v-btn @click="closeDialog">Anuluj</v-btn>
                    </v-card-actions>
                  </v-form>
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

          <template v-slot:item.roles="{ item }">
            {{ item.roles | roles }}
          </template>

          <template v-slot:item.isActive="{ item }">
            {{ item.isActive ? 'Aktywny' : 'Nieaktywny' }}
          </template>

          <template v-slot:item.actions="{ item }">
            <div class="d-flex flex-nowrap">
              <v-btn icon small @click="onEdit(item)">
                <v-icon small>mdi-pencil</v-icon>
              </v-btn>

              <v-btn icon small @click="onSendEmailMessage(item.email, item.name)">
                <v-icon small>mdi-email</v-icon>
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
    <DialogEmailMessage ref="email" />
  </v-container>
</template>

<script>
import statuses from '../../variables/statuses';
import DialogConfirm from '../../components/DialogConfirm.vue';
import getAllUsers from '../../api/user/getAllUsers';
import deleteUser from '../../api/user/deleteUser';
import updateUser from '../../api/user/updateUser';
import createUser from '../../api/user/createUser';
import DialogEmailMessage from '../../components/DialogEmailMessage';

export default {
  name: 'UsersPage',
  components: {
    DialogConfirm,
    DialogEmailMessage,
  },
  props: {
    isActive: {
      type: Number,
      required: false,
      default: null
    },
  },
  computed: {
    isEditing() {
      return this.editedIndex !== -1;
    },
    dialogTitle() {
      return this.editedIndex === -1 ? 'Dodaj użytkownika' : 'Edytuj użytkownika'
    },
    userStatus() {
      return this.editedItem.isActive ? 'Aktywny' : 'Nieaktywny';
    },
  },
  data () {
    return {
      showPassword: '',
      showPassword2: '',
      showPasswordFields: false,
      password: '',
      dialog: false,
      editedIndex: -1,
      page: 1,
      pageCount: 0,
      itemsPerPage: 0,
      totalItems: 0,
      editedItem: {
        id: null,
        name: '',
        password: '',
        email: '',
        isActive: false,
        role: '',
      },
      defaultItem: {
        id: null,
        name: '',
        password: '',
        email: '',
        isActive: false,
        role: '',
      },
      userRoles:[
        {
          text: 'Adminisrator',
          value: 'ROLE_ADMIN',
        },
        {
          text: 'Użytkownik',
          value: 'ROLE_USER',
        },
      ],
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
      passwordComparision: [
        (v) => this.editedItem.password === v || 'Podane hasła różnią się.',
      ],
    };
  },
  created() {
    this.statuses = statuses;
    if (this.isActive !== null) {
      this.status = this.isActive;
    }
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
    onSaveItem() {
      if (!this.$refs.form.validate()) return;

      if (this.editedIndex > -1) {
        this.$store.dispatch('dialogloader/show', 'Trwa aktualizacja...');

        updateUser(this.editedItem)
          .then((response) => {
            Object.assign(this.users[this.editedIndex], response.data.data);
            this.closeDialog();
          })
          .finally(() => this.$store.dispatch('dialogloader/hide'));
      } else {
        this.$store.dispatch('dialogloader/show', 'Trwa dodawanie...');

        createUser(this.editedItem)
          .then((response) => {
            this.users.unshift(response.data.data);
            this.closeDialog();
          })
          .finally(() => this.$store.dispatch('dialogloader/hide'));
      }
    },
    onEdit(item) {
      this.editedIndex = this.users.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editedItem.role = item.roles[0];
      this.dialog = true;
    },
    closeDialog() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
    onSendEmailMessage(receiverEmail, receiverName) {
      this.$refs.email.open(receiverEmail, receiverName);
    },
  },
};
</script>
