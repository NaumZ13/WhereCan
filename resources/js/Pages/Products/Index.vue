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
                        >Search</InputLabel
                    >
                    <TextInput
                        v-model="search"
                        @input="() => fetchProducts(1, true)"
                        type="text"
                        id="search"
                        class="w-full p-2 border border-gray-300 rounded-lg"
                        placeholder="Search products..."
                    />
                </div>

                <div class="mb-4">
                    <label
                        for="category"
                        class="block mb-2 text-sm font-medium text-gray-900"
                        >Category</label
                    >
                    <select
                        id="category"
                        class="w-full p-2 border border-gray-300 rounded-lg"
                    >
                        <option>All</option>
                        <option>Electronics</option>
                        <option>Fashion</option>
                        <option>Home</option>
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
            <main class="w-4/5 p-2 bg-indigo-200 overflow-auto">
                <div class="flex flex-wrap justify-around gap-4">
                    <div
                        v-for="(product, index) in products"
                        :key="product.id"
                        class="bg-purple-100 border rounded-lg shadow-md w-full sm:w-1/2 md:w-1/4 lg:w-1/5 p-5"
                    >
                        <a href="#">
                            <img
                                class="rounded-t-lg w-full h-48 object-cover"
                                :src="product.image"
                                alt="Product Image"
                            />
                        </a>
                        <div class="mt-4">
                            <a href="#">
                                <h5
                                    class="mb-2 text-lg font-bold tracking-tight text-gray-900"
                                >
                                    {{ product.name }}
                                </h5>
                            </a>
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
                            <svg
                                class="rtl:rotate-180 w-3.5 h-3.5 ms-2"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 14 10"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M1 5h12m0 0L9 1m4 4L9 9"
                                />
                            </svg>
                        </Link>
                    </div>
                </div>
                <Pagination
                    :meta="props.products.meta"
                    @pagination-change-page="onSearchProducts"
                    class="mt-4"
                />
            </main>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { ref, watch  } from "vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    products: {
        type: Object,
    },
});
const search = ref("");

const truncateDescription = (description, maxLength) => {
    return description.length > maxLength
        ? description.slice(0, maxLength)
        : description;
};

const products = ref(props.products.data);
const meta = ref(props.products.meta);

const fetchProducts = async (page = 1) => {
    try {
        const response = await axios.get("/search-products", {
            params: {
                search: search.value,
                page: page,
            },
        });
        console.log(response.data.products)
        products.value = response.data.products;
        meta.value = response.data.meta;
    } catch (error) {
        console.error(error);
    }
};

watch(search, () => fetchProducts(1), { debounce: 300 });
</script>
