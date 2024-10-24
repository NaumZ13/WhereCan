<template>
    <div class="datatable container mx-auto p-6 bg-white shadow rounded-lg mt-10 mb-10">
      <!-- Search Box -->
      <div class="mb-4">
        <input
          type="text"
          v-model="search"
          placeholder="Search products..."
          @input="fetchData"
          class="w-full p-2 border border-gray-300 rounded-md"
        />
      </div>
  
      <!-- Datatable -->
      <div class="overflow-x-auto">
        <table class="table-auto w-full text-left bg-gray-100 rounded-lg">
          <thead class="bg-gray-200">
            <tr>
              <th @click="sortTable('name')" class="px-4 py-2 cursor-pointer">Name</th>
              <th @click="sortTable('price')" class="px-4 py-2 cursor-pointer">Price</th>
              <th @click="sortTable('created_at')" class="px-4 py-2 cursor-pointer">Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products.data" :key="product.id" class="border-b hover:bg-gray-50">
              <td class="px-4 py-2">{{ product.name }}</td>
              <td class="px-4 py-2">{{ product.price }}</td>
              <td class="px-4 py-2">{{ new Date(product.created_at).toLocaleDateString() }}</td>
              <td>
                <Link :href="route('client.products.edit', product.id)" class="px-4 py-2 bg-blue-500 text-white rounded" >Edit</Link>
                <button @click="onDelete(product.id)" class="px-4 py-2 bg-red-500 text-white rounded ml-2">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <!-- Pagination -->
      <div class="pagination flex items-center justify-between mt-6">
        <button
          :disabled="!products.prev_page_url"
          @click="fetchData(products.prev_page_url)"
          class="px-4 py-2 bg-blue-500 text-white rounded disabled:bg-gray-300"
        >
          Previous
        </button>
        <button
          :disabled="!products.next_page_url"
          @click="fetchData(products.next_page_url)"
          class="px-4 py-2 bg-blue-500 text-white rounded disabled:bg-gray-300"
        >
          Next
        </button>
      </div>
  
      <!-- Items per page -->
      <div class="mt-4 flex items-center">
        <label for="perPage" class="mr-2">Items per page:</label>
        <select
          v-model="perPage"
          @change="fetchData"
          class="p-2 border border-gray-300 rounded-md"
        >
          <option v-for="size in [10, 20, 50, 100]" :key="size" :value="size">
            {{ size }}
          </option>
        </select>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
import { Link } from '@inertiajs/vue3';
  
  // State variables
  const products = ref({});
  const search = ref('');
  const perPage = ref(10);
  const sortBy = ref('');
  const sortDirection = ref('asc');
  
  // Fetch data from API
  const fetchData = async (url = '/client/products/getProducts') => {
    try {
      const response = await axios.get(url, {
        params: {
          search: search.value,
          per_page: perPage.value,
          sort: sortBy.value,
          sort_direction: sortDirection.value,
        },
      });
      products.value = response.data;
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };
  
  // Sorting method
  const sortTable = (column) => {
    if (sortBy.value === column) {
      sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
      sortBy.value = column;
      sortDirection.value = 'asc';
    }
    fetchData();
  };
  
  // Fetch data when the component is mounted
  onMounted(() => {
    fetchData();
  });

  const onDelete = async (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
      try {
        await axios.delete(`/client/products/${id}`);
        fetchData();
      } catch (error) {
        console.error('Error deleting product:', error);
      }
    }
  };

  </script>
  
  <style scoped>
  .table-auto th, .table-auto td {
    padding: 12px;
  }
  </style>
  