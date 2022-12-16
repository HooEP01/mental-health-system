<script setup>

// import vue 
import { computed } from 'vue';

// import inertia
import { Link, usePage } from '@inertiajs/inertia-vue3';
import BtnLink from  '@/Components/BtnLink.vue';

// get permission list set from Middleware/HandleInertiaRequests.php
const can = computed(() => usePage().props.value.auth.can)
</script>

<template>
    <!-- user profile link -->
    <li class="flow-root">
        <BtnLink :href="route('profile.index')" :active="route().current('profile.index')">
        <box-icon class="mr-2" name='spreadsheet'></box-icon>
        <span class="inline-block align-top">User Profile</span>
        </BtnLink>
    </li>
    <!-- user professional link -->

    <!-- professional profile link -->
    <li class="flow-root" v-if="can.profiles">
        <BtnLink :href="route('profiles.index')" :active="route().current('profiles.index')">
        <box-icon class="mr-2" name='book-alt'></box-icon>
        <span class="inline-block align-top">Professional Profile</span>
        </BtnLink>
    </li>

    <li class="flow-root" v-else>
        <BtnLink :href="route('profile.show', 'professional')" :active="route().current('profile.show', 'professional')">
        <box-icon class="mr-2" name='calendar-event'></box-icon>
        <span class="inline-block align-top">Join Us</span>
        </BtnLink>
    </li>


</template>