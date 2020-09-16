<template>
  <v-row>
    <v-col cols="12">
      <h1>{{ title }}</h1>
    </v-col>
    <v-col cols="12">
      <v-form @submit.prevent="handleFunctionCall(action)" ref="form">
        <v-row justify="center">
          <v-col md="6">
              <v-text-field
                label="Nazwa"
                v-model="mapPoint.title"
                :rules="$rules.required"
                autofocus
              ></v-text-field>
              <v-text-field
                label="Ulica"
                v-model="mapPoint.street"
                :rules="$rules.required"
              >
              </v-text-field>
              <v-row>
                <v-col md="4">
                  <v-text-field
                    label="Kod pocztowy"
                    return-masked-value
                    v-mask="mask"
                    v-model="mapPoint.postcode"
                    :rules="$rules.required && $rules.postcode"
                  ></v-text-field>
                </v-col>
                <v-col md="8">
                  <v-text-field
                    label="Miasto"
                    v-model="mapPoint.city"
                    :rules="$rules.required"
                  ></v-text-field>
                </v-col>
              </v-row>
              <h4>Opis</h4>
              <ckeditor
                :editor="editor"
                v-model="mapPoint.description"
                :config="editorConfig"
              ></ckeditor>
              <v-combobox
                v-model="checkedCategories"
                :items="pointCategories"
                :search-input.sync="pointCategorySearch"
                hide-selected
                clearable
                hint="Jeśli tag którego szukasz nie istnieje, możesz dodać nowy."
                label="Dodaj powiązane tagi. Maks 5 tagów."
                multiple
                persistent-hint
                small-chips
                class="mt-8"
                :rules="$rules.comboboxRequired"
              >
                <template v-slot:no-data>
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title>
                        Nie znaleziono tagów "<strong>{{ pointCategorySearch }}</strong>".
                        Naciśnij <kbd>enter</kbd>, aby dodać nowy tag.
                      </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </template>
              </v-combobox>

              <v-img
                class="mt-6"
                :src="mapPoint.logo.src"
              ></v-img>

              <v-file-input
                class="mt-10"
                label="Zdjęcie główne"
                accept="image/*"
                @change="processLogoImage"
                counter
                show-size
                :rules="$rules.required && $rules.logoSize"
              ></v-file-input>

              <v-row>
                <v-col
                  v-for="(img, index) in mapPoint.mapPointImage"
                  class="d-flex child-flex"
                  :key="index"
                  cols="12"
                  md="4">
                  <v-card flat tile class="d-flex">
                    <v-img
                      class="point-image"
                      :src="img.thumb"
                      aspect-radio="1"
                      @click.stop="openImage(index)"
                    >
                      <v-btn
                        x-small
                        absolute
                        right
                        fab
                        color="gray"
                        class="mt-1"
                        @click.stop="onDeleteMapPointImage(img.id, index)"
                      >
                        <v-icon>mdi-delete</v-icon>
                      </v-btn>
                    </v-img>
                  </v-card>
                </v-col>
              </v-row>

              <v-file-input
                label="Galeria obrazów"
                accept="image/*"
                @change="processImages"
                counter
                multiple
                show-size
                :rules="$rules.imagesSize"
              ></v-file-input>
          </v-col>
        </v-row>
        <v-row justify="center">
          <v-col md="6">
            <p class="add-point__info mb-0">*Wybierz kolor ikony na mapie</p>
            <v-color-picker
              class="mx-auto"
              v-model="mapPoint.color"
              hide-inputs
            ></v-color-picker>
          </v-col>
        </v-row>
        <v-row justify="center">
          <v-col md="6">
            <em class="text-caption">*Przeciągnij marker, aby ustawić położenie</em>
            <LMap
              class="add-point__map"
              :zoom="zoom"
              :center="center"
            >
              <LTileLayer :url="url" :attribution="attribution"></LTileLayer>
              <LMarker
                :lat-lng="center"
                :draggable="true"
                @update:lat-lng="onLatLngChange"
              >
                <LIcon
                  :icon-anchor="[0, 22.5]"
                >
                  <div
                    class="add-point__marker"
                    :style="{ '--bg-color': mapPoint.color}"
                  ></div>
                </LIcon>
              </LMarker>
            </LMap>
          </v-col>
        </v-row>

        <v-row justify="center">
          <v-col md="6">
            <v-autocomplete
              v-model="mapPoint.user.id"
              :items="users"
              :loading="isUserLoading"
              :search-input.sync="userSearchInput"
              clearable
              cache-items
              label="Dodano przez"
              hint="Wybierz użytkownika, przez którego ma być dodany punkt."
              persistent-hint
              :rules="$rules.required"
              class="mt-5"
            >
            </v-autocomplete>
          </v-col>
        </v-row>

        <v-row justify="center">
          <v-col md="6">
            <v-switch
              class="mt-5"
              v-model="mapPoint.isActive"
              :label="`Status: ${status}`"
            ></v-switch>
          </v-col>
        </v-row>

        <v-row class="mt-4" justify="center">
          <v-col class="text-right" md="6">
            <v-btn type="submit">Zapisz</v-btn>
          </v-col>
        </v-row>
      </v-form>
    </v-col>

    <LightBox
      ref="lightbox"
      :media="mapPointImages"
      :show-light-box="false"
      :show-thumbs="false"
    ></LightBox>

    <DialogConfirm ref="confirm"/>
  </v-row>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { mask } from 'vue-the-mask';
