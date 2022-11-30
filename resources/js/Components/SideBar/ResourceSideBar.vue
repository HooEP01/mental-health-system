<script setup>
    // import inertia file
    import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <!-- content link -->
    <li class="flow-root">
        <Link :href="route('content.index')"
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='spreadsheet'></box-icon>
            <span class="inline-block align-top">Content</span>
        </Link>
    </li>

    <!-- event link -->
    <li class="flow-root">
        <Link :href="route('event.index')"
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='calendar-event'></box-icon>
            <span class="inline-block align-top">Event</span>
        </Link>
    </li>

    <!-- professional link -->
    <li class="flow-root">
        <Link 
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" type='solid' name='user-detail'></box-icon>
            <span class="inline-block align-top">Professional</span>
        </Link>
    </li>

    <slot/>
    
</template>

