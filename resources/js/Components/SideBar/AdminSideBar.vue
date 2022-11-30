<script setup>
    // import inertia file
    import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <!-- content link -->
    <li class="flow-root">
        <Link :href="route('contents_view.index')"
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='spreadsheet'></box-icon>
            <span class="inline-block align-top">Content</span>
        </Link>
    </li>

    <!-- event link -->
    <li class="flow-root">
        <Link :href="route('events_view.index')"
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='calendar-event'></box-icon>
            <span class="inline-block align-top">Event</span>
        </Link>
    </li>

    <!-- appointment link -->
    <li class="flow-root">
        <Link :href="route('appointments_view.index')"
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='calendar-star'></box-icon>
            <span class="inline-block align-top">Appointment</span>
        </Link>
    </li>

    <!-- answer link -->
    <li class="flow-root">
        <Link :href="route('answers.index')"
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='book-heart'></box-icon>
            <span class="inline-block align-top">Report</span>
        </Link>
    </li>

    <!-- payment link -->
    <li class="flow-root">
        <Link :href="route('users_view.index')"
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='transfer-alt'></box-icon>
            <span class="inline-block align-top">Payments</span>
        </Link>
    </li>

    <!-- professional link -->
    <li class="flow-root">
        <Link :href="route('users_view.index')"
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" type='solid' name='user-detail'></box-icon>
            <span class="inline-block align-top">Users</span>
        </Link>
    </li>

    <!-- role link -->
    <li class="flow-root">
        <Link :href="route('roles_view.index')"
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='briefcase-alt-2'></box-icon>
            <span class="inline-block align-top">Role</span>
        </Link>
    </li>

    <!-- permission link -->
    <li class="flow-root">
        <Link :href="route('permissions_view.index')"
            class="inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-slate-600 hover:text-white hover:fill-white text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='layer'></box-icon>
            <span class="inline-block align-top">Permission</span>
        </Link>
    </li>

    <!-- default slot -->
    <slot/>
    
</template>

