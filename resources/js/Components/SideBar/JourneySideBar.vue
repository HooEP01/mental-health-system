<script setup>
    // import inertia file
    import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <!-- content link -->
    <li class="flow-root">
        <Link :href="route('answer.index')"
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='spreadsheet'></box-icon>
            <span class="inline-block align-top">Answer</span>
        </Link>
    </li>

    <!-- appointment link -->
    <li class="flow-root">
        <Link 
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='book-alt'></box-icon>
            <span class="inline-block align-top">Appointment</span>
        </Link>
    </li>

    <!-- default slot -->
    <slot/>
    
</template>

