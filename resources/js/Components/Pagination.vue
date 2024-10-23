<template>
    <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6">
        <!-- Mobile Pagination -->
        <div class="flex flex-1 justify-between sm:hidden">
            <Link 
                v-if="previousUrl"
                :href="previousUrl"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Previous
            </Link>
            <Link 
                v-if="nextUrl"
                :href="nextUrl"
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Next
            </Link>
        </div>

        <!-- Desktop Pagination -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing {{ ' ' }}
                    <span class="font-medium">{{ meta.from }}</span> {{ ' ' }}
                    to <span class="font-medium">{{ meta.to }}</span> {{ ' ' }}
                    of <span class="font-medium">{{ meta.total }}</span> {{ ' ' }}
                    results
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm bg-white" aria-label="Pagination">
                    <Link 
                        v-for="link in meta.links"
                        :key="link.label"
                        :href="link.url ? link.url : '#'"
                        v-html="link.label"
                        :class="[ 
                            'relative inline-flex items-center px-3 py-2 text-sm font-medium',
                            link.active 
                                ? 'z-10 bg-indigo-600 text-white' 
                                : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50',
                            link.label === '&laquo; Previous' || link.label === 'Next &raquo;' 
                                ? 'rounded-md' 
                                : 'rounded-none'
                        ]"
                        @click.prevent="goToPage(link.url)"
                    >
                    </Link>
                    <!-- For disabled links or if link.url is null, render the span without an href -->
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'

const props = defineProps({
    meta: {
        type: Object,
        required: true
    },
});

const previousUrl = computed(() => props.meta.links[0]?.url);
const nextUrl = computed(() => [...props.meta.links].reverse()[0]?.url);

// Function to handle page navigation
const goToPage = (url) => {
    if (url) {
        const queryParams = new URL(url).searchParams;
        const page = queryParams.get('page');
        if (page) {
            // Send the user to the new page
            router.get(url);
        }
    }
};
</script>
