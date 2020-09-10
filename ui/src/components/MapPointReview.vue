<template>
    <div>
      <div v-if="reviews.length">
        <v-card v-for="(review, index) in reviews" :key="index" class="mb-3">
          <v-card-title class="pb-0 justify-space-between">
            <span v-if="isEditable(review)">
              Twoja opinia
              <v-btn
                small
                @click="onEdit(review, index)"
              >
                Edytuj
              </v-btn>
            </span>
            <span v-else>
              {{ review.user.name }}
            </span>
            <em><small class="text-caption">Dodano: {{ review.createdAt | date }}</small></em>
          </v-card-title>
          <v-card-text>
            <v-rating
              v-model="review.rating"
              class="mb-1"
              color="amber"
              length="10"
              dense
              background-color="grey  "
              :readonly="!isEditing"
              size="20"
            ></v-rating>
            <ckeditor
              v-if="isEditing && isEditable(review) && reviewIndex === index"
              :editor="editor"
              v-model="updatedReview"
              :config="editorConfig"
            ></ckeditor>
            <em v-else v-html="review.content"></em>
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
                    @click.stop="openImage(index, review)"
                  >
                    <v-btn
                      v-if="isEditing"
                      x-small
                      absolute
                      right
                      fab
                      color="gray"
                      class="mt-1"
                      @click.stop="onDeleteReviewImage(img.id, index, review)"
                    >
                      <v-icon>mdi-delete</v-icon>
                    </v-btn>
                  </v-img>
                </v-card>
              </v-col>
            </v-row>
            <v-file-input
              v-if="isEditing && isEditable(review) && reviewIndex === index"
              class="mt-10"
              label="Dodaj zdjęcia"
              accept="image/*"
              @change="processImages"
              counter
              multiple
              show-size
              :rules="$rules.imagesSize"
            ></v-file-input>
          </v-card-text>
          <v-card-actions
            v-if="isEditing && isEditable(review) && reviewIndex === index" class="justify-end"
          >
            <v-btn @click="onUpdateReview(review, index)">
              Zaktualizuj opinię
            </v-btn>
            <v-btn @click="isEditing = false">
              Anuluj
            </v-btn>
          </v-card-actions>
        </v-card>

        <LightBox
        ref="lightbox"
        :media="reviewImages"
        :show-light-box="false"
        :show-thumbs="false"
      ></LightBox>

      <DialogConfirm ref="confirm"/>
    </div>
  </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import updateReview from '@/api/review/updateReview';
import LightBox from 'vue-image-lightbox';
import '../../node_modules/vue-image-lightbox/dist/vue-image-lightbox.min.css';
import DialogConfirm from '@/components/DialogConfirm.vue';
import deleteReviewImage from '@/api/review/deleteReviewImage';

export default {
  name: 'MapPointReview',
  components: {
    LightBox,
    DialogConfirm,
  },
  data() {
    return {
      isEditing: false,
      reviewIndex: null,
      reviewImages: [],
      newReviewImages: [],
      updatedReview: '',
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
    onEdit(review, index) {
      this.updatedReview = review.content;
      this.isEditing = true;
      this.reviewIndex = index;
    },
    isEditable(review) {
      return this.$store.getters['user/id'] === review.user.id;
    },
    openImage(index, review) {
      this.reviewImages = review.reviewImages;
      this.$refs.lightbox.showImage(index);
    },
    onUpdateReview(review, index) {
      this.$store.dispatch('dialogloader/show', 'Trwa aktualizowanie opinii...');

      updateReview(this.updatedReview, review.rating, review.id, this.newReviewImages)
        .then((response) => {
          this.$store.dispatch('dialogpopup/show', {
            title: response.data.title,
            message: response.data.message,
          });
          this.isEditing = false;
          this.$emit('onReviewUpdated', index, response.data.data);
        })
        .finally(() => {
          this.$store.dispatch('dialogloader/hide');
        });
    },
    onDeleteReviewImage(imageId, index, review) {
      this.$refs.confirm.open('Usuń zdjęcie', 'Czy usunąć zdjęcie?')
        .then((confirm) => {
          if (confirm) {
            this.reviewImages = review.reviewImages;
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
    processImages(event) {
      this.newReviewImages = event;
    },
  },
  props: {
    reviews: {
      type: Array,
      reqiured: true,
    },
  },
};
</script>
