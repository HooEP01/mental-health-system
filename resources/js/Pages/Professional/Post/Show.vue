<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import BreezeNavLink from '@/Components/NavLink.vue';
import BreadcrumbHeader from '@/Components/BreadcrumbHeader.vue';

const props = defineProps({
    post: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
})

</script>
    
<template>

    <Head title="Post" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Professional
            </h2>
        </template>
        <div class="py-12">

            <BreadcrumbHeader title="Professional Post Page! Here you can create, edit, and delete your own posts.">
                <div class="flex space-x-2 items-center">
                    <a :href="route('professional-post.index')"
                        class="px-4 py-2 bg-violet-500 uppercase text-white rounded focus:outline-none flex items-center">
                        > Back
                    </a>
                </div>
            </BreadcrumbHeader>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-5">
                <div class="md:grid md:grid-cols-4 md:gap-3">

                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <ul class="list-disc">

                            </ul>
                        </div>
                    </div>


                    <div class="mt-5 md:col-span-3 md:mt-0">
                        <div class="shadow sm:overflow-hidden sm:rounded-md">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <h3>{{ post.title }}</h3>
                                {{ post.description }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>