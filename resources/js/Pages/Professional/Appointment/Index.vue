<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
// Import inertia
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        ProfessionalSideBar,
        Inertia,
        Link,
        Head,
    },
    props: {
        appointments: Object, default: () => ({}),
        search: Object, default: () => ({}),
        can: Object, default: () => ({}),
    },
    data() {
        return {
            search: '',
            status: '',
            
            statuses: [ "All", "Approve", "Paid", "Disapprove" ],
        }
    },
    watch: {
        search(value){
            Inertia.get(route('appointments.index'), 
            { search: value, status: this.status, page: 1 }, {
                preserveState: true,
                replace: true,
            });
        }
    },
    methods: {
        changeStatus(){
            Inertia.get(route('appointments.index'), 
            { search: this.search, status: this.status, page: 1 }, {
                preserveState: true,
                replace: true,
            });
        },
    }
}
</script>

<template>
    <!-- Header -->
    <Head title="Professional Appointment Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Professional</p>
                Appointment
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
                    Professional Appointment
                </template>
                <!--/ #Title -->
                
                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                </template>
                <!--/ #Subtitle -->

                <!-- #Feature -->
                <template #feature>
                    <!-- create content -->
                    <Link v-if="can.create" :href="route('events.create')" class="inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-indigo-600 hover:fill-white text-white bg-indigo-500 font-semibold py-3 px-4 border border-transparent rounded">
                    <box-icon class='mr-2' name='message-square-add'></box-icon>
                    <span class="inline-block align-top">Create New Event</span>
                    </Link>
                </template>
                <!--/ #Feature -->

                <!-- #Tool -->
                <template #tool>
                    <ProfessionalSideBar />
                </template>
                <!--/ #Tool -->

                <!-- #Main -->
                <template #main>

                    <!-- if empty -->
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                        <div class="px-4 sm:px-0">
                            <div class="border border-gray-400 sm:overflow-hidden sm:rounded-md overflow-x-scroll">
                                <div class="pace-y-6 bg-white px-4 py-5 sm:p-6">

                                    <div class="py-3 text-right">
                                        
                                        <select v-model="status" @change="changeStatus()" id="status" name="status" class="mt-1 mr-3 w-30 rounded-md border border-gray-400 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                            <option v-for="status in statuses" :key="status" :value="status">{{status.toUpperCase()}}</option>
                                        </select>

                                        <input v-model="search" type="text" name="title" id="title" autocomplete="title" placeholder="Search" required class="inline-flex justify-center mt-1 block w-30 rounded-md border-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <div class="overflow-x-auto">
                                    <!-- Table -->
                                    <table class="table-auto sm:rounded-md w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse border-b border-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase">
                                            <tr class="bg-white border-b border-gray-400">
                                                <th scope="col" class="py-3 px-6">#</th>
                                                <th scope="col" class="py-3 px-6">Title</th>
                                                <th scope="col" class="py-3 px-6">Name</th>
                                                <th scope="col" class="py-3 px-6">Date</th>
                                                <th scope="col" class="py-3 px-6">Time</th>
                                                <th scope="col" class="py-3 px-6">Reason</th>
                                                <th scope="col" class="py-3 px-6">Status</th>
                                                <th scope="col" class="py-3 px-6">View</th>
                                                <th scope="col" class="py-3 px-6">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="(appointment, index) in appointments.data" :key="appointment.id"
                                                class="bg-white border-b border-gray-400">
                                                <td data-label="Title" class="py-4 px-6">
                                                    {{ index + 1 }}
                                                </td>
                                                <td data-label="Title" class="py-4 px-6">
                                                    <Link :href="route('events.show', appointment.event_id)">
                                                        {{ appointment.event_title }}
                                                    </Link>
                                                </td>
                                                <td data-label="User_id" class="py-4 px-6">
                                                    {{ appointment.name }}
                                                </td>
                                                <td data-label="User_id" class="py-4 px-6">
                                                    {{ appointment.start_date }}
                                                </td>
                                                <td data-label="User_id" class="py-4 px-6">
                                                    {{ appointment.start_time }}
                                                </td>
                                                <td data-label="Description" class="py-4 px-6">
                                                    <p v-html="appointment.reason" class="mt-1 text-sm text-gray-500 line-clamp-3"></p>
                                                </td>
                                                
                                                
                                                <td data-label="Status" class="py-4 px-6">
                                                    {{ appointment.status }}
                                                </td>
                                                <td data-label="View" class="py-4 px-6">
                                                    <Link v-if="can.edit" :href="route('appointments.show', appointment.id)"
                                                        class="inline-flex items-center text-left fill-white text-white w-full bg-yellow-400 hover:bg-yellow-500 font-semibold py-3 px-4 border border-transparent rounded">
                                                        <box-icon class='mr-1' name='show-alt'></box-icon>
                                                        <span class="mr-1 inline-block align-top">View</span>
                                                    </Link>
                                                </td>
                                                <td data-label="Action" class="py-4 px-6">
                                                    <Link v-if="can.edit && appointment.status != 'Approve'" :href="route('appointments.edit', appointment.id)"
                                                        class="inline-flex items-center text-left fill-white text-white w-full bg-indigo-500 hover:bg-indigo-600 font-semibold py-3 px-4 border border-transparent rounded">
                                                        <box-icon class='mr-1' name='message-square-edit'></box-icon>
                                                        <span class="inline-block align-top">Approve</span>
                                                    </Link>
                                                    <Link v-else-if="can.edit" :href="route('appointments.edit', appointment.id)"
                                                        class="inline-flex items-center text-left fill-white text-white w-full bg-red-500 hover:bg-red-600 font-semibold py-3 px-4 border border-transparent rounded">
                                                        <box-icon class='mr-1' name='message-square-edit'></box-icon>
                                                        <span class="inline-block align-top">Disapprove</span>
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