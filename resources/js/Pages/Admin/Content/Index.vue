<script setup>

// import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import AdminSideBar from '@/Components/SideBar/AdminSideBar.vue';

// import inertia
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    contents: {
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


<Head title="Admin Content" />
    <BreezeAuthenticatedLayout>
        <template #header>
            Administrator
        </template>

        <template #content>

            <ContainerWithSideBar>

                <template #title>
                    View Your Content
                </template>

                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>

                <!-- <template #feature>
                    create content
                    <Link v-if="can.create" :href="route('contents.create')"
                        class="inline-flex items-center text-left w-full bg-transparent hover:bg-green-100 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
                    <box-icon class='mr-2' name='message-square-add'></box-icon>
                    <span class="inline-block align-top">Create New Content</span>
                    </Link>
                </template> -->

                <template #tool>
                    <!-- admin side bar -->
                    <AdminSideBar>
                        <!-- null -->
                    </AdminSideBar>
                </template>

                <template #main>

                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">Title</th>
                                <th scope="col" class="py-3 px-6">Description</th>
                                <th scope="col" class="py-3 px-6">User Name</th>
                                <th scope="col" class="py-3 px-6">Status</th>
                                <th scope="col" class="py-3 px-6">View</th>
                                <th scope="col" class="py-3 px-6">Action</th>
                                <!-- <th v-if="can.edit || can.delete" scope="col" class="py-3 px-6">Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="content in contents.data" :key="content.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td data-label="Title" class="py-4 px-6">
                                    {{ content.title }}
                                </td>
                                <td data-label="Description" class="py-4 px-6">
                                    <p v-html="content.description" class="mt-1 text-sm text-gray-500 line-clamp-3"></p>
                                </td>
                                <td data-label="User_id" class="py-4 px-6">
                                    {{ content.name.toUpperCase() }}
                                </td>
                                <td data-label="Status" class="py-4 px-6">
                                    {{ content.status.toUpperCase() }}
                                </td>
                                <td data-label="View" class="py-4 px-6">
                                    
                                </td>
                                <td data-label="Action" class="py-4 px-6">
                                    <Link v-if="can.edit" :href="route('contents_view.edit', content.id)"
                                        class="inline-flex items-center text-left w-full bg-green-100 hover:bg-green-200 text-gray-800 font-semibold py-3 px-4 border border-transparent rounded">
                                        <box-icon class='mr-2' name='message-square-edit'></box-icon>
                                        <span v-if="content.status != 'approve'" class="inline-block align-top">Approve</span>
                                        <span v-else class="inline-block align-top">Disapprove</span>
                                    </Link>
                                </td>





                                <!-- <td v-if="can.edit || can.delete" class="py-4 px-6 w-48">
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
                                </td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>

                    </div>
                
                </template>

            </ContainerWithSideBar>
        
        </template>

    </BreezeAuthenticatedLayout>

</template>