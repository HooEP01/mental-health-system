<script setup>

// import vue 
import { computed } from 'vue';

// import inertia
import { Link, usePage } from '@inertiajs/inertia-vue3';

// get permission list set from Middleware/HandleInertiaRequests.php
const can = computed(() => usePage().props.value.auth.can)
</script>

<template>
    <!-- user profile link -->
    <li class="flow-root">
        <Link :href="route('profile.index', 'user')"
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
        <box-icon class="mr-2" name='spreadsheet'></box-icon>
        <span class="inline-block align-top">User Profile</span>
        </Link>
    </li>

    <!-- user professional link -->

    <!-- professional profile link -->
    <li class="flow-root" v-if="can.profiles">
        <Link :href="route('profiles.index')"
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
        <box-icon class="mr-2" name='book-alt'></box-icon>
        <span class="inline-block align-top">Professional Profile</span>
        </Link>
    </li>

    <li class="flow-root" v-else>
        <Link :href="route('profile.index', 'professional')"
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
        <box-icon class="mr-2" name='calendar-event'></box-icon>
        <span class="inline-block align-top">Join Us</span>
        </Link>
    </li>


</template>