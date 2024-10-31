<template>
    <AppLayout title="Products">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800t">
                Products
            </h2>
        </template>

        <div class="flex flex-col min-h-screen">
            <div class="flex flex-grow">
                <!-- Sidebar for Filters -->
            <aside class="w-1/5 p-4 bg-gray-100 bg-gray-600 rounded-lg shadow-md">
                <!-- Search Input -->
                <div class="mb-4">
                    <InputLabel for="search" class="block mb-2 text-sm font-medium text-gray-900">Search</InputLabel>
                    <TextInput 
                        v-model="search"
                        @input="() => fetchProducts(1, true)"
                        type="text"
                        id="search"
                        class="w-full p-2 border border-gray-300 rounded-lg shadow-sme"
                        placeholder="Search products..." />
                </div>
                <!-- Dropdown Filters -->
                <div class="mb-4">
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                    <select id="category" class="w-full p-2 border border-gray-300 rounded-lg">
                        <option>All</option>
                        <option>Electronics</option>
                        <option>Fashion</option>
                        <option>Home</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="priceRange" class="block mb-2 text-sm font-medium text-gray-900">Price Range</label>
                    <select id="priceRange" class="w-full p-2 border border-gray-300 rounded-lg">
                        <option>All</option>
                        <option>$0 - $50</option>
                        <option>$50 - $100</option>
                        <option>$100 - $200</option>
                    </select>
                </div>
                <!-- Checkboxes -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Available Stock</label>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" class="form-checkbox text-blue-600" />
                            <span class="ml-2 text-gray-900">In Stock</span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" class="form-checkbox text-blue-600" />
                            <span class="ml-2 text-gray-900">On Sale</span>
                        </label>
                    </div>
                </div>
            </aside>

            <!-- Main Product Section -->
            <div class="w-3/4 pl-20">
                <div class="flex flex-wrap -mx-4">
                    <div class="p-4 w-full md:w-1/2 lg:w-1/3" v-for="product in props.products.data" :key="product.id">
                        <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow bg-gray-600 h-full">
                            <a href="#">
                                <img class="rounded-t-lg" :src="product.image" alt="Product Image" />
                            </a>
                            <div class="p-5 flex flex-col justify-between h-full">
                                <div>
                                    <a href="#">
                                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{product.name}}</h5>
                                    </a>
                                    <p class="mb-3 font-normal text-gray-700">
                                        {{ truncateDescription(product.description, 100) }}
                                        <span v-if="product.description.length > 100">...</span>
                                    </p>
                                </div>
                                <div class="mt-auto h-full">
                                    <Link :href="route('products.show', product.id)"  class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                        Read more
                                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Pagination :meta="props.products.meta" @pagination-change-page="onSearchProducts" class="mt-2" />
            </div>
            </div>
            
        </div>
    </AppLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { onMounted, ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    products: {
        type: Object,
    },
});
const products = ref();

const search = ref('');
const paginationMeta = ref({});

const truncateDescription = (description, maxLength) => {
    if (description.length > maxLength) {
        return description.slice(0, maxLength);
    }
    return description;
};

const fetchProducts = async (page = 1, isSearch = false) => {
    if (isSearch) {
        page = 1; 
    }
    axios.get('/search-products', {
        params: {
            search: search.value,
            page: page,  // Include page in the request
        }
    }).then(response => {
        products.value = response.data.products;
        paginationMeta.value = response.data.meta;  // Update pagination meta
    }).catch(error => {
        console.log(error);
    });
};

</script>