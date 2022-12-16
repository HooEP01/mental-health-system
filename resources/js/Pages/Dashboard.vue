<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavTabButton from '@/Components/NavTabButton.vue';
import NavTabBar from '@/Components/TopBar/NavTabBar.vue';
// Import inertia
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        NavTabButton,
        NavTabBar,
        DropdownLink,
        Dropdown,
        Inertia,
        Link,
        Head,
    },
    data() {
        return {
            tab: 'home',
        };
    },
    props: {
        user: Object, default: () => ({}),
        appointments: Object, default: () => ({}),
        answers: Object, default: () => ({}),
        uniqueAnswer: Object, default: () => ({}),
        uniqueAppointment: Object, default: () => ({}),
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



<template>
    <!-- Header -->
    <Head title="Dashboard" />
    <!--/ Header -->

    <!-- Breeze Authenticated layout -->
    <BreezeAuthenticatedLayout>
        <!-- #Header -->
        <template #header>
            <!-- Title Header -->
            <div class="pb-6 mb-2">
                <p class="text-base font-normal">Welcome Back</p>
                Dashboard
            </div>
            <!--/ Title Header -->

            <!-- NavTabBar -->
            <NavTabBar>
         
                <!-- Content Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('home')" :active="tab === 'home'" class="inline-block p-4 rounded-t-lg border-b-2"> 
                        <box-icon class='mr-2' name='book-heart'></box-icon>
                        <span class="inline-block align-top">Home</span>
                    </NavTabButton>
                </li>
                <!--/ Content Tab -->

                <!-- Schedule Tab -->
                <li class="mr-6">
                    <NavTabButton @click="activeTab('schedule')" :active="tab === 'schedule'" class="inline-block p-4 rounded-t-lg border-b-2">
                        <box-icon class='mr-2' name='book-add'></box-icon>
                        <span class="inline-block align-top">Schedule</span>
                    </NavTabButton>
                </li>
                <!--/ Schedule Tab -->

            </NavTabBar>
            <!--/ NavTabBar -->      
        </template>
        <!--/ #Header -->

        <!-- #Main -->
        <template #content>
            <div v-if="tab === 'home'" class="px-4 sm:px-0 md:col-span-2 md:mt-0 mt-5 gap-y-10 gap-x-6 xl:gap-x-3">
                <div class="bg-white overflow-hidden sm:rounded-lg">
                    <p class="text-3xl font-bold">Stats</p>
                    <div class="mt-3 bg-emerald-50 sm:overflow-hidden sm:rounded-md">
                        <div class="flex justify-between">
                            <div class="px-6 py-6 font-bold">
                                {{ user.name }}
                            </div>
                        </div>
                        <div class="flex flex-col items-left px-6 pb-6">
                            <p class="mt-3" v-if="appointments.length">
                                {{ appointments[0].event_title }}
                            </p>
                            <p v-else class="mt-3">Duis quis erat mauris. Quisque dictum dui nec est sollicitudin, nec tempor metus vehicula. Duis lacinia quam id lectus venenatis posuere a non sem.</p>
                            <Link v-if="appointments.length" :href="route('appointment.show', appointments[0].id)" class="mt-2 inline-flex items-center text-left w-64 fill-white hover:text-white hover:bg-emerald-500 hover:fill-white text-white bg-emerald-400 font-semibold py-3 px-4 border border-transparent rounded">
                                <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                <span class="inline-block align-top">Upcomming Appointment</span>
                            </Link>
                            <Link v-else="appointments.length" :href="route('event.index')" class="mt-2 inline-flex items-center text-left w-72 fill-white hover:text-white hover:bg-emerald-500 hover:fill-white text-white bg-emerald-400 font-semibold py-3 px-4 border border-transparent rounded">
                                <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                <span class="inline-block align-top">No Upcomming Appointment</span>
                            </Link>
                        </div>
                    </div>

                    <div class="grid gap-5 grid-cols-12">
                        <div class="mt-3 mb-3 col-span-6">
                            <div class="mt-3 bg-indigo-50 sm:overflow-hidden sm:rounded-md">
                                <div class="flex justify-between">
                                    <div class="px-6 py-6 font-bold">
                                        Total <span class="font-bold">{{ answers.length }}</span>
                                        <span v-if="answers.length > 1"> Answers</span>
                                        <span v-else> Answer</span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-left px-6 pb-6">
                                    <p class="mt-3 font-semibold">
                                        In {{ uniqueAnswer.length }} 
                                        <span v-if="uniqueAnswer.length > 1">Contents</span>
                                        <span v-else>Content</span>    
                                    </p>
                                    <p v-if="answers.length">Recent Complete Answer - {{ answers[0].content_title }}</p>
                                    <p v-else>Vivamus id lacus id leo lobortis dignissim sed quis lorem. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. </p>
                                    <Link v-if="answers.length" :href="route('answer.show', answers[0].id)" class="mt-2 inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-indigo-500 hover:fill-white text-white bg-indigo-400 font-semibold py-3 px-4 border border-transparent rounded">
                                        <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                        <span class="inline-block align-top">Answer</span>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 col-span-6">
                            <div class="mt-3 bg-indigo-50 sm:overflow-hidden sm:rounded-md">
                                <div class="flex justify-between">
                                    <div class="px-6 py-6 font-bold">
                                        Total <span class="font-bold">{{ appointments.length }}</span>
                                        <span v-if="appointments.length > 1"> Appointments</span>
                                        <span v-else> Appointment</span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-left px-6 pb-6">
                                    <p class="mt-3 font-semibold">
                                        In {{ uniqueAppointment.length}} 
                                        <span v-if="uniqueAppointment.length > 1">Events</span>
                                        <span v-else>Event</span>
                                    </p>
                                    <p v-if="uniqueAppointment.length">Recent Join Event - {{ uniqueAppointment[0].event_title }}</p>
                                    <p v-else>Vivamus id lacus id leo lobortis dignissim sed quis lorem. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. </p>
                                    <Link v-if="uniqueAppointment.length" :href="route('event.show', uniqueAppointment[0].event_id)" class="mt-2 inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-indigo-500 hover:fill-white text-white bg-indigo-400 font-semibold py-3 px-4 border border-transparent rounded">
                                        <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                        <span class="inline-block align-top">Event</span>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 bg-amber-50 sm:overflow-hidden sm:rounded-md">
                        <div class="flex justify-between">
                            <div class="px-6 py-6 font-bold">
                                {{ user.name }}
                            </div>
                        </div>
                        <div class="flex flex-col items-left px-6 pb-6">
                            <p v-if="appointments.length" class="mt-3">Upcomming Joined Event:  <span class="font-semibold"> {{ appointments[0].event_title }} </span></p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="px-4 sm:px-0 md:col-span-2 md:mt-0 mt-5 gap-y-10 gap-x-6 xl:gap-x-3">
                <div class="bg-white overflow-hidden sm:rounded-lg">
                    <p class="text-3xl font-bold">Task</p>
                    <div class="grid gap-5 grid-cols-12"></div>

                    <div v-if="appointments.length">
                        <div v-for="(task, index) in appointments[0].task" class="mt-3 mb-3 bg-violet-50 sm:overflow-hidden sm:rounded-md">
                            <div class="flex justify-between">
                                <div class="px-6 py-6 font-bold">
                                    {{ index + 1 }}. {{ task.title }}
                                </div>
                                <div></div>
                                <Dropdown class="flex justify-end px-4 pt-4">
                                    <template #trigger>
                                        <button id="dropdownButton" data-dropdown-toggle="dropdown" class="inline-block text-gray-500 dark:text-gray-400 rounded-lg text-sm p-1.5" type="button">
                                            <span class="sr-only">Open dropdown</span>
                                            <box-icon name='dots-horizontal-rounded'></box-icon>
                                        </button>
                                    </template>

                                    <template #content>
                                        <ul class="py-1" aria-labelledby="dropdownButton">
                                            <li>
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                                            </li>
                                            <li>
                                                <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                            </li>
                                        </ul>
                                    </template>
                                </Dropdown>
                                </div>
                                <div class="flex flex-col items-left px-6 pb-6">
                                    <p v-html="task.description"  class="mt-3 line-clamp-3"></p>
                                </div>
                        </div>
                    </div>
                    <div v-else>
                        <div class="mt-3 mb-3 bg-violet-50 sm:overflow-hidden sm:rounded-md">
                            <div class="flex justify-between">
                                <div class="px-6 py-6 font-bold">
                                    Empty
                                </div>
                            </div>
                            
                            <div class="flex flex-col items-left px-6 pb-6">
                                <p>Task allow Professional to know better about you. Suspendisse magna lacus, molestie vitae nisi sed, faucibus facilisis lorem.</p>
                                <Link :href="route('event.index')" class="mt-2 inline-flex items-center text-left w-40 fill-white hover:text-white hover:bg-violet-500 hover:fill-white text-white bg-violet-400 font-semibold py-3 px-4 border border-transparent rounded">
                                    <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                    <span class="inline-block align-top">Join event</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <!--/ #Main -->

    </BreezeAuthenticatedLayout>
</template>
