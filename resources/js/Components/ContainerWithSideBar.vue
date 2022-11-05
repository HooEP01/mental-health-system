<template>
    <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
            <div class="shadow sm:overflow-hidden sm:rounded-md">
                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                    <p v-if="slots.title">
                        <slot name="title"/>
                    </p>

                    <p v-if="slots.subtitle" class="mt-1 text-sm text-gray-600">
                        <slot name="subtitle"/>
                    </p>

                    <p v-if="slots.feature">Function</p>
                    <ul class="list-disc">
                        <slot name="feature"/>
                    </ul>
                        
                    <p v-if="slots.tool">Tool</p>
                    <ul class="list-disc">
                        <slot name="tool"/>
                    </ul>
       
                </div>
            </div>
        </div>
    </div>

    <slot name="main"/>
</template>

<script setup>
// import useSlots
import { useSlots } from 'vue'
// use slots
const slots = useSlots()

</script>