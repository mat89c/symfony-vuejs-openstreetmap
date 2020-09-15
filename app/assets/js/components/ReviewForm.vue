<template>
  <v-row>
    <v-col cols="12">
      <h1>{{ title }}</h1>
    </v-col>
    <v-col cols="12">
      <v-form  @submit.prevent="handleFunctionCall(action)" ref="form">
        <v-card class="mb-3">
          <v-card-text>
            Ocena:
            <v-rating
              v-model="review.rating"
              class="mb-1"
              color="amber"
              length="10"
              min="1"
              dense
              background-color="grey"
              size="20"
            ></v-rating>
            <div class="mt-10 mb-2">Napisz opinie:</div>
            <ckeditor
              :editor="editor"
              v-model="review.content"
              :config="editorConfig"
            ></ckeditor>
            <v-row>
              <v-col
                v-for="(img, index) in review.reviewImages"
                class="d-flex child-flex"
                :key="index"
                cols="12"
                md="2">
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
                      @click.stop="onDeleteReviewImage(img.id, index)"
                    >
                      <v-icon>mdi-delete</v-icon>
                    </v-btn>
                  </v-img>
                </v-card>
              </v-col>
            </v-row>

            <v-file-input
              class="mt-10"
              label="Dodaj zdjęcia"
              accept="image/*"
              @change="processImages"
              counter
              multiple
              show-size
              :rules="$rules.imagesSize"
            ></v-file-input>

            <v-autocomplete
              v-model="user"
              :items="users"
              :loading="isUserLoading"
              :search-input.sync="userSearchInput"
              clearable
              cache-items
              label="Dodano przez"
              hint="Wybierz użytkownika, przez którego ma być dodana opinia."
              persistent-hint
              :rules="$rules.required"
              class="mt-5"
            >
            </v-autocomplete>

            <v-autocomplete
              v-model="mapPoint"
              :items="mapPoints"
              :loading="isMapPointLoading"
              :search-input.sync="mapPointSearchInput"
              clearable
              cache-items
              label="Wybrany punkt"
              hint="Wybierz punkt do którego ma być przypisana opinia."
              persistent-hint
              :rules="$rules.required"
              class="mt-5"
            >
            </v-autocomplete>

            <v-switch
              class="mt-5"
              v-model="review.isActive"
              :label="`Status: ${status}`"
            ></v-switch>
          </v-card-text>
          <v-card-actions class="justify-end">
            <v-btn type="submit">
              Zapisz
            </v-btn>
            <v-btn :to="{ name: 'ReviewsPage' }">
              Anuluj
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-col>

    <LightBox
        ref="lightbox"
        :media="reviewImages"
        :show-light-box="false"
        :show-thumbs="false"
      ></LightBox>

      <DialogConfirm ref="confirm"/>
  </v-row>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import createReview from '../api/review/createReview';
import updateReview from '../api/review/updateReview';
import LightBox from 'vue-image-lightbox';
import deleteReviewImage from '../api/review/deleteReviewImage'
import DialogConfirm from '../components/DialogConfirm';
import searchUserByIdOrEmail from '../api/user/searchUserByIdOrEmail';
import searchMapPointByIdOrEmail from '../api/point/searchMapPointByIdOrName';

export default {
  name: 'ReviewForm',
  props: {
    title: {
      type: String,
      required: true,
    },
    action: {
      type: String,
      required: true,
    },
    review: {
      type: Object,
      required: true,
    },
  },
  components: {
    LightBox,
    DialogConfirm,
  },
  computed: {
    status() {
      return this.review.isActive ? 'Aktywny' : 'Nieaktywny';
    },
  },
  watch: {
    review: function(review) {
      if (review.user) {
        this.users = [
          {
            text: `#${review.user.id} ${review.user.name} ${review.user.email}`,
            value: review.user.id,
          },
        ];
        this.user = review.user.id;
      }

      if (review.mapPoint) {
        this.mapPoints = [
          {
            text: `#${review.mapPoint.id} ${review.mapPoint.title}`,
            value: review.mapPoint.id,
          },
        ];
        this.mapPoint = review.mapPoint.id;
      }
    },
    userSearchInput(value) {
      if (this.isUserLoading) return;

      if (!value) return;

      this.isUserLoading = true;
      this.onSearchUser(value);
    },
    mapPointSearchInput(value) {
      if (this.isMapPointLoading) return;

      this.isMapPointLoading = true;
      this.onSearchMapPoint(value);
    }
  },
  data() {
    return {
      reviewImages: [],
      newReviewImages: [],
      isUserLoading: false,
      isMapPointLoading: false,
      userSearchInput: null,
      mapPointSearchInput: null,
      users: [],
      mapPoints: [],
      user: null,
      mapPoint: null,
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
    handleFunctionCall(functionName) {
      this[functionName]();
    },
    onCreateReview() {
      if (!this.$refs.form.validate()) return;

      this.$store.dispatch('dialogloader/show', 'Trwa dodawanie opinii...');
      createReview(this.review, this.newReviewImages, this.mapPoint, this.user)
        .then((response) => {
          this.$router.push({ name: 'ReviewsPage' });
          this.$store.dispatch('dialogpopup/show', {
            title: response.data.title,
            message: response.data.message,
          });
        })
        .finally(() => {
          this.$store.dispatch('dialogloader/hide');
        });
    },
    onUpdateReview() {
      this.$store.dispatch('dialogloader/show', 'Trwa aktualizowanie opinii...');

      updateReview(this.review, this.newReviewImages, this.mapPoint, this.user)
        .then((response) => {
          this.$router.push({ name: 'ReviewsPage' });
          this.$store.dispatch('dialogpopup/show', {
            title: response.data.title,
            message: response.data.message,
          });
        })
        .finally(() => {
          this.$store.dispatch('dialogloader/hide');
        });
    },
    processImages(event) {
      this.newReviewImages = event;
    },
    openImage(index) {
      this.reviewImages = this.review.reviewImages;
      this.$refs.lightbox.showImage(index);
    },
    onDeleteReviewImage(imageId, index) {
      this.$refs.confirm.open('Usuń zdjęcie', 'Czy usunąć zdjęcie?')
        .then((confirm) => {
          if (confirm) {
            this.reviewImages = this.review.reviewImages;
            this.$store.dispatch('dialogloader/show', 'Trwa usuwanie zdjęcia...');

            deleteReviewImage(imageId)
              .then((response) => {
                this.$delete(this.reviewImages, index);
                this.$store.dispatch('dialogpopup/show', {
                  title: response.data.title,
                  message: response.data.message,
                });
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
    onSearchMapPoint(value) {
      searchMapPointByIdOrEmail(value)
        .then((response) => {
          this.mapPoints = response.data.data.map((mapPoint) => {
            return {
              text: `#${mapPoint.id} ${mapPoint.title}`,
              value: mapPoint.id,
            };
          });
        })
        .finally(() => this.isMapPointLoading = false);
    },
  },
};
</script>
