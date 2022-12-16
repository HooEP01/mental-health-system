<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import AdminSideBar from '@/Components/SideBar/AdminSideBar.vue';
import Pagination from '@/Components/Pagination.vue';
// Import inertia
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        Pagination,
        AdminSideBar,
        Inertia,
        Link,
        Head,
    },
    props: {
        permissions: Object, default: () => ({}),
        can: Object, default: () => ({}),
        search: Object, default: () => ({}),
    },
    data() {
        return {
            search: '',
        }
    },
    watch: {
        search(value){
            Inertia.get(route('permissions_view.index'), 
            { search: value, page: 1 }, {
                preserveState: true,
                replace: true,
            });
        }
    }
}
</script>


<template>
    <!-- Header -->
    <Head title="Administrator Permission Show" />
    <!--/ Header -->
    
    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Administrator</p>
                Permission
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
                    Show Permission
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


                                    <div class="py-3 text-right">
                                        <input v-model="search" type="text" name="title" id="title" autocomplete="title" placeholder="Search" required class="inline-flex justify-center mt-1 block w-30 rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>


                                    <!-- Table -->
                                    <table class="table-auto sm:rounded-md w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse border-b border-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase">
                                            <tr class="bg-white border-b border-gray-400">
                                                <th scope="col" class="py-3 px-6">#</th>
                                                <th scope="col" class="py-3 px-6">Name</th>
                                                <th scope="col" class="py-3 px-6">Guard Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(permission, index) in permissions.data" :key="permission.id" class="bg-white border-b border-gray-400">             
                                                <td data-label="Title" class="py-4 px-6">
                                                    {{ index + 1 }}
                                                </td>
                                                <td data-label="Title" class="py-4 px-6">
                                                    {{ permission.name }}
                                                </td>
                                                <td data-label="Description" class="py-4 px-6">
                                                    {{ permission.guard_name }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--/ Table -->
                                    <pagination class="mt-6" :links="permissions.links" />
                                </div>
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