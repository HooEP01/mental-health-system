<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavTabButton from '@/Components/NavTabButton.vue';
import NavTabBar from '@/Components/TopBar/NavTabBar.vue';

import TaskList from '@/Components/User/TaskList.vue'
import ChatList from '@/Components/User/ChatList.vue'
// Import inertia
import { useForm, Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref } from "vue";
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        ProfessionalSideBar,
        DropdownLink,
        NavTabButton,
        NavTabBar,
        Dropdown,
        Inertia,
        useForm,
        Head,
        Link,
        ref,
        TaskList,
        ChatList,
    },
    data(props) {
        return {
            tab: props.firstTab,
        };
    },
    props: {
        event: Object, default: () => ({}),
        appointment: Object, default:() => ({}),
        appointments: Object, default:() => ({}),
        tasks: Object, default:() => ({}),
        professional: Object, default:() => ({}),
        firstTab: Object, default:() => ({}),
        chats: Object, default:() => ({}),
        can: Object, default: () => ({}),
    },
    methods: {
        // Destroy Content
        destroy(id) {
            Inertia.delete(route('appointment.destroy', id));
        },
        // Active Tab
        activeTab(name) {
            this.tab = name;
        },
    }
}
</script>


<style setup>
.prose {
    max-width: none;
}
</style>
    

<template>
    <!-- Header -->
    <Head title="User Appointment Show" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Journey</p>
                Appointment: {{ event.title }}
                <p class="text-base font-sm">
                    {{ appointment.start_date }} {{ appointment.start_time }}
                </p>
            </div>
            <!--/ Title Header -->

            <!-- NavTabBar -->
            <NavTabBar>
                <!-- Back Tab -->
                <li class="mr-6">
                    <Link :href="route('appointment.index')">
                        <NavTabButton class="inline-block p-4 rounded-t-lg border-b-2"> 
                            <box-icon class='mr-2' name='arrow-back'></box-icon>
                            <span class="inline-block align-top"> Back </span>
                        </NavTabButton>
                    </Link>
                </li>
                <!--/ Back Tab -->

                <!-- Content Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('event')" :active="tab === 'event'" class="inline-block p-4 rounded-t-lg border-b-2"> 
                        <box-icon class='mr-2' name='book-heart'></box-icon>
                        <span class="inline-block align-top">{{ event.category }}</span>
                    </NavTabButton>
                </li>
                <!--/ Content Tab -->

                <!-- Task Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('task')" :active="tab === 'task'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Task</span>
                    </NavTabButton>
                </li>
                <!--/ Task Tab -->

                <!-- Chat Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('chat')" :active="tab === 'chat'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='message-square-dots'></box-icon>
                        <span class="inline-block align-top">Chat</span>
                    </NavTabButton>
                </li>
                <!--/ Chat Tab -->

                <!-- Member Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('member')" :active="tab === 'member'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='cog'></box-icon>
                        <span class="inline-block align-top">Member</span>
                    </NavTabButton>
                </li>
                <!--/ Member Tab -->


            </NavTabBar>
            <!--/ NavTabBar -->      
        </template>
        <!--/ #Header -->

        <!-- #Content -->
        <template #content>
            <!-- Container With Sidebar -->
            <ContainerWithSideBar>
                <!-- #Title -->
                <template #title>
                    Show {{ event.category }}
                </template>
                <!--/ #Title -->

                <!-- #Subtitle -->
                <template #subtitle>
                    This information will be displayed publicly so be careful what you share.
                    <div class="pt-4">
                        <div v-if="event.image" class="sm:overflow-hidden sm:rounded-md">
                            <img :src="'/storage/' + event.image" class="w-199 h-100 object-cover" />
                        </div>
                    </div>
                </template>
                <!--/ #Subtitle -->
                    
                <!-- #Main -->
                <template #main>
                    <div class="mt-5 md:col-span-3 md:mt-0 px-4 sm:px-0">
                                         
                        <!-- Event Tab -->
                        <div v-if="tab === 'event'" class="sm:overflow-hidden sm:rounded-md">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <div v-html="event.description" class="prose w-full text-slate-600"></div>
                            </div>
                        </div>
                        <!--/ Event Tab -->


                        <!-- Task Tab -->
                        <div v-if="tab === 'task'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <TaskList :tasks="tasks" :appointmentID="appointment.id" />
                        </div>
                        <!--/ Task Tab -->

                        <!-- Chat Tab -->
                        <div v-if="tab === 'chat'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <ChatList :chats="chats" :appointment="appointment"/>
                        </div>
                        <!--/ Chat Tab  -->
                       
                        <!-- Member Tab -->
                        <div v-if="tab === 'member'" class="sm:overflow-hidden sm:rounded-md mt-2 pt-2">
                            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                <!-- Owner -->
                                <h1 class="text-3xl font-bold">Owner</h1>
                                <div class="border border-gray-400 sm:overflow-hidden sm:rounded-md">
                                    <div class="flex justify-between">
                                        <div class="px-6 py-6 font-bold">
                                            {{ 1 }}. {{ professional.professional_title }} {{ professional.first_name }} {{ professional.last_name }}
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-left px-6 pb-6">
                                        <span class="inline-block h-12 w-12 overflow-hidden rounded-full bg-gray-100">
                                            <img v-if="professional.image" :src="'/storage/' + professional.image" class="w-64 h-48 object-cover" />
                                            <svg v-else class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                        <p class="text-base font-medium text-slate-600 mt-3">{{ professional.professional_description }}</p>
                                    </div>
                                </div>
                                <!-- Member -->
                                <h1 class="text-3xl font-bold">Member</h1>
                                <div class="border border-gray-400 sm:overflow-hidden sm:rounded-md overflow-x-scroll">
                                    <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                                        <!-- Table -->
                                        <table class="table-auto sm:rounded-md w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse border-b border-gray-400">
                                            <thead class="text-xs text-gray-700 uppercase">
                                                <tr class="bg-white border-b border-gray-400">
                                                    <th scope="col" class="py-3 px-6">#</th>
                                                    <th scope="col" class="py-3 px-6">User</th>
                                                    <th scope="col" class="py-3 px-6">Name</th>
                                                    <th scope="col" class="py-3 px-6">Email</th>
                                                    <th scope="col" class="py-3 px-6">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(appoint, index) in appointments" class="bg-white border-b border-gray-400">
                                                    <td data-label="Title" class="py-4 px-6">
                                                        {{ index + 1 }}
                                                    </td>
                                                    <td data-label="Title" class="py-4 px-6">
                                                        {{ appoint.name }}
                                                    </td>
                                                    <td data-label="User_id" class="py-4 px-6">
                                                        {{ appoint.first_name }} {{ appoint.last_name }}
                                                    </td>
                                                    <td data-label="Description" class="py-4 px-6">
                                                        {{ appoint.email }}
                                                    </td>
                                                    <td v-if="appoint.id === appointment.id">
                                                        <Link @click="destroy(appointment.id)" class="inline-flex items-center text-left fill-white text-white w-30 bg-red-400 hover:bg-red-500 font-semibold py-2 px-4 border border-transparent rounded">
                                                            <box-icon class='mr-1' name='exit'></box-icon>
                                                            <span class="mr-2 inline-block align-top">Exit </span>
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
                        <!--/ Member Tab -->

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