import LightBox from 'vue-image-lightbox';
import {
  LMap,
  LTileLayer,
  LMarker,
  LIcon,
} from 'vue2-leaflet';
import getAllTags from '../api/tag/getAllTags';
import deleteMapPointImage from '../api/point/deleteMapPointImage';
import DialogConfirm from '../components/DialogConfirm';
import searchUserByIdOrEmail from '../api/user/searchUserByIdOrEmail';
import updateMapPoint from '../api/point/updateMapPoint';
import createMapPoint from '../api/point/createMapPoint';

export default {
  name: 'MapPointForm',
  props: {
    title: {
      type: String,
      required: true,
    },
    action: {
      type: String,
      required: true,
    },
    mapPoint: {
      type: Object,
      required: true,
    },
  },
  components: {
    LMap,
    LTileLayer,
    LMarker,
    LIcon,
    LightBox,
    DialogConfirm,
  },
  computed: {
    status() {
      return this.mapPoint.isActive ? 'Aktywny' : 'Nieaktywny';
    },
    checkedCategories: {
      get() {
        return this.mapPoint.mapPointCategories.map((category) => {
          if (typeof category === 'object') {
            return {
              value: category.id,
              text: category.name,
            };
          } else {
            return category;
          }
        });
      },
      set(categories) {
        this.mapPoint.mapPointCategories = categories.map((category) => {
          if (typeof category === 'object') {
            return {
              id: category.value,
              name: category.text,
            };
          } else {
            return category;
          }
        });
      },
    },
  },
  watch: {
    checkedCategories(val) {
      if (val.length > 5) {
        this.$nextTick(() => this.checkedCategories.pop());
      }
    },
    mapPoint: function(mapPoint) {
      if (mapPoint.user.id) {
        this.users = [
          {
            text: `#${mapPoint.user.id} ${mapPoint.user.name} ${mapPoint.user.email}`,
            value: mapPoint.user.id,
          },
        ];
        this.mapPoint.user.id = mapPoint.user.id;
      }

      if (typeof mapPoint.lat !== 'undefined' && mapPoint.lat !== '') {
        this.center = [mapPoint.lat, mapPoint.lng];
      }
    },
    userSearchInput(value) {
      if (this.isUserLoading) return;

      if (!value) return;

      this.isUserLoading = true;
      this.onSearchUser(value);
    },
  },
  created() {
    getAllTags()
      .then((response) => {
        if (typeof response.data.data !== 'undefined') {
          this.pointCategories = response.data.data.map(({ id, name }) => ({
            value: id,
            text: name,
          }));
        }
      });
  },
  data() {
    return {
      users: [],
      isUserLoading: false,
      userSearchInput: null,
      pointCategories: [],
      mapPointImages: [],
      pointCategorySearch: null,
      zoom: 6,
      center: [52.302, 19.281],
      mask: '##-###',
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      editor: ClassicEditor,
      editorConfig: {
        alignment: {
          options: [
            'left',
            'right',
          ],
        },
        toolbar: {
          items: [
            'bold',
            'italic',
            'bulletedList',
            'numberedList',
            'heading',
          ],
        },
      },
    };
  },
  directives: {
    mask,
  },
  methods: {
    processLogoImage(event) {
      this.mapPoint.newLogo = event;
    },
    processImages(event) {
      this.mapPoint.newMapPointImages = event;
    },
    onLatLngChange({ lat, lng }) {
      this.mapPoint.lat = lat;
      this.mapPoint.lng = lng;
    },
    openImage(index) {
      this.mapPointImages = this.mapPoint.mapPointImage;
      this.$refs.lightbox.showImage(index);
    },
    onDeleteMapPointImage(imageId, index) {
      this.$refs.confirm.open('Usuń zdjęcie', 'Czy usunąć zdjęcie?')
        .then((confirm) => {
          if (confirm) {
            this.$store.dispatch('dialogloader/show', 'Trwa usuwanie zdjęcia...');

            deleteMapPointImage(imageId)
              .then(() => {
                this.$delete(this.mapPoint.mapPointImage, index);
              })
              .finally(() => {
                this.$store.dispatch('dialogloader/hide');
              });
          }
        });
    },
    onSearchUser(value) {
      searchUserByIdOrEmail(value)
        .then((response) => {
          this.users = response.data.data.map((user) => {
            return {
              text: `#${user.id} ${user.name} ${user.email}`,
              value: user.id,
            };
          });
        })
        .finally(() => this.isUserLoading = false);
    },
    handleFunctionCall(functionName) {
      this[functionName]();
    },
    onCreateMapPoint() {
      if (!this.$refs.form.validate()) return;

      this.$store.dispatch('dialogloader/show', 'Trwa dodawanie punktu...');
      createMapPoint(this.mapPoint)
        .then(() => {
          this.$router.push({ name: 'PointsPage' });
        })
        .finally(() => {
          this.$store.dispatch('dialogloader/hide');
        });
    },
    onUpdateMapPoint() {
      if (!this.$refs.form.validate()) return;

      this.$store.dispatch('dialogloader/show', 'Trwa aktualizacja punktu...');
      updateMapPoint(this.mapPoint)
        .then(() => {
          this.$router.push({ name: 'PointsPage' });
        })
        .finally(() => {
          this.$store.dispatch('dialogloader/hide');
        });
    },
  },
};
</script>

