<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();  // Access route object
const selectedProductId = ref(route.params.productId);
const variants = ref([]);
const product = ref();
const currentPage = ref(1);
const totalPages = ref(1);
const links = ref([]);

// Fetch products based on the selected category ID
const fetchProducts = async () => {
  try {
    console.log(`http://localhost/api/products/${selectedProductId.value}`)
    const response = await fetch(`http://localhost/api/products/${selectedProductId.value}`, {
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
      throw new Error('Failed to fetch products');
    }

    const data = await response.json();
    product.value = data.data;
    variants.value = data.data.variants || [];
    currentPage.value = data.meta.current_page;
    totalPages.value = data.meta.last_page;
    links.value = data.meta.links;
  } catch (error) {
    console.error('Error fetching products:', error);
  }
};

const handleUnauthorized = () => {
  // Redirect to login page if unauthorized
  router.push({name: 'login'});
};

const goToPage = (pageUrl) => {
  if (pageUrl) {
    const url = new URL(pageUrl);
    const page = url.searchParams.get('page');
    fetchCategories(page);
  }
};

const goToProductVariantPage = (productId) => {
  router.push({ name: 'product-variant', params: { productId } });
};

onMounted(() => {
  selectedProductId.value = route.params.productId;  // Get category ID from route params
  fetchProducts();
});
</script>

<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Products</h2>
    <table class="table-auto w-full border-collapse border border-gray-300">
      <thead>
      <tr>
        <th class="border border-gray-300 px-4 py-2">ID</th>
        <th class="border border-gray-300 px-4 py-2">Product Name</th>
        <th class="border border-gray-300 px-4 py-2">SKU</th>
        <th class="border border-gray-300 px-4 py-2">Price</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="variant in variants" :key="variant.id">
        <td class="border border-gray-300 px-4 py-2">{{ variant.id }}</td>
        <td class="border border-gray-300 px-4 py-2">{{ product.name }}</td>
        <td class="border border-gray-300 px-4 py-2">{{ variant.sku }}</td>
        <td class="border border-gray-300 px-4 py-2">${{ variant.price }}</td>
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
