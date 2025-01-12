<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const categories = ref([]);
const router = useRouter();
const currentPage = ref(1);
const totalPages = ref(1);
const links = ref([]);

const fetchCategories = async (page = 1) => {
  try {
    const response = await fetch(`http://localhost/api/categories?page=${page}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
      },
    });

    if (response.status === 401) {
      handleUnauthorized();
      return;
    }

    if (!response.ok) {
      throw new Error('Failed to fetch categories');
    }

    const data = await response.json();
    categories.value = data.data;
    currentPage.value = data.meta.current_page;
    totalPages.value = data.meta.last_page;
    links.value = data.meta.links;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
};

const handleUnauthorized = () => {
  // Redirect to login page if unauthorized
  router.push({ name: 'login' });
};

// Navigate to the products page and pass the selected category ID
const goToProductsPage = (categoryId) => {
  router.push({ name: 'products', params: { categoryId } });
};

const goToPage = (pageUrl) => {
  if (pageUrl) {
    const url = new URL(pageUrl);
    const page = url.searchParams.get('page');
    fetchCategories(page);
  }
};

onMounted(() => {
  fetchCategories();
});
</script>

<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Categories</h2>
    <table class="table-auto w-full border-collapse border border-gray-300">
      <thead>
      <tr>
        <th class="border border-gray-300 px-4 py-2">ID</th>
        <th class="border border-gray-300 px-4 py-2">Category Name</th>
        <th class="border border-gray-300 px-4 py-2">Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="category in categories" :key="category.id" @click="goToProductsPage(category.id)" class="cursor-pointer hover:bg-gray-100">
        <td class="border border-gray-300 px-4 py-2">{{ category.id }}</td>
        <td class="border border-gray-300 px-4 py-2">{{ category.name }}</td>
        <td class="border border-gray-300 px-4 py-2">
          <button
            @click="goToProductsPage(category.id)"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
            View Products
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <div v-if="links.length > 0" class="mt-4 flex space-x-2">
      <button
        v-for="link in links"
        :key="link.label"
        :disabled="!link.url"
        @click="goToPage(link.url)"
        v-html="link.label"
        class="px-4 py-2 border rounded bg-blue-500 text-white hover:bg-blue-600 disabled:bg-gray-300 disabled:cursor-not-allowed"
      ></button>
    </div>
  </div>
</template>
