<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { LineChart, BarChart } from 'vue-chart-3';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, BarElement } from 'chart.js';
import { computed } from 'vue';

// Register Chart.js components globally
ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, BarElement);

// Dummy data for products statistics
const totalProducts = 25;
const publishedProducts = 18;
const unpublishedProducts = 7;
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
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
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
                    <p class="mt-2 text-4xl">{{ publishedProducts }}</p>
                </div>
                <div class="bg-white shadow-md rounded-lg p-4 flex flex-col justify-between">
                    <h3 class="font-semibold text-lg">Unpublished Products</h3>
                    <p class="mt-2 text-4xl">{{ unpublishedProducts }}</p>
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
                    <LineChart :chart-data="salesData" :options="{ responsive: true, maintainAspectRatio: false }" />
                </div>

                <!-- Bar chart for product views -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="font-semibold text-lg mb-4">Top Viewed Products</h3>
                    <BarChart :chart-data="viewsData" :options="{ responsive: true, maintainAspectRatio: false }" />
                </div>
            </div>

            <!-- Actions Section -->
            <div class="flex justify-around w-full mt-10">
                <a href="/products/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create New Product
                </a>
                <a href="/products" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Manage Products
                </a>
            </div>
        </div>
    </AppLayout>
</template>