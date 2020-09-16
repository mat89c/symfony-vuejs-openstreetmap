<template>
  <v-dialog v-model="dialog" max-width="500" @keydown.esc="cancel">
    <v-card>
      <v-toolbar dense flat>
        <v-toolbar-title class="text-caption">Wyślij wiadomośc do {{ message.receiverName }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text class="pa-4">
        <v-form ref="emailForm">
          <v-text-field
            label="Temat"
            v-model="message.subject"
            :rules="$rules.required"
          ></v-text-field>
          <v-row>
            <v-col cols="12" class="mt-3">
              <ckeditor
                :editor="editor"
                v-model="message.content"
                :config="editorConfig"
              ></ckeditor>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions class="pt-0">
        <v-spacer></v-spacer>
        <v-btn @click="onSubmit">Wyślij</v-btn>
        <v-btn @click="dialog = false">Anuluj</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import sendEmailMessage from '../api/email/sendEmailMessage';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
  name: 'DialogEmailMessage',
  data() {
    return {
      message: {
        receiverEmail: '',
        receiverName: '',
        subject: '',
        content: '',
      },
      dialog: false,
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
    onSubmit() {
      if (!this.$refs.emailForm.validate()) return;

      this.$store.dispatch('dialogloader/show', 'Trwa wysyłanie wiadomości e-mail...');

      sendEmailMessage(this.message)
        .then((response) => {
          this.close();

          this.$store.dispatch('dialogpopup/show', {
            title: response.data.title,
            message: response.data.message,
          });
        })
        .finally(() => this.$store.dispatch('dialogloader/hide'));
    },
    open(receiverEmail, receiverName) {
      this.message.receiverEmail = receiverEmail;
      this.message.receiverName = receiverName;
      this.dialog = true;
    },
    close() {
      this.dialog = false;
      this.message = {
        receiverEmail: '',
        receiverName: '',
        subject: '',
        content: '',
      };
    },
  },
};
</script>
