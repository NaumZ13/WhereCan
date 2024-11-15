<template>
    <AppLayout title="Products">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Products</h2>
        </template>

        <div class="flex flex-grow min-h-screen overflow-hidden">
            <!-- Sidebar for Filters -->
            <aside
                class="w-1/5 p-4 bg-purple-300 rounded-lg shadow-md overflow-y-auto"
            >
                <div class="mb-4">
                    <InputLabel
                        for="search"
                        class="block mb-2 text-sm font-medium text-gray-900"
                    >
                        Search
                    </InputLabel>
                    <TextInput
                        v-model="filters.search"
                        @input="resetAndFetchProducts"
                        type="text"
                        id="search"
                        class="w-full p-2 border border-gray-300 rounded-lg"
                        placeholder="Search products..."
                    />
                </div>
                <!-- Other filter fields -->
                <div class="mb-4">
                    <label
                        for="category"
                        class="block mb-2 text-sm font-medium text-gray-900"
                    >
                        Category
                    </label>
                    <select
                        id="category"
                        v-model="filters.category"
                        @change="resetAndFetchProducts"
                        class="w-full p-2 border border-gray-300 rounded-lg"
                    >
                        <option value="">All</option>
                        <option value="electronics">Electronics</option>
                        <option value="fashion">Fashion</option>
                        <option value="home">Home</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label
                        for="priceRange"
                        class="block mb-2 text-sm font-medium text-gray-900"
                        >Price Range</label
                    >
                    <select
                        id="priceRange"
                        class="w-full p-2 border border-gray-300 rounded-lg"
                    >
                        <option>All</option>
                        <option>$0 - $50</option>
                        <option>$50 - $100</option>
                        <option>$100 - $200</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900"
                        >Available Stock</label
                    >
                    <div>
                        <label class="inline-flex items-center">
                            <input
                                type="checkbox"
                                class="form-checkbox text-blue-600"
                            />
                            <span class="ml-2 text-gray-900">In Stock</span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input
                                type="checkbox"
                                class="form-checkbox text-blue-600"
                            />
                            <span class="ml-2 text-gray-900">On Sale</span>
                        </label>
                    </div>
                </div>
            </aside>

            <!-- Main Product Section -->
            <main class="w-4/5 p-2 bg-indigo-200 overflow-auto mt-5">
                <div class="flex flex-wrap justify-around gap-4">
                    <div
                        v-for="(product, index) in products"
                        :key="product.id"
                        class="bg-purple-100 border rounded-lg shadow-md w-full sm:w-1/2 md:w-1/4 lg:w-1/5 p-5 transform transition-transform duration-300 ease-in-out hover:scale-105 hover:rotate-2 hover:shadow-lg"
                    >
                        <img
                            class="rounded-t-lg w-full h-48 object-cover"
                            :src="product.image"
                            alt="Product Image"
                        />
                        <div class="mt-4">
                            <h5
                                class="mb-2 text-lg font-bold tracking-tight text-gray-900"
                            >
                                {{ product.name }}
                            </h5>
                            <p class="text-gray-700 text-sm">
                                {{
                                    truncateDescription(
                                        product.description,
                                        100
                                    )
                                }}
                                <span v-if="product.description.length > 100"
                                    >...</span
                                >
                            </p>
                        </div>
                        <Link
                            :href="route('products.show', product.id)"
                            class="inline-flex items-center mt-3 px-3 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                        >
                            Read more
                        </Link>
                    </div>
                </div>
                <div ref="loadMoreTrigger" class="mt-4"></div>
            </main>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { ref, onMounted, onBeforeUnmount } from "vue";
import axios from "axios";
import debounce from "lodash.debounce";

const products = ref([]);
const filters = ref({ search: "", category: "" });
const page = ref(1);
const loading = ref(false);
const isLastPage = ref(false);

// Truncate description helper
const truncateDescription = (description, maxLength) => {
    return description.length > maxLength
        ? description.slice(0, maxLength)
        : description;
};

// Reset filters and fetch products
const resetAndFetchProducts = debounce(() => {
    page.value = 1;
    products.value = [];
    isLastPage.value = false; // Reset last page flag
    fetchFilteredProducts();
}, 300);

// Fetch products with filters and pagination
const fetchFilteredProducts = async () => {
    if (loading.value || isLastPage.value) return;
    loading.value = true;

    try {
        const response = await axios.get("/fetch-products", {
            params: {
                ...filters.value,
                page: page.value,
            },
        });
        products.value = [...products.value, ...response.data.products];
        page.value += 1;
        isLastPage.value = response.data.meta.current_page === response.data.meta.last_page;
    } catch (error) {
        console.error("Error fetching products:", error);
    } finally {
        loading.value = false;
    }
};

// Intersection Observer for infinite scroll
const loadMoreTrigger = ref(null);
let observer;

const createObserver = () => {
    observer = new IntersectionObserver(
        (entries) => {
            const [entry] = entries;
            if (entry.isIntersecting && !loading.value) {
                fetchFilteredProducts();
            }
        },
        { threshold: 1.0 }
    );

    if (loadMoreTrigger.value) {
        observer.observe(loadMoreTrigger.value);
    }
};

onMounted(() => {
    resetAndFetchProducts(); // Initial fetch
    createObserver();
});

onBeforeUnmount(() => {
    if (observer && loadMoreTrigger.value) {
        observer.unobserve(loadMoreTrigger.value);
    }
});
</script>
