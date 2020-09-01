<template>
  <v-card v-if="isCreating" class="mb-3">
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
        v-model="review"
        :config="editorConfig"
      ></ckeditor>
    </v-card-text>
    <v-card-actions class="justify-end">
      <v-btn @click="onCreateReview()">
        Wyślij opinię
      </v-btn>
      <v-btn @click="isCreating = false">
        Anuluj
      </v-btn>
    </v-card-actions>
  </v-card>

  <v-card v-else class="mb-3">
    <v-card-title class="text-subtitle-1">
      Nie dodałeś jeszcze swojej opinii. Kliknij poniżej, aby dodać.
    </v-card-title>
    <v-card-text>
      <v-btn @click="isCreating = true">
        Dodaj opinię
      </v-btn>
    </v-card-text>
  </v-card>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import createReview from '@/api/review/createReview';

export default {
  name: 'MapPointReviewCreate',
  data() {
    return {
      isCreating: false,
      rating: 5,
      review: '',
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
    onCreateReview() {
      this.$store.dispatch('dialogloader/show', 'Trwa dodawanie opinii...');

      createReview(this.rating, this.review, this.$route.params.id)
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
  },
};
</script>
