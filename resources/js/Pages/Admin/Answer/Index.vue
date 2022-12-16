<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import AdminSideBar from '@/Components/SideBar/AdminSideBar.vue';
// Import inertia
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        AdminSideBar,
        Inertia,
        Link,
        Head,
    },
    props: {
        answers: Object, default: () => ({}),
        can: Object, default: () => ({}),
    },
}
</script>



<template>
    <!-- Header -->
    <Head title="User Content Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Administrator</p>
                Answer
            </div>
            <!--/ Title Header -->
        </template>
        <!--/ #Header -->

        <!-- #Content -->
        <template #content>
            <!-- Container With Sidebar -->
            <ContainerWithSideBar>
                <!-- #Title -->
                <template #title>
                    View Answer
                </template>
                <!--/ #Title -->

                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>
                <!--/ #Subtitle -->

                <!-- #Tool -->
                <template #tool>
                    <AdminSideBar />
                </template>
                <!--/ #Tool -->

                <!-- #Main -->
                <template #main>
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <div class="px-4 sm:px-0">
                            <div class="border border-gray-400 sm:overflow-hidden sm:rounded-md overflow-x-scroll">
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                    <!-- Table -->
                                    <table class="table-auto sm:rounded-md w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse border-b border-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase">
                                            <tr class="bg-white border-b border-gray-400">
                                                <th scope="col" class="py-3 px-6">#</th>
                                                <th scope="col" class="py-3 px-6">Title</th>
                                                <th scope="col" class="py-3 px-6">Description</th>
                                                <th scope="col" class="py-3 px-6">Name</th>
                                                <th scope="col" class="py-3 px-6">Status</th>
                                                <th scope="col" class="py-3 px-6">View</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="(content, index) in answers.data" :key="content.id"
                                                class="bg-white border-b border-gray-400">
                                                <td data-label="Title" class="py-4 px-6">
                                                    {{ index + 1 }}
                                                </td>
                                                <td data-label="Title" class="py-4 px-6">
                                                    {{ content.title }}
                                                </td>
                                                <td data-label="Description" class="py-4 px-6">
                                                    <p v-html="content.description" class="mt-1 text-sm text-gray-500 line-clamp-3"></p>
                                                </td>
                                                <td data-label="User_id" class="py-4 px-6">
                                                    <Link :href="route('users_view.edit', content.user_id)" class="font-semibold">
                                                        {{ content.user_name }}
                                                    </Link>
                                                    
                                                </td>
                                                <td data-label="Status" class="py-4 px-6">
                                                    {{ content.status }}
                                                </td>
                                                <td data-label="View" class="py-4 px-6">
                                                    <Link v-if="can.edit" :href="route('answers_view.show', content.id)"
                                                        class="inline-flex items-center text-left fill-white text-white w-full bg-yellow-400 hover:bg-yellow-500 font-semibold py-3 px-4 border border-transparent rounded">
                                                        <box-icon class='mr-1' name='show-alt'></box-icon>
                                                        <span class="mr-1 inline-block align-top">View</span>
                                                    </Link>
                                                </td>
                                              
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--/ Table -->
                                </div>
                            </div>
                        </div>
                    </div>
                </template> 
                <!--/ #Main -->
            </ContainerWithSideBar>
            <!-- end container with side bar -->
        </template>
        <!--/ #Content -->
    </BreezeAuthenticatedLayout>
    <!--/ Breeze Authenticated layout -->
</template>
