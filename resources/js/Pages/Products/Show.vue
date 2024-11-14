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
                    <div class="p-6 bg-gray-100 rounded-lg shadow-md">
                        <div v-if="review.canBeEditedOrDeleted">
                            <div class="flex items-center justify-end">
                                <button
                                    @click="deleteReview(review.id)"
                                    v-if="review.can?.delete"
                                    type="button"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button
                                    @click="editReview(review)"
                                    v-if="review.can?.update"
                                    type="button"
                                    class="ml-2"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </div>
                        </div>

                        <div
                            v-if="
                                editMode === review.id &&
                                review.canBeEditedOrDeleted
                            "
                        >
                            <textarea
                                v-model="form.review"
                                class="w-full p-4 border border-gray-300 rounded-lg shadow-md"
                                rows="4"
                            ></textarea>
                            <InputError
                                :message="form.errors.review"
                                class="mb-2"
                            />
                            <div class="flex justify-end mt-2">
                                <PrimaryButton
                                    @click="saveEdit(review.id)"
                                    type="button"
                                    class="bg-green-600 mr-2"
                                    >Save</PrimaryButton
                                >
                                <PrimaryButton
                                    @click="cancelEdit()"
                                    type="button"
                                    class="bg-gray-400"
                                    >Cancel</PrimaryButton
                                >
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-gray-700">{{ review.review }}</p>
                        </div>

                        <div class="mt-2 flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-900">{{
                                review.user.name
                            }}</span>
                            <span class="text-sm text-gray-500">{{
                                new Date(review.created_at).toLocaleDateString()
                            }}</span>
                        </div>

                        <!-- Like and Reply Buttons -->
                        <div class="mt-4 flex justify-between items-center">
                            <div>
                                <span class="text-pink-500 mt-2"
                                    >{{ review.likes_count }} likes</span
                                >
                            </div>
                            <div
                                v-if="$page.props.auth.user"
                                class="flex items-center space-x-4"
                            >
                                <button
                                    @click="replyToReview(review.id)"
                                    type="button"
                                    class="bg-indigo-600 py-1 px-4 rounded text-white"
                                >
                                    Reply
                                </button>
                                <button
                                    v-if="review.can.like"
                                    @click="onLike(review)"
                                    type="button"
                                    class="bg-pink-400 text-white py-1.5 px-3 rounded-full"
                                >
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </button>
                                <button
                                    v-else
                                    @click="onUnlike(review.id)"
                                    type="button"
                                    class="bg-pink-400 text-white py-1.5 px-3 rounded-full"
                                >
                                    <i class="fa-solid fa-thumbs-down"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Replies Section -->
                        <div
                            v-if="review.replies"
                            class="mt-4 space-y-4 pl-6 border-l-2 border-gray-200"
                        >
                            <div
                                v-for="reply in review.replies"
                                :key="reply.id"
                                class="p-3 bg-gray-50 rounded-lg shadow-sm"
                            >
                                <div class="flex justify-between">
                                    <span
                                        class="text-xs font-semibold text-gray-800"
                                        >{{ reply.user.name }}</span
                                    >
                                    <span class="text-xs text-gray-500">{{
                                        new Date(
                                            reply.created_at
                                        ).toLocaleDateString()
                                    }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">
                                    {{ reply.reply }}
                                </p>
                                <div
                                    class="mt-4 flex justify-between items-center"
                                >
                                    <div>
                                        <span class="text-pink-500 mt-2"
                                            >{{
                                                review.likes_count
                                            }}
                                            likes</span
                                        >
                                    </div>
                                    <div
                                        v-if="$page.props.auth.user"
                                        class="flex items-center space-x-4"
                                    >
                                        <button
                                            @click="replyToReview(review.id)"
                                            type="button"
                                            class="bg-indigo-600 py-0.5 px-4 rounded text-white"
                                        >
                                            Reply
                                        </button>
                                        <button
                                            v-if="review.can.like"
                                            @click="onLike(review)"
                                            type="button"
                                            class="bg-pink-400 text-white py-1.5 px-3 rounded-full"
                                        >
                                            <i
                                                class="fa-solid fa-thumbs-up"
                                            ></i>
                                        </button>
                                        <button
                                            v-else
                                            @click="onUnlike(review.id)"
                                            type="button"
                                            class="bg-pink-400 text-white py-1.5 px-3 rounded-full"
                                        >
                                            <i
                                                class="fa-solid fa-thumbs-down"
                                            ></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

const props = defineProps({
    product: Object,
    reviews: Object,
});

const showReview = ref(false);
const editMode = ref(null);
const originalReview = ref("");
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

const editReview = (review) => {
    editMode.value = review.id;
    originalReview.value = review.review;
    form.review = review.review;
};

const saveEdit = (id) => {
    form.put(route("review.update", id), {
        onSuccess: () => {
            editMode.value = null;
            form.reset();
        },
        preserveScroll: true,
    });
};

const cancelEdit = () => {
    form.review = originalReview.value; // Reset to original value
    editMode.value = null;
};

const deleteReview = async (id) => {
    const result = await Swal.fire({
        title: "Are you sure you want to delete this review?",
        showDenyButton: true,
        confirmButtonText: "Yes",
        denyButtonText: "Cancel",
    });

    if (result.isConfirmed) {
        try {
            router.delete(route("review.destroy", id), {
                preserveScroll: true,
            });
        } catch (error) {
            Swal.fire("Error", "Unable to delete review", "error");
        }
    }
};

onMounted(() => {
    console.log(props.reviews);
});

const onLike = (review) => {
    // Update the likes count immediately in the frontend
    review.likes_count++;
    review.can.like = false;

    // Send request without reloading or displaying a loading bar
    router.post(
        route("likes.store", ["review", review.id]),
        {},
        {
            only: ["reviews"], // Only get the updated reviews data if necessary
            preserveScroll: true,
            onSuccess: () => {
                // Optionally do something on success, like showing a small notification
            },
            onError: () => {
                // Roll back in case of error
                review.likes_count--;
                review.can.like = true;
            },
        }
    );
};

const onUnlike = (id) => {
    router.delete(route("likes.destroy", ["review", id]), {
        preserveScroll: true,
    });
};

// Function to handle adding the product to the cart
const addToCart = (productId) => {
    console.log(`Adding product with ID ${productId} to cart`);
};
</script>

<style scoped>
/* Additional custom styling if needed */
</style>
