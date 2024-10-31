<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    totalProducts: Number,
    totalUnpublishedProducts: Number,
    totalPublishedProducts: Number,
});

const averageRating = 4.3;

// Dummy data for sales trends (past month)
const salesData = {
    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
    datasets: [
        {
            label: 'Products Sold',
            data: [12, 19, 8, 15],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }
    ]
};

// Dummy data for product views
const viewsData = {
    labels: ['Product A', 'Product B', 'Product C', 'Product D'],
    datasets: [
        {
            label: 'Views',
            data: [150, 200, 120, 300],
            backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(153, 102, 255, 0.2)'],
            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 206, 86, 1)', 'rgba(153, 102, 255, 1)'],
            borderWidth: 1
        }
    ]
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Client Dashboard
            </h2>
        </template>

        <div class="flex flex-col min-h-screen items-center p-10">
            <div class="mb-6">
                <h1 class="font-bold text-5xl">Overview</h1>
            </div>

            <!-- Statistics Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full px-4">
                <div class="bg-white shadow-md rounded-lg p-4 flex flex-col justify-between">
                    <h3 class="font-semibold text-lg">Total Products</h3>
                    <p class="mt-2 text-4xl">{{ totalProducts }}</p>
                </div>
                <div class="bg-white shadow-md rounded-lg p-4 flex flex-col justify-between">
                    <h3 class="font-semibold text-lg">Published Products</h3>
                    <p class="mt-2 text-4xl">{{ totalPublishedProducts }}</p>
                </div>
                <div class="bg-white shadow-md rounded-lg p-4 flex flex-col justify-between">
                    <h3 class="font-semibold text-lg">Unpublished Products</h3>
                    <p class="mt-2 text-4xl">{{ totalUnpublishedProducts }}</p>
                </div>
                <div class="bg-white shadow-md rounded-lg p-4 flex flex-col justify-between">
                    <h3 class="font-semibold text-lg">Average Rating</h3>
                    <p class="mt-2 text-4xl">{{ averageRating }}</p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full mt-10 px-4">
                <!-- Line chart for sales -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="font-semibold text-lg mb-4">Products Sold (Last Month)</h3>
                </div>

                <!-- Bar chart for product views -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="font-semibold text-lg mb-4">Top Viewed Products</h3>
                </div>
            </div>

            <!-- Actions Section -->
            <div class="flex justify-around w-full mt-10">
                <Link :href="route('client.products.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create New Product
                </Link>
                <Link :href="route('client.products.index')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Manage Products
                </Link>
            </div>
        </div>
    </AppLayout>
</template>