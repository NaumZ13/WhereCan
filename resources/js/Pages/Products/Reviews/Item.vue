<template>
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

        <div v-if="editMode === review.id && review.canBeEditedOrDeleted">
            <textarea
                v-model="form.review"
                class="w-full p-4 border border-gray-300 rounded-lg shadow-md"
                rows="4"
            ></textarea>
            <InputError :message="form.errors.review" class="mb-2" />
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
                    <span class="text-xs font-semibold text-gray-800">{{
                        reply.user.name
                    }}</span>
                    <span class="text-xs text-gray-500">{{
                        new Date(reply.created_at).toLocaleDateString()
                    }}</span>
                </div>
                <p class="text-sm text-gray-600 mt-2">
                    {{ reply.reply }}
                </p>
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
                            class="bg-indigo-600 py-0.5 px-4 rounded text-white"
                        >
                            Reply
                        </button>
                        <button
                            v-if="review.can.like"
                            @click="onLike(review)"
                            type="button"
                            class="bg-pink-400 text-white py-0.5 px-2 rounded-full"
                        >
                            <i class="fa-solid fa-thumbs-up"></i>
                        </button>
                        <button
                            v-else
                            @click="onUnlike(review.id)"
                            type="button"
                            class="bg-pink-400 text-white py-0.5 px-1.5 rounded-full"
                        >
                            <i class="fa-solid fa-thumbs-down"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";
import Swal from "sweetalert2";
import { router } from "@inertiajs/vue3";
import { Link, useForm } from "@inertiajs/vue3";

const props = defineProps(["review"]);

const editMode = ref(null);
const originalReview = ref("");

const form = useForm({
    review: "",
});

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

</script>