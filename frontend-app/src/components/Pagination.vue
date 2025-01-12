<template>
  <div class="pagination">
    <button :disabled="currentPage === 1" @click="changePage(currentPage - 1)">
      Previous
    </button>

    <button v-for="page in totalPages" :key="page" :class="{ active: page === currentPage }" @click="changePage(page)">
      {{ page }}
    </button>

    <button :disabled="currentPage === totalPages" @click="changePage(currentPage + 1)">
      Next
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  totalItems: {
    type: Number,
    required: true,
  },
  itemsPerPage: {
    type: Number,
    required: true,
  },
});

const emit = defineEmits(['page-changed']);

const totalPages = computed(() => Math.ceil(props.totalItems / props.itemsPerPage));

const changePage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    emit('page-changed', page);
  }
};
</script>

<style>
.pagination {
  display: flex;
  gap: 0.5rem;
  margin-top: 1rem;
}
button {
  padding: 0.5rem 1rem;
  border: 1px solid #ccc;
  background-color: #f9f9f9;
  cursor: pointer;
}
button.active {
  background-color: #007bff;
  color: white;
}
button:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}
</style>