<style lang="scss">
.add-point {
  &__map {
    background-color: #363636;
    width: 100%;
    min-height: 400px;
  }

  &__marker {
    background: var(--bg-color);
    width: 30px;
    height: 30px;
    border-radius: 50% 50% 50% 0;
    position: absolute;
    transform: rotate(-45deg);
    left: 50%;
    top: 50%;
    margin: -20px 0 0 -20px;
    &:after {
      content: "";
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' height='22' viewBox='0 0 24 24' width='22'%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3Cpath fill='%23fff' d='M13.127 14.56l1.43-1.43 6.44 6.443L19.57 21zm4.293-5.73l2.86-2.86c-3.95-3.95-10.35-3.96-14.3-.02 3.93-1.3 8.31-.25 11.44 2.88zM5.95 5.98c-3.94 3.95-3.93 10.35.02 14.3l2.86-2.86C5.7 14.29 4.65 9.91 5.95 5.98zm.02-.02l-.01.01c-.38 3.01 1.17 6.88 4.3 10.02l5.73-5.73c-3.13-3.13-7.01-4.68-10.02-4.3z'/%3E%3C/svg%3E");
      width: 20px;
      height: 20px;
      margin: 3px 0 0 5px;
      display: block;
      position: absolute;
      transform: rotate(45deg);
    }
}
}
</style>
