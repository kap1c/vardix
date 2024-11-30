<template>
  <Head title="Записи" />
  <div class="container">
    <h1>Записи</h1>
    <div class="head">
      <Link :href="'/posts/create'" class="btn-primary">Новая запись</Link>
      <label for=""> <b>{{ posts.data.length }}</b> записей из <b>{{ posts.total }}</b></label>
    </div>

    <form action="/posts" class="mt-4" method="get">
      <input type="text" name="search" placeholder="Поиск" v-model="searchQuery">
    </form>

    <table class="table-bordered mt-5">
      <thead>
      <tr>
        <th v-for="header in headers" :key="header">
          {{ header }}
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="post in posts.data" :key="post.id">
        <td>{{ post.name }}</td>
        <td>{{ post.src }}</td>
        <td>{{ post.size }} MB</td>
        <td>{{ post.file_ext }}</td>
        <td>
          <img v-if="isPhoto(post.file_ext)" :src="post.src" :alt="post.name">
        </td>
        <td class="actions">
          <Link :href="`posts/${post.id}/edit`">Редактировать</Link>
          <a download="" :href="`/${post.src}`" class="rounded-lg bg-gray-200 px-4 py-1">
            Скачать
          </a>
          <button class="rounded-lg bg-red-300 px-4 py-1" @click="confirmDelete(post.id)">Удалить</button>
        </td>
      </tr>
      </tbody>
    </table>
    <pagination class="mt-5" :links="posts.links" :search="searchQuery" />
  </div>
  <modal
      :visible="showModal"
      title="Вы точно хотите удалить запись?"
      message="Это действие безвозвратна"
      cancelText="Отмена"
      confirmText="Удалить"
      @confirm="deletePost"
      @cancel="closeModal"
  />
</template>

<script>
import { Link, useForm, Head } from "@inertiajs/vue3";
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import {ref} from 'vue';

export default {
  props: {
    posts: {
      type: Object,
      default: () => [],
    },
    search: {
      type: String,
      default: ''
    }
  },
  components: {
    Link,
    Pagination,
    Modal,
    Head,
  },
  data() {
    return {
      headers: ["Название", "Ссылка", "Размер", "Расширение", "Фото", "Действия"],
      formats: ['jpg', 'png', 'jpeg', 'bmp'],
      showModal: false,
    }
  },
  methods: {
    isPhoto(extension) {
      return this.formats.includes(extension);
    },
    confirmDelete(id) {
      this.postToDelete = id;
      this.showModal = true;
    },
    deletePost () {
      if (this.postToDelete) {
        this.deleteForm.delete(`/posts/${this.postToDelete}`);
        this.postToDelete = null;
      }
      this.closeModal();
    },
    closeModal () {
      this.showModal = false;
      this.postToDelete = null;
    },
  },
  setup(props) {
    const deleteForm = useForm({});
    const searchQuery = ref(props.search);
    const postToDelete = ref(null);

    return {
      searchQuery,
      postToDelete,
      deleteForm,
    }
  }
}
</script>
<style scoped>
  table {
    width: 100%;
  }

  a {
    border: 1px solid;
    border-radius: 5px;
    padding: 4px;
    text-decoration: none;
  }
  table tr{
    text-align: center;
  }
  .actions {
    flex-direction: column;
    display: inline-flex;
    border: none;
    align-self: center;
    alignment-baseline: center;
  }
  .head {
    label {
      float: right;
    }
  }
  form {
    input {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
  }
  img{
    width: 100px;
    height: 100px;
  }
</style>