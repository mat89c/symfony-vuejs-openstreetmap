<template>
  <v-card class="mb-3">
    <v-card-text>
      Ocena:
      <v-rating
        v-model="rating"
        class="mb-1"
        color="amber"
        length="10"
        dense
        background-color="grey"
        size="20"
      ></v-rating>
      <div class="mt-10 mb-2">Napisz opinie:</div>
      <ckeditor
        :editor="editor"
        v-model="content"
        :config="editorConfig"
      ></ckeditor>
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
    </v-card-text>
    <v-card-actions class="justify-end">
      <v-btn @click="onClick()">
        Zapisz
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import createReview from '../api/review/createReview';

export default {
  name: 'ReviewForm',
  data() {
    return {
      isCreating: false,
      rating: 5,
      content: '',
      reviewImages: [],
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
    onClick() {
      this.$store.dispatch('dialogloader/show', 'Trwa dodawanie opinii...');

      createReview(this.rating, this.review, this.reviewImages, this.$route.params.id)
        .then((response) => {
          this.$store.dispatch('dialogpopup/show', {
            title: response.data.title,
            message: response.data.message,
          });
          this.isCreating = false;
          this.rating = 5;
          this.review = '';
        })
        .finally(() => {
          this.$store.dispatch('dialogloader/hide');
        });
    },
    processImages(event) {
      this.reviewImages = event;
    },
  },
};
</script>
