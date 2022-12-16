<script setup>
// import inertia
import { Link, usePage } from '@inertiajs/inertia-vue3';
import BtnLink from  '@/Components/BtnLink.vue';

</script>

<template>
    <!-- content link -->
    <li class="flow-root">
        <BtnLink :href="route('content.index')" :active="route().current('content.index')">
            <box-icon class="mr-2" name='spreadsheet'></box-icon>
            <span class="inline-block align-top">Content</span>
        </BtnLink>
    </li>

    <!-- event link -->
    <li class="flow-root">
        <BtnLink :href="route('event.index')" :active="route().current('event.index')">
            <box-icon class="mr-2" name='calendar-event'></box-icon>
            <span class="inline-block align-top">Event</span>
        </BtnLink>
    </li>

    <!-- professional link -->
    <!-- <li class="flow-root">
        <Link 
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" type='solid' name='user-detail'></box-icon>
            <span class="inline-block align-top">Professional</span>
        </Link>
    </li> -->

    <slot/>
    
</template>

