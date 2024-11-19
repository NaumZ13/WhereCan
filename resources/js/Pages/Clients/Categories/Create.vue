<template>
    <AppLayout title="Create Category">
        <div class="flex flex-col min-h-screen items-center p-10">
            <div class="mb-6">
                <h1 class="font-bold text-5xl">Create Category</h1>
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
                    <label
                        for="category"
                        class="block mb-2 text-sm font-medium text-gray-900"
                    >
                        Category
                    </label>
                    <select
                        id="parent_id"
                        v-model="form.parent_id"
                        class="w-full p-2 border border-gray-300 rounded-lg"
                    >
                        <option value="" disabled selected>Choose a Category</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                    </select>
                </div>

                <div class="flex justify-between">
                <Link :href="route('client.dashboard')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                    Back
                </Link>
                
                <SecondaryButton type="submit">
                    Submit
                </SecondaryButton>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps(['categories']);

const form = useForm({
    name: '',
    parent_id: '',
});

const submit = () => {
    form.post(route('client.categories.store'));
};
</script>