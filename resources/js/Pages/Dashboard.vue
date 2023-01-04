<script>
// Import layout
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ContainerWithSideBar from '@/Components/ContainerWithSideBar.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ScheduleViewer from "@/Components/Editor/ScheduleViewer.vue";
import NavTabButton from '@/Components/NavTabButton.vue';
import NavTabBar from '@/Components/TopBar/NavTabBar.vue';
// Import inertia
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref } from "vue";
// Option API
export default {
    components: {
        BreezeAuthenticatedLayout,
        ContainerWithSideBar,
        ScheduleViewer,
        NavTabButton,
        NavTabBar,
        DropdownLink,
        Dropdown,
        Inertia,
        Link,
        Head,
        ref,
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
        events: Object, default: () => ({}),
        contents: Object, default: () => ({}),
        tasks: Object, default: () => ({}),
        can: Object, default: () => ({}),
    },
    setup() {
        let time = ref([]);
        return {time};
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
            <NavTabBar v-if="events.length">
         
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
            <div v-if="tab === 'home'" class="px-5 sm:px-2 md:col-span-3 gap-y-10 gap-x-6 xl:gap-x-3">
                <div class="bg-white overflow-hidden sm:rounded-lg">
                    <div class="grid gap-0 md:gap-5 grid-cols-12">
                        <div class="mt-3 mb-3 col-span-12 sm:col-span-4">
                            <div class="mt-3 bg-slate-50 sm:overflow-hidden sm:rounded-md">
                                <div class="flex justify-between">
                                    <div class="px-6 py-6">
                                        <p class="text-md font-bold"> {{ user.name }} </p>
                                        <p class="text-sm font-base"> Good Day </p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-left px-6 pb-6">
                                    <div v-if="appointments.length">
                                        <p> {{ appointments[0].event_title }} </p>
                                        <p> {{ appointments[0].start_date }} {{ appointments[0].start_time }} </p>
                                        <Link :href="route('appointment.show', appointments[0].id)" class="mt-2 inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-slate-500 hover:fill-white text-white bg-slate-400 font-semibold py-3 px-4 border border-transparent rounded">
                                            <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                            <span class="inline-block align-top">Upcomming</span>
                                        </Link>
                                    </div>
                                    <div v-else>
                                        <p> Join an event now! </p>
                                        <p> Go to Resource to find out more </p>
                                        <Link :href="route('event.index')" class="mt-2 inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-slate-500 hover:fill-white text-white bg-slate-400 font-semibold py-3 px-4 border border-transparent rounded">
                                            <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                            <span class="inline-block align-top">Upcomming</span>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="mt-3 mb-3 col-span-12 sm:col-span-4">
                            <div class="mt-3 bg-emerald-50 sm:overflow-hidden sm:rounded-md">
                                <div class="flex justify-between">
                                    <div class="px-6 py-6">
                                        <p class="text-md font-bold"> Total {{ answers.length }} <span v-if="answers.length > 1"> Answers</span> <span v-else> Answer </span> </p>
                                        <p class="text-sm font-base"> In {{ uniqueAnswer.length }} <span v-if="uniqueAnswer.length > 1">Contents</span> <span v-else>Content</span> </p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-left px-6 pb-6">
                                    <div v-if="answers.length">
                                        <p>Recent Complete:</p>
                                        <p>{{ answers[0].content_title }}</p>
                                    </div>
                                    <div v-else>
                                        <p>Start Your</p>
                                        <p>Healing Journey</p>
                                    </div>
                                    <Link v-if="answers.length" :href="route('answer.show', answers[0].id)" class="mt-2 inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-emerald-400 hover:fill-white text-white bg-emerald-300 font-semibold py-3 px-4 border border-transparent rounded">
                                        <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                        <span class="inline-block align-top">Answer</span>
                                    </Link>
                                    <Link v-else :href="route('answer.index')" class="mt-2 inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-emerald-400 hover:fill-white text-white bg-emerald-300 font-semibold py-3 px-4 border border-transparent rounded">
                                        <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                        <span class="inline-block align-top">Event</span>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 mb-3 col-span-12 sm:col-span-4">
                            <div class="mt-3 bg-emerald-50 sm:overflow-hidden sm:rounded-md">
                                <div class="flex justify-between">
                                    <div class="px-6 py-6">
                                        <p class="text-md font-bold"> Total {{ appointments.length }} <span v-if="uniqueAnswer.length > 1"> Appointments</span> <span v-else> Appointment </span> </p>
                                        <p class="text-sm font-base"> In {{ uniqueAppointment.length }} <span v-if="uniqueAppointment.length > 1">Events</span> <span v-else>Event</span> </p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-left px-6 pb-6">
                                    <div v-if="uniqueAppointment.length">
                                        <p>Recent Join:</p>
                                        <p>{{ uniqueAppointment[0].event_title }}</p>
                                    </div>
                                    <div v-else>
                                        <p>Start Your</p>
                                        <p>Healing Journey</p>
                                    </div>

                                    <Link v-if="uniqueAppointment.length" :href="route('event.show', uniqueAppointment[0].event_id)" class="mt-2 inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-emerald-400 hover:fill-white text-white bg-emerald-300 font-semibold py-3 px-4 border border-transparent rounded">
                                        <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                        <span class="inline-block align-top">Event</span>
                                    </Link>
                                    <Link v-else :href="route('event.index')" class="mt-2 inline-flex items-center text-left w-full fill-white hover:text-white hover:bg-emerald-400 hover:fill-white text-white bg-emerald-300 font-semibold py-3 px-4 border border-transparent rounded">
                                        <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                        <span class="inline-block align-top">Event</span>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden sm:rounded-lg mt-3 md:mt-0">
                    <div v-if="tasks.length">
                        <div v-for="appointment in appointments" :key="appointment.id">
                            <div v-for="(task, index) in appointment.task" class="mt-3 mb-3 bg-violet-50 sm:overflow-hidden sm:rounded-md">
                                <div class="flex justify-between">
                                    <div class="px-6 py-6 font-bold">
                                        {{ index + 1 }}. {{ task.title }}
                                    </div>
                                </div>
                                    <div class="flex flex-col items-left px-6 pb-6">
                                    <p v-html="task.description"  class="mt-3 line-clamp-3"></p>
                                    <div class="mt-2">
                                        <Link @click="showTask(appointment.id, task.id)" class="inline-flex items-center text-left fill-white hover:text-white hover:bg-violet-600 hover:fill-white text-white bg-violet-500 font-semibold py-3 px-4 border border-transparent rounded">
                                            <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                            <span class="inline-block align-top">{{ task.content_title }}</span>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div class="mt-3 mb-3 bg-violet-50 sm:overflow-hidden sm:rounded-md">
                            <div class="flex justify-between">
                                <div class="px-6 py-6 font-bold">
                                    No Task
                                </div>
                            </div>
        
                            <div class="flex flex-col items-left px-6 pb-6">
                                <p>Task allow Professional to know better about you. It is usually assigned piece of work often to be finished within a certain time.</p>
                                <Link :href="route('event.index')" class="mt-2 inline-flex items-center text-left w-40 fill-white hover:text-white hover:bg-violet-500 hover:fill-white text-white bg-violet-400 font-semibold py-3 px-4 border border-transparent rounded">
                                    <box-icon class="mr-2" name='spreadsheet'></box-icon>
                                    <span class="inline-block align-top">Join event</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="tab === 'home'" class="px-5 sm:px-0 md:col-span-1 md:mt-0 mt-5 grid grid-cols-1 gap-y-10 gap-x-6 grid-cols-1">
                <div v-for="content in contents.data" :key="content.id" >
                    <Link :href="route('content.show', content.id)">
                        <div class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-grey-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                            <img v-if="content.image" :src="'/storage/' + content.image" alt="image" class="h-full w-full object-cover object-center lg:h-full lg:w-full" />
                            <img v-else src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg" alt="images" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                        </div>
                        <div class="mt-4 flex justify-between p-2 bg-white">
                            <div>
                                <h3 class="text-base font-bold text-gray-900 line-clamp-1">
                                    {{ content.title }}
                                </h3>
                                <p v-html="content.description" class="mt-1 text-sm text-gray-600 line-clamp-3"></p>
                                <p v-if="content.questionCount" class="mt-1 text-sm text-gray-600"> {{ content.questionCount }} Questions
                                    <span v-if="content.format_category === 'Unit'" class="mt-1 text-sm text-gray-600"> * </span>
                                </p>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
             
            <div v-if="tab === 'schedule'"  class="px-5 sm:px-0 md:col-span-4 grid grid-cols-1">
                <h1 class="text-3xl font-bold">Schedule</h1>
                <div v-for="event in events" :key="event.id">
                    <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                        <div class="flex justify-between">
                            <Link :href="route('events.show', [event.id])" class="font-semibold text-lg">
                                {{ event.title }}
                            </Link>
                        </div>
                        <ScheduleViewer v-model="time" :schedules="event.schedule" :events="event" :appointments="event.appointment" :index="index" />
                    </div>
                </div>
            </div>
        </template>
        <!--/ #Main -->

    </BreezeAuthenticatedLayout>
</template>
