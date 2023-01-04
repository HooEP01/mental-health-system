<template>
    <div class="md:col-span-1 relative">
        <div class="px-4 pt-4 sm:pt-0 sm:px-0">
            <div class="sm:overflow-hidden sm:rounded-md">
                <div class="space-y-6 bg-indigo-50 px-4 py-5 sm:p-6">
                    <p v-if="slots.title" class="font-bold text-base text-slate-900">
                        <slot name="title"/>
                    </p>

                    <p v-if="slots.subtitle" class="mt-1 text-sm text-slate-600">
                        <slot name="subtitle"/>
                    </p>

                    <p v-if="slots.feature" class="font-bold text-base text-slate-900">Function</p>
                    <ul class="list-disc">
                        <slot name="feature"/>
                    </ul>
                        
                    <p v-if="slots.tool" class="font-bold text-base text-slate-900">Tool</p>
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