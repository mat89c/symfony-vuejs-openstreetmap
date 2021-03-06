<template>
  <v-container class="add-point">
    <v-form @submit.prevent="onSubmit" ref="form">
      <v-row>
        <v-col>
          <h1 class="add-point__title">Dodaj nowy punkt</h1>
        </v-col>
      </v-row>
      <v-row justify="center">
        <v-col md="6">
            <v-text-field
              label="Nazwa"
              v-model="pointName"
              :rules="$rules.required"
              autofocus
            ></v-text-field>
            <v-text-field
              label="Ulica"
              v-model="pointStreet"
              :rules="$rules.required"
            >
            </v-text-field>
            <v-row>
              <v-col md="4">
                <v-text-field
                  label="Kod pocztowy"
                  return-masked-value
                  v-mask="mask"
                  v-model="pointPostCode"
                  :rules="$rules.required && $rules.postcode"
                ></v-text-field>
              </v-col>
              <v-col md="8">
                <v-text-field
                  label="Miasto"
                  v-model="pointCity"
                  :rules="$rules.required"
                ></v-text-field>
              </v-col>
            </v-row>
            <h4 class="ck-editor__title">Opis</h4>
            <ckeditor
              :editor="editor"
              v-model="pointDescription"
              :config="editorConfig"
            ></ckeditor>
            <v-combobox
              v-model="pointCategory"
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
            <v-file-input
              class="mt-10"
              label="Zdjęcie główne"
              accept="image/*"
              @change="processLogoImage"
              counter
              show-size
              :rules="$rules.required && $rules.logoSize"
            ></v-file-input>
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
            v-model="pointMarkerColor"
            hide-inputs
          ></v-color-picker>
        </v-col>
      </v-row>
      <v-row justify="center">
        <v-col md="6">
          <span class="add-point__info">*Przeciągnij marker, aby ustawić położenie</span>
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
                  :style="{ '--bg-color': pointMarkerColor}"
                ></div>
              </LIcon>
            </LMarker>
          </LMap>
        </v-col>
      </v-row>
      <v-row class="mt-4" justify="center">
        <v-col class="text-right" md="6">
          <v-btn type="submit">Zapisz</v-btn>
        </v-col>
      </v-row>
      <v-icon class="add-point__bg">mdi-map-marker</v-icon>
    </v-form>
  </v-container>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { mask } from 'vue-the-mask';
import createMapPoint from '@/api/point/createMapPoint';
import {
  LMap,
  LTileLayer,
  LMarker,
  LIcon,
} from 'vue2-leaflet';
import getAllCategories from '@/api/category/getAllCategories';

export default {
  name: 'MapPointCreatePage',
  components: {
    LMap,
    LTileLayer,
    LMarker,
    LIcon,
  },
  directives: {
    mask,
  },
  created() {
    getAllCategories()
      .then((response) => {
        if (typeof response.data.data !== 'undefined') {
          this.pointCategories = response.data.data.map(({ id, name }) => ({
            value: id,
            text: name,
          }));
        }
      });
  },
  watch: {
    pointCategory(val) {
      if (val.length > 5) {
        this.$nextTick(() => this.pointCategory.pop());
      }
    },
  },
  data() {
    return {
      pointName: '',
      pointStreet: '',
      pointPostCode: '',
      pointCity: '',
      pointDescription: '',
      pointCategorySearch: null,
      pointCategories: [],
      pointCategory: [],
      pointLatLng: {
        lat: 52.302,
        lng: 19.281,
      },
      pointMarkerColor: '#ffaa00',
      pointLogoImage: '',
      pointImages: [],
      mask: '##-###',
      zoom: 6,
      center: [52.302, 19.281],
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
  methods: {
    onLatLngChange(latlng) {
      this.pointLatLng = latlng;
    },
    onSubmit() {
      if (!this.$refs.form.validate()) {
        window.scrollTo(0, 0);
        return;
      }

      this.$store.dispatch('dialogloader/show', 'Zapisywanie punktu do bazy danych...');

      const point = {
        title: this.pointName,
        street: this.pointStreet,
        postcode: this.pointPostCode,
        city: this.pointCity,
        description: this.pointDescription,
        color: this.pointMarkerColor,
        lat: this.pointLatLng.lat,
        lng: this.pointLatLng.lng,
        logo: this.pointLogoImage,
        images: this.pointImages,
        categories: this.pointCategory,
      };

      createMapPoint(point)
        .then((response) => {
          this.$router.push('/')
            .then(() => {
              this.$store.dispatch('dialogpopup/show', {
                title: response.data.title,
                message: response.data.message,
              });
            });
        })
        .finally(() => {
          this.$store.dispatch('dialogloader/hide');
        });
    },
    processLogoImage(event) {
      this.pointLogoImage = event;
    },
    processImages(event) {
      this.pointImages = event;
    },
  },
};
</script>

<style lang="scss">
  .add-point {
    &__title {
      position: relative;
      z-index: 1;
    }

    &__form {
      width: 100%;
      max-width: 500px;
      position: relative;
      z-index: 1;
    }

    &__bg {
      position: fixed!important;
      top: -10vh;
      left: -35vh;
      font-size: 130vh!important;
      color: #4242421e!important;
      z-index: 0;
    }

    &__map {
      background-color: #363636;
      width: 100%;
      min-height: 400px;
    }

    &__info {
      color: rgba(255, 255, 255, 0.7);
      font-size: 14px;
      font-style: italic;
      position: relative;
      z-index: 1;
    }
  }

  .row {
    position: relative;
    z-index:1;
  }
</style>
