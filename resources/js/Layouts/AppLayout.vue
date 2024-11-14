<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-300">
            <nav class="bg-purple-900 border-b border-purple-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center" v-if="$page.props.auth.user && $page.props.auth.user.role_id === $page.props.roles.client">
                                <Link :href="route('client.dashboard')">
                                    <ApplicationMark class="block h-9 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex text-white">
                                <NavLink :href="route('client.dashboard')" :active="route().current('client.dashboard')" v-if="$page.props.auth.user && $page.props.auth.user.role_id === $page.props.roles.client">
                                    Dashboard
                                </NavLink>
                                <NavLink v-if="$page.props.auth.user && $page.props.auth.user.role_id === 3"  :href="route('client.products.index')" :active="route().current('products.index')">
                                    Products
                                </NavLink>
                                <NavLink v-else  :href="route('products.index')" :active="route().current('products.index')">
                                    Products
                                </NavLink>
                            </div>
                        </div>

                        <!-- Right Side of Navigation -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Authenticated User -->
                            <div v-if="$page.props.auth.user">
                                <Dropdown class="ms-3 relative" align="right" width="48">
                                    <template #trigger>
                                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                        </button>
                                    </template>
                                    <template #content class="text-black">
                                        <DropdownLink :href="route('profile.show')">Profile</DropdownLink>
                                        <DropdownLink as="button" @click="logout">Log Out</DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Guest User -->
                            <div v-else>
                                <NavLink :href="route('login')" class="text-black bg-gray-500">Log In</NavLink>
                                <NavLink :href="route('register')" class="ms-4 text-black">Register</NavLink>
                            </div>
                        </div>

                        <!-- Hamburger Menu for Mobile -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100  focus:text-gray-500  transition duration-150 ease-in-out" @click="showingNavigationDropdown = !showingNavigationDropdown">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1" v-if="$page.props.auth.user && $page.props.auth.user.role_id === $page.props.roles.client">
                        <ResponsiveNavLink :href="route('client.dashboard')" :active="route().current('client.dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>
                    <!-- Responsive Auth Links -->
                    <div class="pt-4 pb-1 border-t border-gray-200 ">
                        <div v-if="$page.props.auth.user">
                            <div class="px-4">
                                <div class="font-medium text-base text-gray-800 ">{{ $page.props.auth.user.name }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('profile.show')">Profile</ResponsiveNavLink>
                                <form @submit.prevent="logout">
                                    <ResponsiveNavLink as="button">Log Out</ResponsiveNavLink>
                                </form>
                            </div>
                        </div>
                        <div v-else>
                            <ResponsiveNavLink :href="route('login')">Log In</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('register')">Register</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <main>
                <slot />
            </main>
        </div>
        <footer>
            <div class="bg-purple-900 mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <p class="text-center text-sm text-white">
                    &copy; 2021 Company Name. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</template>
