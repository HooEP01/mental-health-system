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
        payments: Object, default: () => ({}),
        can: Object, default: () => ({}),
    },
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
                Payment
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
                    Show Payment
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
                                <div class="space-y-6 bg-white px-4 py-5 sm:p-6 overflow-x-auto">
                                    <!-- Table -->
                                    <table class="table-auto sm:rounded-md w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse border-b border-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase">
                                            <tr class="bg-white border-b border-gray-400">
                                                <th scope="col" class="py-3 px-6">#</th>
                                                <th scope="col" class="py-3 px-6">Title</th>
                                                <th scope="col" class="py-3 px-6">Name</th>
                                                <th scope="col" class="py-3 px-6">Start Date</th>
                                                <th scope="col" class="py-3 px-6">Start Time</th>
                                                <th scope="col" class="py-3 px-6">Status</th>
                                                <th scope="col" class="py-3 px-6">Price (MYR)</th>
                                                <th scope="col" class="py-3 px-6">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="(payment, index) in payments.data" :key="payment.id"
                                                class="bg-white border-b border-gray-400">
                                                <td data-label="Title" class="py-4 px-6">
                                                    {{ index + 1 }}
                                                </td>
                                                <td data-label="Title" class="py-4 px-6">
                                                    <Link :href="route('event.show', payment.event.id)" class="font-semibold">{{ payment.event.title }}</Link>
                                                </td>
                                                <td data-label="Description" class="py-4 px-6">
                                                    {{ payment.user_name }}                                    
                                                </td>
                                                <td data-label="Description" class="py-4 px-6">
                                                    {{ payment.start_date }}                                    
                                                </td>
                                                <td data-label="User_id" class="py-4 px-6">
                                                    {{ payment.start_time }}
                                                    <!-- {{ content.name }} -->
                                                </td>
                                                <td data-label="Status" class="py-4 px-6">
                                                    {{ payment.status.toUpperCase() }}
                                                </td>
                                                <td data-label="Amount" class="py-4 px-6">
                                                    {{ payment.amount }}
                                                </td>
                                                <td data-label="Action" class="py-4 px-6">
                                                    <Link v-if="can.edit && payment.status == 'Succeeded'" :href="route('appointments_view.show', payment.appointment_id)"
                                                        class="inline-flex items-center text-left fill-white text-white w-full bg-indigo-500 hover:bg-indigo-600 font-semibold py-3 px-4 border border-transparent rounded">
                                                        <box-icon class='mr-1' name='check'></box-icon>
                                                        <span class="inline-block align-top">Complete</span>
                                                    </Link>
                                                    <Link v-else-if="can.edit" :href="route('payment.create', payment.appointment_id)"
                                                        class="inline-flex items-center text-left fill-white text-white w-full bg-red-500 hover:bg-red-600 font-semibold py-3 px-4 border border-transparent rounded">
                                                        <box-icon class='mr-1' name='message-square-edit'></box-icon>
                                                        <span class="inline-block align-top">Paid</span>
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
             <!--/ Container With Sidebar -->
        </template>
        <!--/ #Content -->
    </BreezeAuthenticatedLayout>
    <!--/ Breeze Authenticated layout -->
</template>