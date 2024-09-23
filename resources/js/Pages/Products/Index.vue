<template>
    <AppLayout title="Products">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Products
            </h2>
        </template>

        <div class="flex">
        <!-- Sidebar for Filters -->
        <aside class="w-1/5 p-4 bg-gray-100 dark:bg-gray-900 rounded-lg shadow-md">
            <!-- Search Input -->
            <div class="mb-4">
                <label for="search" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Search</label>
                <input type="text" id="search" class="w-full p-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Search products..." />
            </div>
            <!-- Dropdown Filters -->
            <div class="mb-4">
                <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                <select id="category" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option>All</option>
                    <option>Electronics</option>
                    <option>Fashion</option>
                    <option>Home</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="priceRange" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price Range</label>
                <select id="priceRange" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option>All</option>
                    <option>$0 - $50</option>
                    <option>$50 - $100</option>
                    <option>$100 - $200</option>
                </select>
            </div>
            <!-- Checkboxes -->
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Available Stock</label>
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="form-checkbox text-blue-600 dark:text-blue-400" />
                        <span class="ml-2 text-gray-900 dark:text-white">In Stock</span>
                    </label>
                </div>
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="form-checkbox text-blue-600 dark:text-blue-400" />
                        <span class="ml-2 text-gray-900 dark:text-white">On Sale</span>
                    </label>
                </div>
            </div>
        </aside>

        <!-- Main Product Section -->
        <div class="w-3/4 pl-20">
            <div class="flex flex-wrap -mx-4">
                <div class="p-4 w-full md:w-1/2 lg:w-1/3" v-for="product in products" :key="product.id">
                    <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <a href="#">
                            <img class="rounded-t-lg" :src="product.image" alt="Product Image" />
                        </a>
                        <div class="p-5">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{product.name}}</h5>
                            </a>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{product.description}}</p>
                            <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Read more
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </AppLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    products: {
        type: Array,
    },
    });
</script>