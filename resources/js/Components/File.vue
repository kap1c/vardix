<template>
  <div
    class="drag-area"
    :class="{ 'drag-over': isDragging }"
    @dragover.prevent="isDragging = true"
    @dragleave="isDragging = false"
    @drop="handleDrop"
    @click="browseFile"
  >
    <p v-if="!file">Наведите сюда файл или <span style="color: #007bff; text-decoration: underline;">Обзор</span></p>
    <div v-else class="file-preview">
      <p><strong>{{ file.name }}</strong> ({{ (file.size / 1024 / 1024).toFixed(2) }} MB)</p>
      <img v-if="isImage" :src="filePreview" alt="Обзор" />
    </div>
    <input type="file" ref="fileInput" class="file-input" max="8" @change="handleFile" />
  </div>
  <div v-if="progress > 0" class="progress-bar w-full bg-gray-200 rounded h-4 mb-4 mt-4">
    <div
        class="bg-blue-500 h-4 rounded text-center text-white text-sm"
        :style="{ width: `${progress}%` }"
    >
      {{ progress }}%
    </div>
  </div>
</template>

<script>
import {ref} from "vue";

export default {
  props: {
    name: String,
    progress: Number,
  },
  data() {
    return {
      file: null,
    }
  },
  emits: ['onChanged'],
  methods: {
    onChange() {
      this.$emit('onChanged', {
        key: 'file',
        value: this.file
      });
    },
    handleFile(event) {
      if(this.validateFileSize(event.target.files[0])){
        alert(`Файл не должен быть более чем 8MB`);
        return;
      }

      this.file = event.target.files[0];
      if (this.isImage) {
        this.filePreview = URL.createObjectURL(this.file);
      }

      this.onChange();
    },
    browseFile() {
      document.querySelector(".file-input").click();
    },
    handleDrop() {
      this.isDragging = false;
      this.handleFile({ target: { files: event.dataTransfer.files } });
    },
  },
  computed: {
    isImage(){
      return this.file && this.file.type.startsWith("image/");
    },
  },
  setup() {
    const file = ref(null);
    const filePreview = ref(null);
    const isDragging = ref(false);
    const maxFileSize = 8 * 1024 * 1024;

    const validateFileSize = (file) => {
      return file && file.size > maxFileSize;
    };
    return {
      file,
      filePreview,
      isDragging,
      validateFileSize
    };
  },
};
</script>

<style scoped>
.drag-area {
  border: 2px dashed #007bff;
  border-radius: 10px;
  padding: 2rem;
  background-color: #f9f9f9;
  cursor: pointer;
  transition: background-color 0.3s;
}
.drag-area.drag-over {
  background-color: #e9f5ff;
}
.drag-area p {
  margin: 0;
  font-size: 1.1rem;
  color: #555;
}
.file-input {
  display: none;
}
.file-preview img {
  max-width: 100%;
  height: auto;
  margin-top: 1rem;
  border-radius: 5px;
}
.progress-bar {
  background-color: rgba(59, 130, 246, 1);
  transition: width 0.2s ease;
}
</style>
