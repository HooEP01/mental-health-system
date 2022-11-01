<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import BreezeNavLink from '@/Components/NavLink.vue';
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
</script>
<template>

    <Head title="Post" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Post
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-5">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex bg-gray-800 justify-between items=center p-5">
                        <div class="flex space-x-2 items-center text-white">
                            Post Settings Page! Here you can list, create, update or delete post!
                        </div>
                        <div class="flex space-x-2 items-center" v-if="can.create">
                            <a href="#"
                                class="px-4 py-2 bg-green-500 uppercase text-white rounded focus:outline-none flex items-center"><span
                                    class="iconify mr-1" data-icon="gridicons:create" data-inline="false"></span> Create
                                Post</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-5">
                <div class="md:grid md:grid-cols-4 md:gap-3">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <ul class="list-disc">
                                <li class="flow-root">
                                    <button
                                        class="text-left w-64 bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-2 px-4 border border-transparent rounded">
                                        <box-icon name='spreadsheet'></box-icon> <span class="inline-block align-top"> Article</span>
                                </button>
                                </li>
                                <li class="flow-root">
                                    <button
                                        class="text-left w-64 bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-2 px-4 border border-transparent rounded">
                                        <box-icon name='notepad'></box-icon> <span class="inline-block align-top"> Case Study</span>
                                    </button>
                                </li>
                                <li class="flow-root">
                                    <button
                                        class="text-left w-64 bg-transparent hover:bg-slate-50 text-gray-800 font-semibold py-2 px-4 border border-transparent rounded">
                                        <box-icon name='calendar-event'></box-icon> <span class="inline-block align-top"> Event</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-5 md:col-span-3 md:mt-0">
                        <form action="#" method="POST">
                            <div class="shadow sm:overflow-hidden sm:rounded-md">
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <div class="grid grid-cols-3 gap-6">
                                        <div class="col-span-3 sm:col-span-2">
                                            <label for="company-website"
                                                class="block text-sm font-medium text-gray-700">Website</label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <span
                                                    class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">http://</span>
                                                <input type="text" name="company-website" id="company-website"
                                                    class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    placeholder="www.example.com">
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="about" class="block text-sm font-medium text-gray-700">About</label>
                                        <div class="mt-1">
                                            <textarea id="about" name="about" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                placeholder="you@example.com"></textarea>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">Brief description for your profile. URLs
                                            are hyperlinked.</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Photo</label>
                                        <div class="mt-1 flex items-center">
                                            <span
                                                class="inline-block h-12 w-12 overflow-hidden rounded-full bg-gray-100">
                                                <svg class="h-full w-full text-gray-300" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </span>
                                            <button type="button"
                                                class="ml-5 rounded-md border border-gray-300 bg-white py-2 px-3 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Change</button>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Cover photo</label>
                                        <div
                                            class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                    fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path
                                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="file-upload"
                                                        class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                                                        <span>Upload a file</span>
                                                        <input id="file-upload" name="file-upload" type="file"
                                                            class="sr-only">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                    <button type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">Title</th>
                                <th scope="col" class="py-3 px-6">Description</th>
                                <th v-if="can.edit || can.delete" scope="col" class="py-3 px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="post in posts.data" :key="post.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td data-label="Title" class="py-4 px-6">
                                    {{ post.title }}
                                </td>
                                <td data-label="Title" class="py-4 px-6">
                                    {{ post.description }}
                                </td>
                                <td v-if="can.edit || can.delete" class="py-4 px-6 w-48">
                                    <div type="justify-start lg:justify-end" no-wrap>
                                        <BreezeButton
                                            class="ml-4 bg-green-500 px-2 py-1 rounded text-white cursor-pointer"
                                            v-if="can.edit">
                                            Edit
                                        </BreezeButton>
                                        <BreezeButton
                                            class="ml-4 bg-red-500 px-2 py-1 rounded text-white cursor-pointer"
                                            v-if="can.delete">
                                            Delete
                                        </BreezeButton>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>