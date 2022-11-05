
<script setup>
    // inertia import file
    import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <!-- user link -->
    <li class="flow-root">
        <Link v-if="permission.user" :href="route('admin-user.index')" :active="route().current('admin-user.index')"
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='spreadsheet'></box-icon>
            <span class="inline-block align-top">User</span>
        </Link>
    </li>

    <!-- permission link -->
    <li class="flow-root">
        <Link v-if="permission.permission" :href="route('admin-permission.index')" :active="route().current('admin-permission.index')"
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='spreadsheet'></box-icon>
            <span class="inline-block align-top">Permission</span>
        </Link>
    </li>

    <!-- role link -->
    <li class="flow-root">
        <Link  v-if="permission.role" :href="route('admin-role.index')" :active="route().current('admin-role.index')"
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='spreadsheet'></box-icon>
            <span class="inline-block align-top">Role</span>
        </Link>
    </li>

    <!-- post link -->
    <li class="flow-root">
        <Link  v-if="permission.adminPostList" :href="route('admin-post.index')" :active="route().current('admin-post.index')"
            class="inline-flex items-center text-left w-full bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
            <box-icon class="mr-2" name='spreadsheet'></box-icon>
            <span class="inline-block align-top">Role</span>
        </Link>
    </li>
    

   
</template>
