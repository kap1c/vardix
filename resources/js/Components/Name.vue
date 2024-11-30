<template>
  <div class="input-group">
    <label for="name">Название</label>
    <input
        type="text"
        id="name"
        v-model="pname"
        placeholder="Введите название"
        @change="onChange"
    />
  </div>
</template>

<script>
export default {
  props: {
    name: String,
  },
  data() {
    return {
      pname: null,
    }
  },
  emits: ['onChanged'],
  methods: {
    onChange() {
      this.$emit('onChanged', {
        key: 'name',
        value: this.pname
      });
    },
  },
  created() {
    this.pname = this.name;
  }
};
</script>

<style scoped>
  .input-group {
    margin-bottom: 1rem;
  }
  .input-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 0.5rem;
  }
  .input-group input {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 10px !important;
    font-size: 1rem;
  }
</style>
