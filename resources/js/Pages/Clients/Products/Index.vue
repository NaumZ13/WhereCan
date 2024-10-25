<template>
    <AppLayout>
        <div class="min-h-screen">
            <Datatable :columns="columns" :data="products">
                <!-- Custom slot for "created_at" column to format the date -->
                <template #created_at="{ row }">
                    {{ new Date(row.created_at).toLocaleDateString() }}
                </template>

                <!-- Custom slot for "actions" column to add buttons -->
                <template #actions="{ row }">
                    <Link
                        :href="route('client.products.edit', row.id)"
                        class="text-white mr-5 bg-green-700 pt-1 pb-1 pl-3 pr-3 rounded"
                        >Edit</Link
                    >
                    <button @click="deleteProduct(row.id)" class="text-red-500">
                        Delete
                    </button>
                </template>
            </Datatable>
        </div>
    </AppLayout>
</template>

<script setup>
import Datatable from "@/Datatable/Datatable.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted } from "vue";
import axios from "axios";
import { Link, router } from "@inertiajs/vue3";
import Swal from 'sweetalert2';

const columns = ref([]);
const products = ref([]);

onMounted(() => {
    fetchProducts();
});

const fetchProducts = async () => {
    try {
        const response = await axios.get("/client/products/getProducts");
        columns.value = response.data.columns;
        products.value = response.data.data;
    } catch (error) {
        console.error("Error fetching products:", error);
    }
};

const editProduct = (id) => {
    // Logic to edit a product
    console.log("Edit product with id:", id);
};

const deleteProduct = (id) => {
    // Logic to delete a product
    Swal.fire({
        title: 'Are you sure you want to delete this product?',
        showDenyButton: true,
        confirmButtonText: `Delete`,
        denyButtonText: `Cancel`,

      }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('client.products.destroy', id));
        }
      });
};
</script>
