<template>
    <AppLayout>
        <div class="min-h-screen">
            <!-- Filters Section -->
            <div class="bg-white p-4 rounded-t-md shadow-md border border-gray-200 flex justify-center">
                <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-7 items-center">
                    <!-- Checkbox Filter Example -->
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" v-model="filters.showUnpublished" class="form-checkbox" />
                        <span>Show unpublished</span>
                    </label>
                    
                    <!-- Search Input -->
                    <input
                        type="text"
                        v-model="filters.search"
                        placeholder="Search products..."
                        class="p-2 border border-gray-300 rounded-md"
                    />
                    <!-- Additional Filter Examples -->

                    <!-- Apply Filters Button -->
                    <button @click="fetchProducts" class="p-2 bg-blue-500 text-white rounded-md">
                        Apply Filters
                    </button>
                </div>
            </div>

            <!-- Datatable Component -->
            <div class="bg-white rounded-b-md shadow-md border border-t-0 border-gray-200">
                <Datatable :columns="columns" :data="products" :pagination="pagination" @changePage="fetchProducts">
                    <!-- Custom slot for "created_at" column to format the date -->
                    <template #created_at="{ row }">
                        {{ new Date(row.created_at).toLocaleDateString() }}
                    </template>

                    <!-- Custom slot for "actions" column to add buttons -->
                    <template #actions="{ row }">
                        <Link
                            title="Edit Product"
                            :href="route('client.products.edit', row.id)"
                            class="text-white mr-5 bg-green-700 pt-1 pb-1 pl-3 pr-3 rounded"
                        ><i class="fa-solid fa-pen-to-square"></i></Link>
                        <button @click="deleteProduct(row.id)" class="text-red-500"><i class="fa-solid fa-trash mr-5"></i></button>
                        <button title="Unpublish Product" @click="unpublishProduct(row.id)"  v-if="!appliedFilters.showUnpublished"  class="text-red-500"><i class="fa-solid fa-x"></i></button>
                        <button title="Publish Product" @click="publishProduct(row.id)" v-if="appliedFilters.showUnpublished"  class="text-red-500"><i class="fa-solid fa-check"></i></button>
                    </template>
                </Datatable>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import Datatable from "@/Datatable/Datatable.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted, reactive } from "vue";
import axios from "axios";
import { Link, router } from "@inertiajs/vue3";
import Swal from 'sweetalert2';

const columns = ref([]);
const products = ref([]);
const pagination = ref({});
const filters = reactive({
    search: "",
    showUnpublished: false,
});
const appliedFilters = reactive({
    showUnpublished: false,
});

onMounted(() => {
    fetchProducts();
});

const fetchProducts = async (page = 1) => {
    try {
        const response = await axios.get("/client/products/getProducts", {
            params: {
                page,
                filters
            }
        });
        columns.value = response.data.columns;
        products.value = response.data.data.data;
        pagination.value = {
            currentPage: response.data.data.current_page,
            lastPage: response.data.data.last_page,
        };
        appliedFilters.showUnpublished = filters.showUnpublished;
    } catch (error) {
        Swal.fire("Error fetching products", error.message, "error");
    }
};

const unpublishProduct = async (id) => {
    try {
        router.put(route("client.products.unpublish", id));
        Swal.fire("Product unpublished successfully", "", "success");
        fetchProducts();
    } catch (error) {
        Swal.fire("Error", "Unable to unpublish product", "error");
    }
};

const publishProduct = async (id) => {
    try {
        router.put(route("client.products.publish", id));
        Swal.fire("Product published successfully", "", "success");
        fetchProducts();
    } catch (error) {
        Swal.fire("Error", "Unable to publish product", "error");
    }
};

const deleteProduct = async (id) => {
    const result = await Swal.fire({
        title: "Are you sure you want to delete this product?",
        showDenyButton: true,
        confirmButtonText: "Delete",
        denyButtonText: "Cancel",
    });

    if (result.isConfirmed) {
        try {
            router.delete(route("client.products.destroy", id));
            fetchProducts();
        } catch (error) {
            Swal.fire("Error", "Unable to delete product", "error");
        }
    }
};
</script>
