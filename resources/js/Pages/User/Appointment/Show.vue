<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import ProfessionalSideBar from '@/Components/SideBar/ProfessionalSideBar.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavTabButton from '@/Components/NavTabButton.vue';
import NavTabBar from '@/Components/TopBar/NavTabBar.vue';

import TaskList from '@/Pages/User/Appointment/Components/TaskList.vue'
import ChatList from '@/Pages/User/Appointment/Components/ChatList.vue'
import MemberList from '@/Pages/User/Appointment/Components/ChatList.vue'
// Import inertia
import { useForm, Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref } from "vue";
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout, ContainerWithSideBar, ProfessionalSideBar, DropdownLink,
        NavTabButton, NavTabBar, Dropdown,
        Inertia, useForm, Head, Link, ref,
        TaskList, ChatList, MemberList,
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
                            <MemberList :professional="professional" :appointments="appointments" :userAppointment="appointment"/>
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