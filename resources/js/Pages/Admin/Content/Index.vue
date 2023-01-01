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
        contents: Object, default: () => ({}),
        can: Object, default: () => ({}), 
        search: Object, default: () => ({}),
    },
    data() {
        return {
            search: '',
            status: '',
            category: '',
            categories: ['All', 'Article', 'Case Study', 'Survey', 'Journal', 'Report'],
            statuses: [ "All", "Approve", "Disapprove", "Complete", "Draft" ],
        }
    },
    watch: {
        search(value){
            Inertia.get(route('contents_view.index'), 
            { search: value, status: this.status, category: this.category, page: 1 }, {
                preserveState: true,
                replace: true,
            });
        }
    },
    methods: {
        changeStatus(){
            Inertia.get(route('contents_view.index'), 
            { search: this.search, status: this.status, category: this.category, page: 1 }, {
                preserveState: true,
                replace: true,
            });
        },
        changeCategory(){
            Inertia.get(route('contents_view.index'), 
            { search: this.search, status: this.status, category: this.category, page: 1 }, {
                preserveState: true,
                replace: true,
            });
        },
    }
}
</script>


<template>
    <!-- Header -->
    <Head title="Administrator Content Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
             
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Administrator</p>
                Content
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
                    View Admin Contents
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
                        <div class="border border-gray-400 sm:overflow-hidden sm:rounded-md overflow-x-scroll">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                
                                <div class="grid grid-cols-12">
                                    <div class="col-span-12 sm:col-span-3 sm:mr-3">
                                        <div class="sm:flex flex-row">
                                            <label for="category" class="pr-3 pt-3 inline-block text-sm font-medium text-slate-600">Category</label>
                                            <select v-model="category" @change="changeCategory()" id="category" name="category" class="inline-flex justify-center mt-1 block w-full rounded-md border border-gray-400 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="category in categories" :key="category" :value="category">{{category.toUpperCase()}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-3 sm:mr-3">
                                        <div class="sm:flex flex-row">
                                            <label for="status" class="pr-3 pt-3 inline-block text-sm font-medium text-slate-600">Status</label>
                                            <select v-model="status" @change="changeStatus()" id="status" name="status" class="inline-flex justify-center mt-1 block w-full rounded-md border border-gray-400 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                <option v-for="status in statuses" :key="status" :value="status">{{status.toUpperCase()}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 sm:mr-3">
                                        <div class="sm:flex flex-row">
                                            <label for="search" class="pr-3 pt-3 inline-block text-sm font-medium text-slate-600">Search</label>
                                            <input v-model="search" type="text" name="title" id="title" autocomplete="title" placeholder="Search" required class="inline-flex justify-center mt-1 block w-full rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                </div>

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
                                            <th scope="col" class="py-3 px-6">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(content, index) in contents.data" :key="content.id" class="bg-white border-b border-gray-400">
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
                                                {{ content.name }}
                                            </td>
                                            <td data-label="Status" class="py-4 px-6">
                                                {{ content.status }}
                                            </td>
                                            <td data-label="View" class="py-4 px-6">
                                                <Link v-if="can.edit" :href="route('contents_view.show', content.id)" class="inline-flex items-center text-left fill-white text-white w-full bg-yellow-400 hover:bg-yellow-500 font-semibold py-3 px-4 border border-transparent rounded">
                                                    <box-icon class='mr-1' name='show-alt'></box-icon>
                                                    <span class="mr-1 inline-block align-top">View</span>
                                                </Link>
                                            </td>
                                            <td data-label="Action" class="py-4 px-6">
                                                <Link v-if="can.edit && content.status != 'Approve'" :href="route('contents_view.edit', content.id)" class="inline-flex items-center text-left fill-white text-white w-full bg-indigo-500 hover:bg-indigo-600 font-semibold py-3 px-4 border border-transparent rounded">
                                                    <box-icon class='mr-1' name='message-square-edit'></box-icon>
                                                    <span class="inline-block align-top">Approve</span>
                                                </Link>
                                                <Link v-else-if="can.edit" :href="route('contents_view.edit', content.id)" class="inline-flex items-center text-left fill-white text-white w-full bg-red-500 hover:bg-red-600 font-semibold py-3 px-4 border border-transparent rounded">
                                                    <box-icon class='mr-1' name='message-square-edit'></box-icon>
                                                    <span class="inline-block align-top">Reject</span>
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--/ Table -->
                            </div>
                        </div>
                    </div>
                </template>
                <!--/ #Main -->
            </ContainerWithSideBar>
             <!--/ Container With Sidebar -->
        </template>
        <!--/ #Content -->
    </BreezeAuthenticatedLayout>
    <!--/ Breeze Authenticated layout -->
</template>