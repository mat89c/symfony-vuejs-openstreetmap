<template>
    <div>
      <v-card v-for="(review, index) in reviews" :key="index" class="mb-3">
        <v-card-title class="pb-0 justify-space-between">
          <span v-if="isEditable(review)">
            Twoja opinia
            <v-btn
              small
              @click="onEdit(review)"
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
            v-if="isEditing && isEditable(review)"
            :editor="editor"
            v-model="updatedReview"
            :config="editorConfig"
          ></ckeditor>
          <em v-else v-html="review.content"></em>
        </v-card-text>
        <v-card-actions v-if="isEditing && isEditable(review)" class="justify-end">
          <v-btn @click="onUpdateReview(review, index)">
            Zaktualizuj opinię
          </v-btn>
          <v-btn @click="isEditing = false">
            Anuluj
          </v-btn>
        </v-card-actions>
      </v-card>
    </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import updateReview from '@/api/review/updateReview';

export default {
  name: 'MapPointReview',
  data() {
    return {
      isEditing: false,
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
    onEdit(review) {
      this.updatedReview = review.content;
      this.isEditing = true;
    },
    isEditable(review) {
      return this.$store.getters['user/id'] === review.user.id;
    },
    onUpdateReview(review, index) {
      this.$store.dispatch('dialogloader/show', 'Trwa aktualizowanie opinii...');

      updateReview(this.updatedReview, review.rating, review.id)
        .then((response) => {
          this.$store.dispatch('dialogpopup/show', {
            title: response.data.title,
            message: response.data.message,
          });
          this.isEditing = false;
          console.log(review);
          this.$emit('onReviewUpdated', index);
        })
        .finally(() => {
          this.$store.dispatch('dialogloader/hide');
        });
    },
  },
  props: {
    reviews: {
      type: Array,
      required: true,
    },
  },
};
</script>
