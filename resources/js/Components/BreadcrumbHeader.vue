<template>
    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-5">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="flex bg-gray-800 justify-between items=center p-5">
                <div class="flex space-x-2 items-center text-white">
                    {{ title }}
                </div>
                <slot></slot>
            </div>
        </div>
    </div>

</template>


<script setup>
const props = defineProps({
    title: String,
});
</script>
