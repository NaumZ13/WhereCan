<template>
    <AppLayout title="Create Product">
        <div class="flex flex-col min-h-screen items-center p-10">
            <div class="mb-6">
                <h1 class="font-bold text-5xl">Create Product</h1>
            </div>
            <form @submit.prevent="submit" class="w-full max-w-lg">
                <div class="mb-4">
                    <InputLabel for="name" value="Product Name" />
                    <TextInput
                        id="name"
                        type="text"
                        v-model="form.name"
                        class="mt-1 w-full bg-gray-200"
                        
                        placeholder="Enter product name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mb-4">
                    <InputLabel for="description" value="Product Description" />
                    <textarea id="description" v-model="form.description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-200 rounded-lg border border-gray-900 focus:ring-blue-500 focus:border-blue-500" placeholder="Prudoct Description ( Optional )"></textarea>
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
                
                <div class="mb-4">
                    <InputLabel for="price" value="Product Price" />
                    <TextInput
                        id="price"
                        type="number"
                        v-model="form.price"
                        class="mt-1 w-full bg-gray-200"
                        required
                        placeholder="Enter product price"
                        step="0.01"
                    />
                    <InputError class="mt-2"  :message="form.errors.price" />
                </div>

                <div class="flex justify-between">
                <Link :href="route('client.products.index')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                    Back
                </Link>
                
                <SecondaryButton type="submit">
                    Edit
                </SecondaryButton>
                </div>
            </form>
        </div>
    </AppLayout>
</template>


<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    product: {
        type: Object,
    },
});

const form = useForm({
    name: props.product.name ?? '',
    description: props.product.description ?? '',
    price: props.product.price ?? '',
});

const submit = () => {
    form.put(route('client.products.update', props.product.id));
};

</script>