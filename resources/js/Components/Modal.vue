<template>
  <div v-if="visible" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
    <div class="bg-white p-6 rounded shadow-md">
      <h2 class="text-lg font-semibold mb-4">{{ title }}</h2>
      <p>{{ message }}</p>
      <div class="mt-4 flex justify-end gap-4">
        <button @click="onCancel" class="bg-gray-300 px-4 py-2 rounded">{{ cancelText }}</button>
        <button @click="onConfirm" class="bg-blue-500 text-white px-4 py-2 rounded">{{ confirmText }}</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    visible: {
      type: Boolean,
      required: true,
    },
    title: {
      type: String,
      default: 'Подтверждение',
    },
    message: {
      type: String,
      default: 'Вы уверены?',
    },
    cancelText: {
      type: String,
      default: 'Отмена',
    },
    confirmText: {
      type: String,
      default: 'Да',
    },
  },
  emits: ['confirm', 'cancel'],
  methods: {
    onConfirm() {
      this.$emit('confirm');
    },
    onCancel() {
      this.$emit('cancel');
    },
  },
};
</script>

<style scoped>
.modal-overlay {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>
