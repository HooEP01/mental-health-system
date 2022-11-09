<script setup>
    // import inertia file
    import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <!-- content link -->
    <li class="flow-root">
        <Link :href="route('contents.index')"
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='spreadsheet'></box-icon>
            <span class="inline-block align-top">Post</span>
        </Link>
    </li>

    <!-- answer link -->
    <li class="flow-root">
        <Link 
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='calendar-event'></box-icon>
            <span class="inline-block align-top">Answer</span>
        </Link>
    </li>

    <!-- event link -->
    <li class="flow-root">
        <Link 
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='book-alt'></box-icon>
            <span class="inline-block align-top">Event</span>
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

    <!-- payment link -->
    <li class="flow-root">
        <Link 
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='book-alt'></box-icon>
            <span class="inline-block align-top">Payment</span>
        </Link>
    </li>

    <!-- user link -->
    <li class="flow-root">
        <Link 
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='book-alt'></box-icon>
            <span class="inline-block align-top">User</span>
        </Link>
    </li>

    <!-- role link -->
    <li class="flow-root">
        <Link 
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='book-alt'></box-icon>
            <span class="inline-block align-top">Role</span>
        </Link>
    </li>

    <!-- permission link -->
    <li class="flow-root">
        <Link 
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='book-alt'></box-icon>
            <span class="inline-block align-top">Permission</span>
        </Link>
    </li>

    <!-- default slot -->
    <slot/>
    
</template>

