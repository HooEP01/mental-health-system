<script setup>
import { computed } from 'vue';

const props = defineProps(['active']);

const classes = computed(() => props.active
    ? 'inline-flex items-center px-1 pt-0 border-b-2 text-base font-semibold leading-5 text-slate-900 focus:outline-none border-indigo-400 focus:border-indigo-300 transition duration-150 ease-in-out'
    : 'inline-flex items-center px-1 pt-0 border-transparent text-base font-semibold leading-5 text-slate-900 hover:text-slate-900 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out'
);
</script>

<template>
    <Button :class="classes">
        <slot />
    </Button>
</template>
