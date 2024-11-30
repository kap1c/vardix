<template>
  <Head :title="title" />
  <div class="form-container">
    <h2>{{ !isUpdating ? "Новая запись" : "Редактирование записи" }}</h2>
    <form>
      <Name
          :name="form.name"
          @onChanged="onChanged"
      />
      <File
        :progress="progress"
        @onChanged="onChanged"
      />
      <button type="button" @click="submit" class="submit-btn" :disabled="disabledButton">
        {{ isSubmitting ? "Загрузка..." : isUpdating ? "Сохранить" : "Создать" }}
      </button>
      <button class="cancel-btn" type="button" @click="cancel">
        {{ "Отмена" }}
      </button>
    </form>
  </div>
  <modal
      :visible="showModal"
      :title="modalTitle"
      :message="modalMessage"
      cancelText="Отмена"
      :confirmText="confirmText"
      @confirm="handleSubmit"
      @cancel="closeModal"
  />
</template>

<script>
import {Head, useForm} from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import Name from "@/Components/Name.vue";
import File from "@/Components/File.vue";

export default {
  components: {
    Modal,
    Head,
    Name,
    File
  },
  props: {
    post: {
      type: Object,
      default: () => ({ id: null, name: "", src: "" }),
    },
    isUpdating: {
      type: Boolean,
      default: false,
    },
    title: String,
  },
  setup(props) {
    const form = useForm({
      name: props.post.name,
      file: null
    });

    return {form};
  },
  data () {
    return {
      showModal: false,
      modalTitle: 'Вы точно хотите удалить запись?',
      modalMessage: 'Это действие безвозвратна',
      confirmText: '',
      cancelling: false,
      isSubmitting: false,
      progress: 0,
    }
  },
  methods: {
    addPost(){
      this.form.post("/posts", {
        onProgress: (event) => {
          this.progress = event.percentage;
        },
        onSuccess: () => {
          this.progress = 0;
          this.isSubmitting = false;
        },
        onError: (e) => {
          if (e.file !== undefined)
            alert('Файл не должен превышать 8 мб');
        }
      });
    },
    updatePost() {
      this.form.post(`/post/${this.post.id}`, {
        onProgress: (event) => {
          this.progress = event.percentage;
        },
        onSuccess: () => {
          this.progress = 0;
          this.isSubmitting = false;
        },
      });
    },
    async handleSubmit() {
      if (this.cancelling)
        window.history.back();
      else {
        this.showModal = false;
        this.isSubmitting = true;

        await (this.isUpdating ? this.updatePost() : this.addPost());
      }
    },
    closeModal () {
      this.showModal = false;
    },
    cancel () {
      this.showModal = true;
      this.cancelling = true;
      this.modalTitle = 'Вы точно хотите отменить изменения?';
      this.modalMessage = 'Указаннын данные будут потеряны';
      this.confirmText = 'Да';
    },
    submit() {
      this.showModal = true;
      this.modalTitle = !this.isUpdating ? 'Вы точно хотите сохранить запись?' : 'Вы точно хотите сохранить запись?';
      this.modalMessage = 'Вы можете отредактировать ее позже';
      this.confirmText = !this.isUpdating ? 'Сохранить' : 'Создать';
    },
    onChanged(x) {
      this.form[x.key] = x.value;
    }
  },
  computed: {
    disabledButton(){
      return this.isSubmitting || !this.form.file;
    }
  },
};
</script>

<style scoped>
  body {
    font-family: Arial, sans-serif;
  }
  .form-container {
    background: #fff;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
    margin: 2rem auto;
    text-align: center;
  }
  .submit-btn {
    width: 100%;
    padding: 0.8rem;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    color: #fff;
    font-size: 1.2rem;
    cursor: pointer;
    margin-top: 1rem;
    transition: background-color 0.3s;
  }
  .submit-btn:disabled {
    background-color: #aaa;
    cursor: not-allowed;
  }
  .submit-btn:hover:enabled {
    background-color: #0056b3;
  }
  .browse-text {
    color: #007bff;
    text-decoration: underline;
  }
  .cancel-btn {
    width: 100%;
    padding: 0.8rem;
    background-color: #6366f1;
    border: none;
    border-radius: 5px;
    color: #fff;
    font-size: 1.2rem;
    cursor: pointer;
    margin-top: 1rem;
    transition: background-color 0.3s;
  }
  .cancel-btn:disabled {
    background-color: #aaa;
    cursor: not-allowed;
  }
  .cancel-btn:hover:enabled {
    background-color: #ef4444;
  }
</style>
