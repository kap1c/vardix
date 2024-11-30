<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, index) in links" :key="`link-${index}`">
        <div
            v-if="link.active === true"
            :key="`null-${index}`"
            class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
            v-html="link.label"
        />
        <Link
            v-else
            :key="`link-${index}`"
            class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
            :class="{ 'bg-white': link.active }"
            :href="buildLinkWithQuery(link.url)"
            v-html="link.label"
        />
      </template>
    </div>
  </div>
</template>


<script>
import { Link } from "@inertiajs/vue3";
import { ref } from 'vue';

export default {
  props: {
    links: Array,
    search: {
      type: String,
      default: '',
    },
  },
  components: {
    Link,
  },
  setup(props) {
    const searchQuery = ref(props.search); // Access props correctly

    const buildLinkWithQuery = (url) => {
      if (!url) return '#';
      const queryParam = searchQuery.value ? `search=${encodeURIComponent(searchQuery.value)}` : '';
      return url.includes('?') ? `${url}&${queryParam}` : `${url}?${queryParam}`;
    };

    return {
      buildLinkWithQuery,
    };
  },
};


</script>