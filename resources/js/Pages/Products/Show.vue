<template>
    <AppLayout :title="product.name">
        <div class="container mx-auto py-10 px-4">
            <!-- Product Details Section -->
            <div
                class="flex flex-col md:flex-row md:space-x-8 space-y-6 md:space-y-0"
            >
                <!-- Product Image -->
                <div class="w-full md:w-1/2">
                    <img
                        src="https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg"
                        :alt="product.name"
                        class="rounded-lg shadow-lg object-cover w-full h-auto"
                    />
                </div>

                <!-- Product Info -->
                <div class="w-full md:w-1/2">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        {{ product.name }}
                    </h1>
                    <p class="text-lg text-gray-700 mb-6">
                        {{ product.description }}
                    </p>

                    <!-- Price -->
                    <div class="text-2xl font-semibold text-blue-600 mb-4">
                        ${{ product.price }}
                    </div>

                    <!-- Add to Cart Button -->
                    <button
                        @click="addToCart(product.id)"
                        class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow hover:bg-blue-700 transition-all"
                    >
                        Add to Cart
                    </button>
                </div>
            </div>

            <!-- Ratings & Reviews Section -->
            <div class="mt-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    User Ratings & Reviews
                </h2>

                <!-- Ratings Overview -->
                <div class="flex items-center mb-4">
                    <div class="text-yellow-400">
                        <!-- Star Ratings -->
                        <svg
                            v-for="n in 5"
                            :key="n"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                            class="w-6 h-6 inline-block"
                            :class="{ 'text-gray-300': n > product.rating }"
                        >
                            <path
                                d="M12 17.75l-6.42 3.85 1.76-7.35L1.3 9.26l7.46-.61L12 2.5l3.24 6.15 7.46.61-6.04 4.99 1.76 7.35L12 17.75z"
                            />
                        </svg>
                    </div>
                    <span class="ml-2 text-lg text-gray-700"
                        >{{ product.rating }} / 5</span
                    >
                </div>

                <form v-if="$page.props.auth.user">
                    <div class="mb-4 flex justify-end">
                        <PrimaryButton
                            @click="showReviewArea()"
                            type="button"
                            :class="showReview ? 'bg-red-500' : 'bg-blue-500'"
                            v-text="showReview ? 'Cancel' : 'Add Review'"
                        ></PrimaryButton>
                        <PrimaryButton
                            @click="saveReview()"
                            :disabled="form.processing"
                            type="submit"
                            v-if="showReview"
                            class="bg-green-600 ml-3"
                            >Save</PrimaryButton
                        >
                    </div>
                    <div v-if="showReview">
                        <textarea
                            v-model="newReview"
                            class="w-full p-4 border border-gray-300 rounded-lg shadow-md"
                            rows="4"
                            placeholder="Write your review here"
                        ></textarea>
                        <InputError
                            :message="form.errors.review"
                            class="mb-2"
                        />
                    </div>
                </form>

                <!-- Reviews List -->
                <div
                    class="space-y-6 mb-2"
                    v-for="review in reviews.data"
                    :key="review.id"
                >
                    <Item :review="review" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { defineProps, onMounted, ref } from "vue";
import Swal from "sweetalert2";
import { router } from "@inertiajs/vue3";
import Item from "./Reviews/Item.vue";

const props = defineProps({
    product: Object,
    reviews: Object,
});

const showReview = ref(false);
const newReview = ref(""); // Separate variable for adding new reviews

const form = useForm({
    review: "",
});

const showReviewArea = () => {
    showReview.value = !showReview.value;
    newReview.value = ""; // Clear new review input when toggling
};

const saveReview = () => {
    form.review = newReview.value; // Use newReview for saving a new review
    form.post(route("review.store", { product: props.product.id }), {
        onSuccess: () => {
            showReview.value = false;
            newReview.value = ""; // Reset new review field after saving
        },
        preserveScroll: true,
    });
};

onMounted(() => {
    console.log(props.reviews);
});

// Function to handle adding the product to the cart
const addToCart = (productId) => {
    console.log(`Adding product with ID ${productId} to cart`);
};
</script>

<style scoped>
/* Additional custom styling if needed */
</style>
