<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps(['active']);

const classes = computed(() => props.active
    ? 'inline-flex items-center text-left w-full bg-indigo-400 text-white fill-white font-semibold py-3 px-4 border border-transparent rounded'
    : 'inline-flex items-center text-left w-full bg-transparent fill-black hover:bg-white hover:text-slate-800 hover:fill-black text-gray-800 font-semibold py-3 px-4 border border-transparent rounded'

);
</script>

<template>
    <Link :class="classes" :active="active">
        <slot />
    </Link>
</template>
