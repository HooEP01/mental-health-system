<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import BreezeNavLink from '@/Components/NavLink.vue';
import BreadcrumbHeader from '@/Components/BreadcrumbHeader.vue';

const props = defineProps({
    posts: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
})

function destroy(id) {
    Inertia.delete(route('professional-post.destroy', id));
}

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
                    <a :href="route('professional-post.create')"
                        class="px-4 py-2 bg-violet-500 uppercase text-white rounded focus:outline-none flex items-center">
                        + Create New Post
                    </a>
                </div>
            </BreadcrumbHeader>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-5">
                <div class="md:grid md:grid-cols-4 md:gap-3">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <ul class="list-disc">
                                <li class="flow-root">
                                    <button
                                        class="text-left w-64 bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-2 px-4 border border-transparent rounded">
                                        <box-icon name='spreadsheet'></box-icon> <span class="inline-block align-top">
                                            Post</span>
                                    </button>
                                </li>
                                <li class="flow-root">
                                    <button
                                        class="text-left w-64 bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-2 px-4 border border-transparent rounded">
                                        <box-icon name='notepad'></box-icon> <span class="inline-block align-top"> Case
                                            Study</span>
                                    </button>
                                </li>
                                <li class="flow-root">
                                    <button
                                        class="text-left w-64 bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-2 px-4 border border-transparent rounded">
                                        <box-icon name='calendar-event'></box-icon> <span
                                            class="inline-block align-top"> Event</span>
                                    </button>
                                </li>
                                <li class="flow-root">
                                    <button
                                        class="text-left w-64 bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-2 px-4 border border-transparent rounded">
                                        <box-icon name='notepad'></box-icon> <span class="inline-block align-top">
                                            Professional</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div
                        class="md:col-span-3 md:mt-0 mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-1 lg:grid-cols-3 xl:gap-x-4">
                        <div v-for="post in posts.data" :key="post.id" class="group relative">
                            <div
                                class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                                <Link :href="route('professional-post.show', post.id)">
                                    <img v-if="post.image" :src="'/storage/' + post.image" alt="image"
                                        class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                                    <img v-else
                                        src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg"
                                        alt="images" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                                </Link>
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700 line-clamp-1">
                                        <a>
                                            <span aria-hidden="true" class="absolute"></span>
                                            {{ post.title }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 line-clamp-3">{{ post.description }}...</p>
                                </div>
                                <div>
                                    <Link :href="route('professional-post.edit', post.id)">
                                    <box-icon name='edit'></box-icon>
                                    </Link>
                                    <Link @click="destroy(post.id)">
                                    <box-icon name='folder-minus'></box-icon>
                                    </Link>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>